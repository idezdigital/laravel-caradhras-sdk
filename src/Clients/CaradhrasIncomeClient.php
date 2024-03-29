<?php

namespace Idez\Caradhras\Clients;

use Illuminate\Http\Client\Response;

class CaradhrasIncomeClient extends BaseApiClient
{
    public const API_PREFIX = 'income';

    public function createParametrizationForAccount(
        int $accountId,
        int $profitablePercentage,
        int $splitPercentage
    ): Response {
        return $this
            ->apiClient()
            ->post(
                url: '/v1/setup/accounts',
                data: [
                    'accountId' => $accountId,
                    'profitablePercentage' => $profitablePercentage,
                    'splitPercentage' => $splitPercentage,
                ]
            );
    }
}
