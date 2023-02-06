<?php

namespace Idez\Caradhras\Clients;

use GuzzleHttp\Psr7\Stream;
use Idez\Caradhras\Data\Individual;
use Idez\Caradhras\Data\P2PTransferPayload;
use Idez\Caradhras\Data\Registrations\PersonRegistration;
use Idez\Caradhras\Data\TransactionCollection;
use Idez\Caradhras\Enums\AccountStatus;
use Idez\Caradhras\Enums\AddressType;
use Idez\Caradhras\Enums\Documents\DocumentErrorCode;
use Idez\Caradhras\Enums\Documents\DocumentSelfieReasonCode;
use Idez\Caradhras\Exceptions\CaradhrasException;
use Idez\Caradhras\Exceptions\Documents\DuplicatedImageException;
use Idez\Caradhras\Exceptions\Documents\FaceNotVisibleException;
use Idez\Caradhras\Exceptions\Documents\InconsistentSelfieException;
use Idez\Caradhras\Exceptions\Documents\InvalidDocumentException;
use Idez\Caradhras\Exceptions\Documents\InvalidSelfieException;
use Idez\Caradhras\Exceptions\Documents\LowQualitySelfieException;
use Idez\Caradhras\Exceptions\Documents\SendDocumentException;
use Idez\Caradhras\Exceptions\FailedCreatePersonalAccount;
use Idez\Caradhras\Exceptions\FraudDetectorException;
use Idez\Caradhras\Exceptions\InsufficientBalanceException;
use Idez\Caradhras\Exceptions\TransferFailedException;
use Illuminate\Support\Str;
use Throwable;

class CaradhrasMainClient extends BaseApiClient
{
    public const API_PREFIX = 'api';

    /**
     * Get P2P transfer.
     *
     * @param  array  $filters
     * @return P2PTransferPayload
     * @throws \Idez\Caradhras\Exceptions\CaradhrasException;
     * @throws \Illuminate\Http\Client\RequestException
     */
    public function getTransfer(array $filters): P2PTransferPayload
    {
        $transfers = $this->apiClient()
            ->get("/p2ptransfer", $filters)
            ->throw()
            ->json();

        if (blank($transfers)) {
            throw new CaradhrasException('Transfer Not Found.', 404);
        }

        return new P2PTransferPayload(head((array) $transfers));
    }

    /**
     * Get individual.
     *
     * @param  string  $registrationId
     * @param  string  $document
     * @return object
     * @throws Idez\Caradhras\Exceptions\CaradhrasException
     */
    public function findIndividual(string $registrationId, string $document): object
    {
        $response = $this
            ->apiClient(throwsHttpError: false)
            ->asJson()
            ->get("/v2/individuals", ['document' => $document]);

        if ($response->failed()) {
            throw CaradhrasException::failedGetIndividual($response);
        }

        $individual = collect(object_get($response->object(), 'items', []))
            ->firstWhere(fn ($individual) => $individual?->idRegistration === $registrationId);

        if (blank($individual)) {
            throw CaradhrasException::failedGetIndividual($response);
        }

        return $individual;
    }

    /**
     * Update a Person.
     *
     * @param  PersonRegistration  $person
     * @return object
     * @throws Exception
     */
    public function updateIndividuals(PersonRegistration $person): object
    {
        return $this
            ->apiClient()
            ->asJson()
            ->put("/v2/individuals/{$person->id}", $person->jsonSerialize())
            ->throw()
            ->object();
    }

    /**
     * Get account.
     *
     * @param  int  $accountId
     * @return object
     * @throws Exception
     */
    public function getAccount(int $accountId): object
    {
        return $this->apiClient()
            ->retry(3, 1500)
            ->get("/contas/{$accountId}")
            ->throw()
            ->object();
    }

    /**
     * Get information.
     *
     * @param  string  $document
     * @return object
     * @throws \Illuminate\Http\Client\RequestException
     */
    public function backgroundCheck(string $document): object
    {
        return $this->apiClient()->post('/knowyourclient/people', [
            'document' => $document,
            'resources' => [1, 3, 4],
        ])->throw()->object();
    }

    /**
     * Get transactions.
     *
     * @param  int  $accountId
     * @param  array  $query
     * @return array
     */
    public function getTransactions(int $accountId, array $query = []): TransactionCollection
    {
        return new TransactionCollection(
            $this
                ->apiClient()
                ->retry(3, 1500)
                ->get("/accounts/{$accountId}/transactions", $query)
                ->json()
        );
    }

    /**
     * Update address.
     *
     * @param  array  $addressData
     * @return object
     * @throws Idez\Caradhras\Exceptions\CaradhrasException
     */
    public function updateAddress(array $addressData): object
    {
        try {
            $queryString = http_build_query($addressData);
            $request = $this->apiClient()->put("/enderecos?{$queryString}")->throw();
        } catch (Throwable $exception) {
            throw new CaradhrasException(trans("errors.services.caradhras.update_address_failed"), 502, 'caradhras.update_address_failed');
        }

        return $request->object();
    }

