<?php

namespace Idez\Caradhras\Clients;

class CaradhrasMainClient extends BaseApiClient
{
    public const API_PREFIX = 'api';

    /**
     * @param  int  $accountId
     * @param  int  $statusId
     * @return object
     */
    public function cancelAccount(int $accountId, int $statusId = 2): object
    {
        return $this
            ->apiClient()
            ->post("/contas/{$accountId}/cancelar?id_status={$statusId}")
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
}
