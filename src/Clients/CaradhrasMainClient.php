<?php

namespace Idez\Caradhras\Clients;

use GuzzleHttp\Psr7\Stream;
use Idez\Caradhras\Data\Card;
use Idez\Caradhras\Data\CardCollection;
use Idez\Caradhras\Data\CardDetails;
use Idez\Caradhras\Data\CardLimit;
use Idez\Caradhras\Data\CardMccGroupControl;
use Idez\Caradhras\Data\CardSettings;
use Idez\Caradhras\Data\Individual;
use Idez\Caradhras\Data\P2PTransferPayload;
use Idez\Caradhras\Data\PhoneRecharge;
use Idez\Caradhras\Data\Registrations\IndividualRegistration;
use Idez\Caradhras\Data\TransactionCollection;
use Idez\Caradhras\Enums\AccountStatus;
use Idez\Caradhras\Enums\AddressType;
use Idez\Caradhras\Enums\Cards\CardStatus;
use Idez\Caradhras\Enums\Documents\DocumentErrorCode;
use Idez\Caradhras\Enums\Documents\DocumentSelfieReasonCode;
use Idez\Caradhras\Exceptions\CaradhrasException;
use Idez\Caradhras\Exceptions\CVVMismatchException;
use Idez\Caradhras\Exceptions\Documents\DuplicatedImageException;
use Idez\Caradhras\Exceptions\Documents\FaceNotVisibleException;
use Idez\Caradhras\Exceptions\Documents\InconsistentSelfieException;
use Idez\Caradhras\Exceptions\Documents\InvalidDocumentException;
use Idez\Caradhras\Exceptions\Documents\InvalidSelfieException;
use Idez\Caradhras\Exceptions\Documents\LowQualitySelfieException;
use Idez\Caradhras\Exceptions\Documents\SendDocumentException;
use Idez\Caradhras\Exceptions\FailedCreatePersonalAccount;
use Idez\Caradhras\Exceptions\FailedRequestCardBatchException;
use Idez\Caradhras\Exceptions\FindCardsException;
use Idez\Caradhras\Exceptions\FraudDetectorException;
use Idez\Caradhras\Exceptions\GetCardDetailsException;
use Idez\Caradhras\Exceptions\InsufficientBalanceException;
use Idez\Caradhras\Exceptions\IssuePhysicalCardException;
use Idez\Caradhras\Exceptions\PhoneRechargeConfirmationFailedException;
use Idez\Caradhras\Exceptions\PhoneRechargeOrderFailedException;
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
     * @throws Idez\Caradhras\Exceptions\CaradhrasException
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
     * @throws Idez\Caradhras\Exceptions\GetCardDetailsException
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
     * @throws Idez\Caradhras\Exceptions\CaradhrasException
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
     * Create card limit.
     *
     * @param  int  $cardId
     * @param  float  $amount
     * @return CardLimit
     * @throws Exception
     */
    public function createCardLimit(int $cardId, float $amount): CardLimit
    {
        $createLimitRequest = $this
            ->apiClient()
            ->post("/cartoes/{$cardId}/controles-limites", [
                'limiteDiario' => $amount,
            ]);

        return new CardLimit($createLimitRequest->json());
    }

    /**
     * Get cards.
     *
     * @param  array  $query
     * @return CardCollection
     * @throws FindCardsException
     */
    public function listCards(array $query = []): CardCollection
    {
        $response = $this->apiClient(throwsHttpError: false)->get("/cartoes", $query);

        if ($response->failed()) {
            if ($response->status() === 404) {
                return new CardCollection(['totalElements' => 0, 'content' => []]);
            }

            throw new FindCardsException();
        }

        return new CardCollection($response->json());
    }

    /**
     * Validate cvv.
     *
     * @param  int  $cardId
     * @param  string  $cvv
     * @throws Idez\Caradhras\Exceptions\CaradhrasException
     * @throws CVVMismatchException
     * @throws Exception
     */
    public function validateCVV(int $cardId, string $cvv): bool
    {
        $response = $this
            ->apiClient(false)
            ->post("/cartoes/{$cardId}/validar-cvv", [
                'cvv' => $cvv,
            ]);

        if ($response->failed()) {
            if ($response->status() === 400 && strpos($response->object()->message, 'criptografia do HSM')) {
                throw new CVVMismatchException();
            }

            throw new CaradhrasException('Failed to validate card CVV.', 502);
        }

        return true;
    }

    /**
     * Cancel card.
     *
     * @param  string  $cardId
     * @param  string  $description
     * @return object
     * @throws Idez\Caradhras\Exceptions\CaradhrasException
     * @throws \Illuminate\Http\Client\RequestException
     */
    public function cancelCard(string $cardId, string $description = ''): object
    {
        if (blank($cardId)) {
            throw new CaradhrasException('Invalid card.');
        }

        return $this
            ->apiClient()
            ->post("/cartoes/{$cardId}/cancelar?id_status=3&observacao={$description}")
            ->throw()
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
    ): object
    {
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
    ): object
    {
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
     * Create phone recharge.
     *
     * @param  string  $dealerCode
     * @param  string  $areaCode
     * @param  string  $phoneNumber
     * @return object
     * @throws \Illuminate\Http\Client\RequestException
     */
    public function createPhoneRecharge(string $dealerCode, string $areaCode, string $phoneNumber): object
    {
        return $this->apiClient()->post('/recharges', [
            'dealerCode' => $dealerCode,
            'ddd' => $areaCode,
            'phoneNumber' => $phoneNumber,
        ])->throw()->object();
    }

    public function orderPhoneRecharge(string $orderId, string $areaCode, string $phoneNumber, string $dealerCode, float $amount)
    {
        $response = $this->apiClient(false)->post("/recharges/{$orderId}", [
            'dealerCode' => $dealerCode,
            'ddd' => $areaCode,
            'phoneNumber' => $phoneNumber,
            'amount' => $amount * 100,
        ]);

        if ($response->failed()) {
            throw new PhoneRechargeOrderFailedException((array) $response->body());
        }

        return $response->object();
    }

    public function confirmPhoneRecharge(string $orderId, int $accountId, float $amount)
    {
        $response = $this->apiClient(false)->post("/recharges/{$orderId}/confirm", [
            'accountId' => $accountId,
            'amount' => $amount * 100,
        ]);

        if ($response->failed()) {
            throw new PhoneRechargeConfirmationFailedException((array) $response->body());
        }

        return $response->object();
    }

    /**
     * Create request of Noname Cards batch
     *
     * @param  int  $businessSourceId
     * @param  int  $productId
     * @param  int  $plasticId
     * @param  int  $cardImageId
     * @param  int  $addressId
     * @param  int  $cardQuantity
     * @return object
     * @throws \Idez\Caradhras\Exceptions\FailedRequestCardBatchException;
     */
    public function createNonameCardsBatch(
        int $businessSourceId,
        int $productId,
        int $plasticId,
        int $cardImageId,
        int $addressId,
        int $cardQuantity
    ): object {
        $query = http_build_query([
            'idOrigemComercial' => $businessSourceId,
            'idProduto' => $productId,
            'idTipoCartao' => $plasticId,
            'idImagem' => $cardImageId,
            'idEndereco' => $addressId,
            'quantidadeCartoes' => $cardQuantity,
        ]);

        $request = $this->apiClient(false)
            ->post('/cartoes/lotes-cartoes-pre-pagos?' . $query);

        if ($request->failed()) {
            throw new FailedRequestCardBatchException();
        }

        return $request->object();
    }

    public function getCard(int $cardId): Card
    {
        $request = $this->apiClient()->get("/cartoes/{$cardId}")
            ->object();

        return new Card($request);
    }

    /**
     * Lock card.
     *
     * @param  int  $cardId
     * @param  string  $description
     * @return object
     * @throws Idez\Caradhras\Exceptions\CaradhrasException
     */
    public function lockCard(int $cardId, string $description): object
    {
        $request = $this->apiClient(false)
            ->post("/cartoes/{$cardId}/bloquear?id_status=2&observacao={$description}");

        if ($request->failed()) {
            throw match ($request->status()) {
                400, 403, 404 => new CaradhrasException(trans('errors.services.caradhras.card.lock_failed'), $request->status()),
                default => new CaradhrasException(trans('errors.services.caradhras.generic_error'), 502)
            };
        }

        return $request->object();
    }

    /**
     * Unlock card.
     *
     * @param  int  $cardId
     * @return Card
     * @throws Idez\Caradhras\Exceptions\CaradhrasException
     */
    public function unlockCard(int $cardId): Card
    {
        $card = $this->getCard($cardId);

        return match (CardStatus::tryFrom($card->idStatus)) {
            CardStatus::BlockedPassword => $this->unlockSystemBlockedCard($cardId),
            CardStatus::BlockedTemporary => $this->unlockUserBlockedCard($cardId),
            default => $card
        };
    }

    /**
     * @param $cardId
     * @return Card
     * @throws CaradhrasException
     */
    public function unlockUserBlockedCard($cardId): Card
    {
        $request = $this->apiClient(false)
            ->post("/cartoes/{$cardId}/desbloquear");

        if ($request->failed()) {
            throw new CaradhrasException(match ($request->status()) {
                400 => trans('errors.card.unlock.failed'),
                403 => trans('caradhras.card_not_printed'),
                default => trans('errors.generic'),
            }, 502);
        }

        return new Card($request);
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

    public function createPerson(IndividualRegistration $personRegistration)
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
     * @return Idez\Caradhras\Data\Registrations\IndividualRegistration
     *
     * @throws Idez\Caradhras\Exceptions\DuplicatedImageException
     * @throws Idez\Caradhras\Exceptions\FaceNotVisibleException
     * @throws Idez\Caradhras\Exceptions\InconsistentSelfieException
     * @throws Idez\Caradhras\Exceptions\InvalidDocumentException
     * @throws Idez\Caradhras\Exceptions\InvalidSelfieException
     * @throws Idez\Caradhras\Exceptions\LowQualitySelfieException
     * @throws Idez\Caradhras\Exceptions\SendDocumentException
     */
    public function addPersonDocument(string $registrationId, string $documentType, Stream $file, string $contentType = 'image/jpeg'): IndividualRegistration
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

        return new IndividualRegistration($response->object());
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

    public function createCardSettings(int $cardId, CardSettings $cardSettings)
    {
        $request = $this->apiClient()
            ->post("/cartoes/{$cardId}/controles-configuracoes", $cardSettings->toArray())
            ->throw();

        return new CardSettings($request->json());
    }

    public function updateCardSettings(int $cardId, int $settingsId, CardSettings $cardSettings)
    {
        return $this->apiClient()
            ->patch("/cartoes/{$cardId}/controles-configuracoes/{$settingsId}", $cardSettings->toArray())
            ->throw()
            ->object();
    }

    public function getCardSettings(int $cardId, int $settingsId)
    {
        return $this->apiClient()
            ->get("/cartoes/{$cardId}/controles-configuracoes/{$settingsId}")
            ->object();
    }

    /**
     * Attach MCC groups to a card.
     *
     * @param  int  $cardId
     * @param  array<int>  $idsGruposMCC
     * @return CardMccGroupControl[]
     * @throws \Illuminate\Http\Client\RequestException
     */
    public function attachMccGroupsToCard(int $cardId, array $idsGruposMCC): array
    {
        $response = $this->apiClient()
            ->post("/cartoes/{$cardId}/controles-grupomcc", [
                'idsGruposMCC' => $idsGruposMCC,
            ])
            ->throw();

        return array_map(
            fn ($cardMccGroupControl) => new CardMccGroupControl($cardMccGroupControl),
            $response->object()
        );
    }

    /**
     * Delete a MCC groups from card.
     *
     * @param  int  $cardId
     * @param  int  $mccGroupControlId
     * @return bool
     * @throws \Illuminate\Http\Client\RequestException
     */
    public function deleteCardMccGroup(int $cardId, int $mccGroupControlId): bool
    {
        $response = $this->apiClient()
            ->delete("/cartoes/{$cardId}/controles-grupomcc/{$mccGroupControlId}")
            ->throw();

        return $response->successful();
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
