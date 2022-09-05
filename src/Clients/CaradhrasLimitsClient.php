<?php

namespace Idez\Caradhras\Clients;

class CaradhrasLimitsClient extends BaseApiClient
{
    public const API_PREFIX = 'limits';

    public function createLimit(string $accountId, int $serviceGroup, int $limitType, float $amount)
    {
        return $this->apiClient()->post("/limits/v2/accounts/{$accountId}/services-groups/{$serviceGroup}", [
            'limitType' => (string) $limitType,
            'requestLimit' => $amount,
        ])->object();
    }

    public function createBatchLimit(string $accountId, int $serviceGroup, array $limits)
    {
        return $this->apiClient()->post("/limits/v2/accounts/{$accountId}/batches", [
            'idServicesGroup' => $serviceGroup,
            'limits' => $limits,
        ])->object();
    }

    public function getLimitById(string $requestId)
    {
        return $this->apiClient()->get('/limits/v2/requests', [
            'idRequest' => $requestId,
        ])->object();
    }
}
