<?php

namespace Idez\Caradhras\Clients;

use Idez\Caradhras\Data\PhoneRecharge;
use Idez\Caradhras\Exceptions\PhoneRechargeConfirmationFailedException;
use Idez\Caradhras\Exceptions\PhoneRechargeOrderFailedException;

class CaradhrasPhoneRechargeClientClient extends BaseApiClient
{
    public function getPhoneRecharge(int $adjustmentId): PhoneRecharge
    {
        $response = $this->apiClient()->get("/recharges/adjustment/{$adjustmentId}")
            ->throw()
            ->json();

        return new PhoneRecharge($response);
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
}
