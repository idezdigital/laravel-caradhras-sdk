<?php

namespace Idez\Caradhras\Clients;

use Idez\Caradhras\Data\BankTransfer;
use Idez\Caradhras\Enums\AliasBankProvider;
use Idez\Caradhras\Exceptions\CaradhrasException;
use Illuminate\Http\Client\RequestException;

class CaradhrasTransfersClient extends BaseApiClient
{
    public const API_PREFIX = 'transfers';

    /**
     * @throws CaradhrasException
     */
    public function getTransfer(string $transactionId): BankTransfer
    {
        $response = $this->apiClient(false)
            ->get("/v1/cashout/receipt/{$transactionId}");

        if ($response->failed()) {
            throw new CaradhrasException(trans('caradhras.bank_transfer.not_found'), 404);
        }

        return new BankTransfer($response->object());
    }

    /**
     * @throws RequestException
     * @throws CaradhrasException
     */
    public function listTransfers(?array $filters = []): object
    {
        $response = $this->apiClient(false)
            ->get('/v1/cashout/transactions', $filters)
            ->throw()
            ->object();

        if (blank($response->items)) {
            throw new CaradhrasException(trans('caradhras.bank_transfer.empty_list'), 400);
        }

        return $response;
    }

    public function transfer(
        int $accountId,
        array $beneficiary,
        float $amount,
        AliasBankProvider $originBankNumber = AliasBankProvider::Dock,
        int $transferType = 3,
        ?string $description = null,
    ): object {
        $response = $this->apiClient(false)
            ->post('/v1/cashout', [
                'accountId' => $accountId,
                'sourceBankNumber' => $originBankNumber,
                'beneficiary' => $beneficiary,
                'amount' => $amount,
                'description' => $description,
                'transferType' => $transferType,
            ]);

        if ($response->failed()) {
            throw new CaradhrasException(match ($response->status()) {
                400, 404, 409 => trans('caradhras.bank_transfer.action_not_allowed'),
                default => trans('caradhras.bank_transfer.default'),
            }, 502);
        }

        return $response->object();
    }
}
