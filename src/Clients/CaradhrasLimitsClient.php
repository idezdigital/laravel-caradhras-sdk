<?php

namespace Idez\Caradhras\Clients;

use Idez\Caradhras\Data\LimitCollection;

class CaradhrasLimitsClient extends BaseApiClient
{
    public const API_PREFIX = 'limits';

    public function createLimit(string $accountId, string $beneficiaryType, int $serviceGroup, int $limitType, float $amount)
    {
        return $this->apiClient()->post("/limits/v2/accounts/{$accountId}/services-groups/{$serviceGroup}", [
            'limitType' => (string) $limitType,
            'requestLimit' => $amount,
            'beneficiaryType' => $beneficiaryType,
        ])->object();
    }

    public function createBatchLimit(string $accountId, string $beneficiaryType, int $serviceGroup, array $limits)
    {
        return $this->apiClient()->post("/limits/v2/accounts/{$accountId}/batches", [
            'idServicesGroup' => $serviceGroup,
            'limits' => $limits,
            'beneficiaryType' => $beneficiaryType,
        ])->object();
    }

    public function getLimitById(string $requestId, string $beneficiaryType)
    {
        return $this->apiClient()->get('/limits/v2/requests', [
            'idRequest' => $requestId, 'beneficiaryType' => $beneficiaryType,
        ])->object();
    }

    public function getAccountLimits(string $accountId, string $beneficiaryType)
    {
        return $this->apiClient()->get("/limits/v2/accounts/{$accountId}", ['beneficiaryType' => $beneficiaryType,])
            ->object();
    }

    public function getAccountLimitRequests(string $accountId, string $beneficiaryType): LimitCollection
    {
        $response = $this
            ->apiClient()
            ->get('/limits/v2/requests', ['idAccount' => $accountId, 'beneficiaryType' => $beneficiaryType,])
            ->toPsrResponse();

        return new LimitCollection($response);
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
