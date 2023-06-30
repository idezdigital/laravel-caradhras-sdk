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

    public function getAccountLimits(string $accountId)
    {
        return $this->apiClient()->get("/limits/v2/accounts/{$accountId}")
            ->object();
    }

    public function getAccountLimitRequests(string $accountId)
    {
        return $this
            ->apiClient()
            ->get('/limits/v2/requests', ['idAccount' => $accountId])
            ->object();
    }

    public function listAccountTimetable(string $accountId)
    {
        return $this->apiClient()->get("/limits/v2/accounts/{$accountId}/timetables")
            ->object();
    }

    public function updateAccountTimetable(string $accountId, int $hour, int $minute)
    {
        $formattedHour = str_pad($hour, 2, '0');
        $formattedMinute = str_pad($minute, 2, '0');

        return $this->apiClient()->put("/limits/v2/accounts/{$accountId}/timetables", [
            'nighttimeStart' => "{$formattedHour}:{$formattedMinute}",
        ]);
    }
}