    /**
     * Create a P2P transfer.
     *
     * @param  array  $data
     * @param  string  $id
     * @return object
     * @throws Idez\Caradhras\Exceptions\InsufficientBalanceException
     * @throws Idez\Caradhras\Exceptions\TransferFailedException
     */
    public function p2p(array $data, string $id): object
    {
        $request = $this->apiClient(false)
            ->asJson()
            ->withHeaders(['transactionUUID' => $id])
            ->post('/p2ptransfer', $data);

        if ($request->failed()) {
            $errorMessage = $request->json('message', '');
            $errorCode = $request->json('code', '');

            if ($request->clientError() && Str::of($errorMessage)->startsWith('Saldo insuficiente')) {
                throw new InsufficientBalanceException();
            }

            if ($errorCode === 'CHECK_FRAUD') {
                throw new FraudDetectorException();
            }

            throw new TransferFailedException($errorMessage);
        }

        return $request->object();
    }

    /**
     * Get balance.
     *
     * @param  int  $accountId
     * @return float
     * @throws Exception
     */
    public function getBalance(int $accountId): float
    {
        return (float) $this->getAccount($accountId)->saldoDisponivelGlobal ?? 0.0;
    }

    /**
     * Create account with existing data.
     *
     * @param  int  $idPessoa
     * @param  int  $idEnderecoCorrespondencia
     * @param  int  $idOrigemComercial
     * @param  int  $idProduto
     * @param  int  $diaVencimento
     * @param  float  $valorRenda
     * @param  int  $valorPontuacao
     * @return object
     */
    public function createAccount(int $idPessoa, int $idEnderecoCorrespondencia, int $idOrigemComercial, int $idProduto, int $diaVencimento, float $valorRenda, int $valorPontuacao = 0): object
    {
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
     * @return object
     * @throws \Illuminate\Http\Client\RequestException
     */
    public function listAccounts(array $search = []): object
    {
        return $this->apiClient()->get('/contas', $search)->throw()->object();
    }

    public function getAddressByIndividualId(int $individualId, int $tipoEndereco = 1)
    {
        return $this->apiClient()
            ->get("/enderecos", ['idPessoa' => $individualId, 'idTipoEndereco' => $tipoEndereco])
            ->throw()
            ->object();
    }

    /**
     * Update account product.
     *
     * @param  int  $accountId
     * @return object
     * @throws Exception
     */
    public function updateAccountProduct(int $accountId): null|object
    {
        $data = [
            'idProduto' => config('caradhras.app.product_id'),
            'idOrigemComercial' => config('caradhras.app.business_source_id'),
        ];

        return $this
            ->apiClient()
            ->asJson()
            ->post("/contas/{$accountId}/alterar-produto", $data)
            ->object();
    }

    /**
     * Get Account transactions.
     *
     * @param  int  $accountId
     * @param  array  $query  (optional). Default [].
     * @return TransactionCollection
     * @throws Exception
     */
    public function transactions(int $accountId, array $query = []): TransactionCollection
    {
        $response = $this
            ->apiClient()
            ->get("/accounts/{$accountId}/transactions", $query);

        return new TransactionCollection($response->json());
    }

    /**
     * Block account.
     *
     * @param  int  $accountId
     * @return object
     */
    public function blockAccount(int $accountId): object
    {
        return $this
            ->apiClient()
            ->post("/contas/{$accountId}/bloquear")
            ->object();
    }

    /**
     * @param  int  $accountId
     * @return object
     */
    public function cancelAccount(int $accountId): object
    {
        $status = AccountStatus::Canceled->value;

        return $this
            ->apiClient()
            ->post("/contas/{$accountId}/cancelar?id_status={$status}")
            ->object();
    }

    /**
     * Create an individual from data.
     *
     * @param  array  $data
     * @return Individual
     */
    public function createIndividual(array $data): Individual
    {
        $response = $this->apiClient()->asForm()->post("/pessoas", $data);

        return new Individual($response->object());
    }

    /**
     * Link account additional.
     *
     * @param  int  $accountId
     * @param  int  $individualId
     * @param  string  $name
     * @param  string|null  $email
     * @return object
     */
    public function linkAccountAdditional(
        int $accountId,
        int $individualId,
        string $name,
        null|string $email = null
    ): object {
        $response = $this->apiClient()
            ->post("/contas/{$accountId}/adicionais", [
                'idPessoa' => $individualId,
                'nomeImpresso' => $name,
                'email' => $email,
            ]);

        return $response->object();
    }

    /**
     * Create address.
     *
     * @param  int  $idPessoa
     * @param  string  $logradouro
     * @param  int  $numero
     * @param  string|null  $complemento
     * @param  string  $bairro
     * @param  string  $cidade
     * @param  string  $uf
     * @param  string  $cep
     * @param  string  $pais
     * @param  AddressType  $tipoEndereco
     * @return object
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
     *
     * @param  string  $registrationId
     * @return object
     */
    public function getPendingAccountDocuments(string $registrationId): object
    {
        $response = $this->apiClient()
            ->get("/v2/individuals/{$registrationId}/documents/status");

        return $response->object();
    }

    /**
     * Search P2P
     *
     * @param  array  $filters
     * @return array|object
     */
    public function searchP2P(array $filters)
    {
        return $this->apiClient()
            ->get('/p2ptransfer', $filters)
            ->object();
    }

    /**
     * Get account transactions.
     *
     * @param  int  $accountId
     * @param  int  $limit
     * @param  int  $page
     * @return TransactionCollection
     */
    public function getAccountTransactions(int $accountId, int $limit = 25, int $page = 0): TransactionCollection
    {
        $response = $this->apiClient()
            ->get("/accounts/{$accountId}/transactions", [
                'page' => $page,
                'limit' => $limit,
            ]);

        return new TransactionCollection($response);
    }

    public function createPerson(PersonRegistration $personRegistration)
    {
        $response = $this->apiClient(false)
            ->post('/v2/individuals', $personRegistration->toArray());

        return $response->object();
    }

    /**
     * Add a document to a personal account.
     *
     * @param  string  $registrationId
     * @param  string  $documentType
     * @param  Stream  $file
     * @param  string  $contentType
     * @return Idez\Caradhras\Data\Registrations\PersonRegistration
     *
     * @throws Idez\Caradhras\Exceptions\DuplicatedImageException
     * @throws Idez\Caradhras\Exceptions\FaceNotVisibleException
     * @throws Idez\Caradhras\Exceptions\InconsistentSelfieException
     * @throws Idez\Caradhras\Exceptions\InvalidDocumentException
     * @throws Idez\Caradhras\Exceptions\InvalidSelfieException
     * @throws Idez\Caradhras\Exceptions\LowQualitySelfieException
     * @throws Idez\Caradhras\Exceptions\SendDocumentException
     */
    public function addPersonDocument(string $registrationId, string $documentType, Stream $file, string $contentType = 'image/jpeg'): PersonRegistration
    {
        $queryParams = http_build_query([
            'additionalDetails' => true,
            'category' => $documentType,
        ]);

        $response = $this->apiClient(false)
            ->withBody($file, $contentType)
            ->post("/v2/individuals/{$registrationId}/documents?" . $queryParams);

        if ($response->failed()) {
            $errorCode = $response->json('errorCode');
            $reasonCode = $response->json('reasonCode');

            if (filled($errorCode)) {
                throw match (DocumentErrorCode::tryFrom($errorCode)) {
                    DocumentErrorCode::DuplicatedImage => new DuplicatedImageException(),
                    DocumentErrorCode::InvalidSelfie => match (DocumentSelfieReasonCode::tryFrom($reasonCode)) {
                        DocumentSelfieReasonCode::LowQuality => new LowQualitySelfieException(),
                        DocumentSelfieReasonCode::FaceNotVisible => new FaceNotVisibleException(),
                        DocumentSelfieReasonCode::Inconsistent => new InconsistentSelfieException(),
                        default => new InvalidSelfieException()
                    },
                    default => new InvalidDocumentException(),
                };
            }

            throw new SendDocumentException($response->json(), $response->status());
        }

        return new PersonRegistration($response->object());
    }

    /**
     * Create personal account.
     *
     * @param  array  $payload
     * @return object
     * @throws Idez\Caradhras\Exceptions\CaradhrasException
     * @throws Idez\Caradhras\Exceptions\FailedCreatePersonalAccount
     */
    public function createPersonalAccount(array $payload): object
    {
        $response = $this->apiClient(false)->post('/v2/individuals/accounts', $payload);

        if ($response->failed()) {
            if ($response->clientError()) {
                throw new FailedCreatePersonalAccount($response->json(), $response->status());
            }

            throw new CaradhrasException('Failed to create personal account');
        }

        return $response->object();
    }

    /**
     * @param  int  $originAccountId
     * @param  int  $destinationAccountId
     * @param  float  $amount
     * @param  string|null  $uuid
     * @param  string  $description
     * @param  string  $details
     * @return object
     */
    public function transfer(int $originAccountId, int $destinationAccountId, float $amount, string $uuid = null, string $description = '', string $details = ''): object
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
     * @return object
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
            ->get("/v2/individuals/$personId", ["statusSPD" => 'true']);
    }

    /** @deprecated */
    public function getBankTransfer(int $adjustmentId)
    {
        return $this->apiClient()->get("/banktransfers/adjustment/{$adjustmentId}")->throw()->json();
    }

    /** @deprecated */
    public function listBankTransferOut(int $accountId)
    {
        return $this->apiClient()
            ->get("/banktransfers/account/{$accountId}")
            ->throw()
            ->object();
    }
}
