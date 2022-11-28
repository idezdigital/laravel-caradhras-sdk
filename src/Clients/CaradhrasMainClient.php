<?php

namespace Idez\Caradhras\Clients;

use Idez\Caradhras\Data\Card;
use Idez\Caradhras\Data\CardDetails;
use Idez\Caradhras\Data\CardLimit;
use Idez\Caradhras\Data\P2PTransferPayload;
use Idez\Caradhras\Data\PhoneRecharge;
use Idez\Caradhras\Data\Registrations\IndividualRegistration;
use Idez\Caradhras\Data\TransactionCollection;
use Idez\Caradhras\Enums\AccountStatus;
use Idez\Caradhras\Exceptions\CaradhrasException;
use Idez\Caradhras\Exceptions\FraudDetectorException;
use Idez\Caradhras\Exceptions\GetCardDetailsException;
use Idez\Caradhras\Exceptions\InsufficientBalanceException;
use Idez\Caradhras\Exceptions\IssuePhysicalCardException;
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
     * @throws \App\Exceptions\CaradhrasException
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
     * @throws \App\Exceptions\CaradhrasException
     */
    public function findIndividual(string $registrationId, string $document): object
    {
        $response = $this
            ->apiClient(throwsHttpError: false)
            ->asJson()
            ->get("/v2/individuals", ['document' => $document]);

        if ($response->failed()) {
            $message = $response->status() === 404 ? 'Failed to get individual.' : 'Individual not found.';
            $statusCode = $response->status() === 404 ? 404 : 502;

            throw new CaradhrasException($message, $statusCode);
        }

        $individual = collect(object_get($response->object(), 'items', []))
            ->filter(fn ($individual) => $individual?->idRegistration === $registrationId)
            ->first();

        if (blank($individual)) {
            throw new CaradhrasException('Individual not found.', 404);
        }

        return $individual;
    }

    /**
     * Associate card to account.
     *
     * @param int $cardId
     * @param int $accountId
     * @param int $individualId
     * @return bool
     * @throws RequestException
     */
    public function associateCardToAccount(int $cardId, int $accountId, int $individualId): bool
    {
        $this->apiClient()
            ->post("/contas/{$accountId}/atribuir-cartao-prepago", [
                'idCartao' => $cardId,
                'idPessoaFisica' => $individualId,
            ])
            ->throw();

        return true;
    }

    /**
     * Update a Person.
     *
     * @param  IndividualRegistration  $person
     * @return object
     * @throws Exception
     */
    public function updateIndividuals(IndividualRegistration $person): object
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
    public function getTransactions(int $accountId, array $query = []): array
    {
        return $this
            ->apiClient()
            ->retry(3, 1500)
            ->get("/accounts/{$accountId}/transactions", $query)
            ->json();
    }

    /**
     * Update address.
     *
     * @param  array  $addressData
     * @return object
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
     * Create a P2P transfer.
     *
     * @param  array  $data
     * @param  string  $id
     * @return object
     * @throws \App\Exceptions\InsufficientBalanceException
     * @throws \App\Exceptions\TransferFailedException
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
     * @param  int  $cardId
     * @return Card
     */
    public function unlockSystemBlockedCard(int $cardId): Card
    {
        $unlockRequest = $this->apiClient()->post("/cartoes/{$cardId}/desbloquear-senha-incorreta");

        return new Card($unlockRequest);
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

    public function getPhoneRecharge(int $adjustmentId): PhoneRecharge
    {
        $response = $this->apiClient()->get("/recharges/adjustment/{$adjustmentId}")
            ->throw()
            ->json();

        return new PhoneRecharge($response);
    }

    /**
     * Set card password.
     *
     * @param  string  $cardId
     * @param  string  $password
     * @return object
     * @throws \Illuminate\Http\Client\RequestException
     */
    public function setCardPassword(string $cardId, $password): object
    {
        return $this->apiClient()
            ->withHeaders(['senha' => str_pad($password, 4, '0', STR_PAD_LEFT)])
            ->post("/cartoes/{$cardId}/cadastrar-senha")
            ->throw()
            ->object();
    }

        /**
     * Update card password.
     *
     * @param  string  $cardId
     * @param  string  $password
     * @return array|object
     */
    public function updateCardPassword(string $cardId, $password)
    {
        return $this->apiClient()
            ->withHeaders(['senha' => str_pad($password, 4, '0', STR_PAD_LEFT)])
            ->put("/cartoes/{$cardId}/alterar-senha")
            ->object();
    }

    /**
     * Issue physical card.
     *
     * @param  int  $accountId
     * @param  int  $individualId
     * @return Card
     * @throws \Illuminate\Http\Client\RequestException
     * @throws \Exception
     */
    public function issuePhysicalCard(int $accountId, int $individualId): Card
    {
        $data = [
            'id_pessoa' => $individualId,
            'id_tipo_plastico' => config('app.plastic_id'),
        ];

        $issueCardResponse = $this->apiClient(false)
            ->post("/contas/{$accountId}/gerar-cartao-grafica", $data);

        if ($issueCardResponse->failed()) {
            throw new IssuePhysicalCardException();
        }

        $cardId = $issueCardResponse->json('idCartao');

        $this->setCardPassword($cardId, random_int(1000, 9999));

        return $this->getCard($cardId);
    }

    /**
     * Create virtual card.
     *
     * @param  int  $accountId
     * @param  int  $individualId
     * @return Card
     */
    public function issueVirtualCard(int $accountId, int $individualId): Card
    {
        $data = http_build_query([
            'dataValidade' => now()->addYears(5)->toDateString() . 'T00:00:00.000',
            'idPessoaFisica' => $individualId,
        ]);

        $card = $this->apiClient()
            ->post("/contas/{$accountId}/gerar-cartao-virtual?" . $data)
            ->object();

        return $this->getCard($card->idCartao);
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
            'idProduto' => config('app.product_id'),
            'idOrigemComercial' => config('app.business_source_id'),
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
     * Get Card details.
     *
     * @param  int  $cardId
     * @return CardDetails
     * @throws \App\Exceptions\GetCardDetailsException
     */
    public function getCardDetails(int $cardId): CardDetails
    {
        $response = $this
            ->apiClient(false)
            ->get("/cartoes/{$cardId}/consultar-dados-reais");

        if ($response->failed()) {
            throw new GetCardDetailsException($response->json());
        }

        return new CardDetails($response->json());
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
     * @param  AccountStatus  $status
     * @return object
     */
    public function cancelAccount(int $accountId, AccountStatus $status = AccountStatus::Canceled): object
    {
        return $this
            ->apiClient()
            ->post("/contas/{$accountId}/cancelar?id_status={$status->value}")
            ->object();
    }

    /**
     * Get card limit.
     * @param  int  $cardId
     * @param  int  $limitId
     * @return CardLimit
     * @throws \App\Exceptions\CaradhrasException
     */
    public function getCardLimit(int $cardId, int $limitId): CardLimit
    {
        if (blank($cardId)) {
            throw new CaradhrasException(trans('errors.card.not_issued'), 500);
        }

        if (blank($limitId)) {
            throw new CaradhrasException(trans('errors.card.undefined_limit'), 500);
        }

        $response = $this
            ->apiClient()
            ->get("/cartoes/{$cardId}/controles-limites/{$limitId}");

        return new CardLimit($response->json());
    }

    /**
     *
     * @param  int  $cardId
     * @param  int  $limitId
     * @param  float|null  $amount
     * @return CardLimit
     */
    public function updateCardLimit(int $cardId, int $limitId, ?float $amount = null): CardLimit
    {
        $response = $this
            ->apiClient()
            ->patch("/cartoes/{$cardId}/controles-limites/{$limitId}", [
                'limiteDiario' => $amount,
                'limiteSemanal' => $amount,
                'limiteMensal' => $amount,
            ]);

        return new CardLimit($response->json());
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
}
