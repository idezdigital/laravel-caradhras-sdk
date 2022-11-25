<?php

namespace Idez\Caradhras\Clients;

use Idez\Caradhras\Data\P2PTransferPayload;
use Idez\Caradhras\Data\Registrations\IndividualRegistration;
use Idez\Caradhras\Enums\AccountStatus;
use Idez\Caradhras\Exceptions\CaradhrasException;

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
