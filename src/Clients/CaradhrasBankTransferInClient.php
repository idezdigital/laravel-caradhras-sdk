<?php

namespace Idez\Caradhras\Clients;

use Idez\Caradhras\Exceptions\BankTransferNotFoundException;

class CaradhrasBankTransferInClient extends BaseApiClient
{
    public const API_PREFIX = 'banktransfersin';

    /**
     * Get Bank Transfer In.
     *
     * @throws \Idez\Caradhras\Exceptions\BankTransferNotFoundException
     * @throws \Illuminate\Http\Client\RequestException
     */
    public function getBankTransferIn(string $transactionCode): object
    {
        $response = $this->apiClient()
            ->get('/v1/receipts', ['transactionCode' => $transactionCode])
            ->throw()
            ->object();

        $bankTransfers = $response->items ?? [];

        if (blank($bankTransfers)) {
            throw new BankTransferNotFoundException();
        }

        return head($bankTransfers);
    }

    /**
     * List bank transfer in.
     *
     * @throws \Illuminate\Http\Client\RequestException
     */
    public function listBankTransferIn(array $data): ?object
    {
        return $this->apiClient()
            ->get('/v1/receipts', $data)
            ->throw()
            ->object();
    }
}
