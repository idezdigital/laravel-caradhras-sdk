<?php

namespace Idez\Caradhras\Clients;

use Idez\Caradhras\Data\P2PTransferPayload;
use Idez\Caradhras\Data\TransactionCollection;
use Idez\Caradhras\Enums\AccountStatusCode;
use Idez\Caradhras\Enums\AddressType;
use Idez\Caradhras\Exceptions\CaradhrasException;
use Idez\Caradhras\Exceptions\PhoneRechargeConfirmationFailedException;
use Illuminate\Http\Client\RequestException;
use Throwable;

class CaradhrasMainClient extends BaseApiClient
{
    public const API_PREFIX = 'api';

    public function getTransfer(array $filters): P2PTransferPayload
    {
        $transfers = $this->apiClient()
            ->get('/p2ptransfer', $filters)
            ->throw()
            ->json();

        if (blank($transfers)) {
            throw new CaradhrasException('Transfer Not Found.', 404);
        }

        return new P2PTransferPayload(head((array) $transfers));
    }

    public function cancelAccount(int $accountId, AccountStatusCode $status = AccountStatusCode::Canceled): object
    {
        return $this
            ->apiClient()
            ->post("/contas/{$accountId}/cancelar?id_status={$status->value}")
            ->object();
    }

    /**
     * Get account.
     */
    public function getAccount(int $accountId): object
    {
        return $this->apiClient()
            ->retry(3, 1500)
            ->get("/contas/{$accountId}")
            ->throw()
            ->object();
    }

    public function transfer(int $originAccountId, int $destinationAccountId, float $amount, ?string $uuid = null, string $description = '', string $details = ''): object
    {
        return $this
            ->apiClient()
            ->withHeaders([
                'transactionUUID' => $uuid,
            ])
            ->post('/p2ptransfer', [
                'amount' => $amount,
                'description' => $description,
                'originalAccount' => $originAccountId,
                'destinationAccount' => $destinationAccountId,
                'transactionDetails' => $details,
            ])
            ->object();
    }

    /**
     * Get individual.
     *
     * @param  int  $personId
     *
     * @throws Exception
     */
    public function getIndividual($personId): object
    {
        if (is_null($personId)) {
            throw new CaradhrasException('Failed to get individual.');
        }

        return $this
            ->apiClient()
            ->asJson()
            ->get("/v2/individuals/$personId", ['statusSPD' => 'true'])
            ->object();
    }

    /**
     * Get individual.
     *
     * @throws \App\Exceptions\CaradhrasException
     */
    public function findIndividual(string $registrationId, string $document): object
    {
        $response = $this
            ->apiClient(throwsHttpError: false)
            ->asJson()
            ->get('/v2/individuals', ['document' => $document]);

        if ($response->failed()) {
            $message = $response->status() === 404 ? 'Failed to get individual.' : 'Individual not found.';
            $statusCode = $response->status() === 404 ? 404 : 502;

            throw new CaradhrasException($message, $statusCode);
        }

        $individual = collect(object_get($response->object(), 'items', []))
            ->filter(fn ($individual) => $individual?->idRegistration === $registrationId)
            ->first();

        if (! isset($individual)) {
            throw new CaradhrasException('Individual not found.', 404);
        }

        return $individual;
    }

    /**
     * Create phone recharge.
     *
     * @throws RequestException
     */
    public function createPhoneRecharge(string $dealerCode, string $areaCode, string $phoneNumber): object
    {
        return $this->apiClient()->post('/recharges', [
            'dealerCode' => $dealerCode,
            'ddd' => $areaCode,
            'phoneNumber' => $phoneNumber,
        ])->throw()->object();
    }

    /**
     * @throws PhoneRechargeConfirmationFailedException
     */
    public function confirmPhoneRecharge(string $orderId, int $accountId, float $amount): object|array
    {
        $response = $this->apiClient(false)->post("/recharges/{$orderId}/confirm", [
            'accountId' => $accountId,
            'amount' => $amount,
        ]);

        if ($response->failed()) {
            throw new PhoneRechargeConfirmationFailedException((array) $response->body());
        }

        return $response->object();
    }

    /**
     * Update account product.
     */
    public function updateAccountProduct(int $accountId, int $productId, int $businessSourceId): ?object
    {
        return $this
            ->apiClient()
            ->asJson()
            ->post("/contas/{$accountId}/alterar-produto", [
                'idProduto' => $productId,
                'idOrigemComercial' => $businessSourceId,
            ])
            ->object();
    }

    /**
     * Block account.
     */
    public function blockAccount(int $accountId, int $idStatus = 1): object
    {
        return $this
            ->apiClient()
            ->withQueryParameters(['id_status' => $idStatus])
            ->post("/contas/{$accountId}/bloquear")
            ->object();
    }

