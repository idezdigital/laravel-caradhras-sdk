<?php

namespace Idez\Caradhras\Clients;

use Idez\Caradhras\Data\P2PTransferPayload;
use Idez\Caradhras\Enums\AccountStatusCode;
use Idez\Caradhras\Exceptions\CaradhrasException;
use Idez\Caradhras\Exceptions\PhoneRechargeConfirmationFailedException;
use Illuminate\Http\Client\RequestException;

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
            ->get("/v2/individuals/$personId", ['statusSPD' => 'true']);
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
     *
     * @param  int  $accountId
     * @return object|null
     */
    public function updateAccountProduct(int $accountId, int $productId, int $businessSourceId): null|object
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
     *
     * @param int $accountId
     * @param int $idStatus
     * @return object
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
     * @param  int  $accountId
     * @return bool
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

}