    /**
     * Reactivate account.
     *
     * @throws CaradhrasException
     */
    public function reactivateAccount(int $accountId): bool
    {
        $response = $this->apiClient(false)->post("/contas/{$accountId}/reativar");

        if ($response->failed()) {
            $message = $response->object()?->message;

            throw new CaradhrasException($message);
        }

        return true;
    }

    /**
     * Create address.
     */
    public function createAddress(
        int $idPessoa,
        string $logradouro,
        int $numero,
        ?string $complemento,
        string $bairro,
        string $cidade,
        string $uf,
        string $cep,
        string $pais = 'Brasil',
        AddressType $tipoEndereco = AddressType::Home
    ): object {
        $data = [
            'idTipoEndereco' => $tipoEndereco->value,
            'idPessoa' => $idPessoa,
            'logradouro' => $logradouro,
            'numero' => $numero,
            'complemento' => $complemento ?? '-',
            'bairro' => $bairro,
            'cidade' => $cidade,
            'uf' => $uf,
            'cep' => $cep,
            'pais' => $pais,
        ];

        $query = http_build_query($data);

        return $this->apiClient()
            ->post("/enderecos?{$query}")
            ->object();
    }

    /**
     * Get pending account documents.
     */
    public function getPendingAccountDocuments(string $registrationId): object
    {
        $response = $this->apiClient()
            ->get("/v2/individuals/{$registrationId}/documents/status");

        return $response->object();
    }

    public function listIndividualDocuments(string $registrationId): array
    {
        $response = $this
            ->apiClient()
            ->asJson()
            ->get("/v2/individuals/$registrationId/documents");

        return $response->object()?->result ?? [];
    }

    public function getDocumentUrl(string $documentId): string
    {
        $response = $this->apiClient()
            ->withoutRedirecting()
            ->get("/docspy/v1/documents/download/{$documentId}");

        return $response->header('Location');
    }

    public function listAddresses(array $filters): ?object
    {
        return $this->apiClient()
            ->get('/enderecos', $filters)
            ->throw()
            ->object();
    }

    /**
     * Update address.
     *
     * @throws \App\Exceptions\CaradhrasException
     */
    public function updateAddress(array $addressData): object
    {
        try {
            $queryString = http_build_query($addressData);
            $request = $this->apiClient()->put("/enderecos?{$queryString}")->throw();
        } catch (Throwable $exception) {
            $errorKey = 'caradhras.update_address_failed';

            throw new CaradhrasException(trans("errors.services.{$errorKey}"), 502, $errorKey);
        }

        return $request->object();
    }

    /**
     * Create account with existing data.
     */
    public function createAccount(
        int $idPessoa,
        int $idEnderecoCorrespondencia,
        int $idOrigemComercial,
        int $idProduto,
        int $diaVencimento,
        float $valorRenda,
        int $valorPontuacao = 0
    ): object {
        $response = $this
            ->apiClient()
            ->post('/contas', [
                'idPessoa' => $idPessoa,
                'idEnderecoCorrespondencia' => $idEnderecoCorrespondencia,
                'idOrigemComercial' => $idOrigemComercial,
                'idProduto' => $idProduto,
                'diaVencimento' => $diaVencimento,
                'valorRenda' => $valorRenda,
                'valorPontuacao' => $valorPontuacao,
            ]);

        return $response->object();
    }

    /**
     * Get accounts.
     *
     * @throws RequestException
     */
    public function listAccounts(array $search = []): object
    {
        return $this->apiClient()->get('/contas', $search)->throw()->object();
    }

    /**
     * Link account additional.
     */
    public function linkAccountAdditional(
        int $accountId,
        int $individualId,
        string $name,
        ?string $email = null
    ): object {
        $response = $this->apiClient()
            ->post("/contas/$accountId/adicionais", [
                'idPessoa' => $individualId,
                'nomeImpresso' => $name,
                'email' => $email,
            ]);

        return $response->object();
    }

    public function listTransactions(int $accountId, array $query = []): TransactionCollection
    {
        $response = $this
            ->apiClient()
            ->retry(3, 500)
            ->get("/accounts/{$accountId}/transactions", $query);

        return new TransactionCollection($response->json());
    }

    public function getBalance(int $accountId)
    {
        return $this->getAccount($accountId)->saldoDisponivelGlobal;
    }

    public function getProductFees(int $productId)
    {
        return $this->apiClient()->get('/produtos/configuracoes/acumuladores', [
            'idProduto' => $productId,
        ])->json('content');
    }

    public function listProducts()
    {
        return $this->apiClient()->get('/produtos')->object()->content;
    }

    public function listBusinessSources()
    {
        return $this->apiClient()->get('/origens-comerciais')->object()->content;
    }
}
