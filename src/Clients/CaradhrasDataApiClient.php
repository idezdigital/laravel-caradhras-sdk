<?php

namespace Idez\Caradhras\Clients;

use Carbon\CarbonInterface;

class CaradhrasDataApiClient extends BaseApiClient
{
    public const API_PREFIX = 'data';

    public function requestExtraction(string $service, CarbonInterface $from, CarbonInterface $to, string $accountId, string $compress = 'zip')
    {
        return $this->apiClient()->get("/v1/transactions", [
            'service' => $service,
            'from' => $from->format('Y-m-d'),
            'to' => $to->format('Y-m-d'),
            'accountId' => $accountId,
            'compress' => $compress,
        ])->object();
    }

    public function getTicket(string $ticketId)
    {
        return $this->apiClient()->get("/v1/transactions/{$ticketId}")->object();
    }

    public function listTickets(string $service = null, string $status = null, int $page = 0)
    {
        $query = array_filter([
            'service' => $service,
            'status' => $status,
            'page' => $page,
        ]);

        return $this->apiClient()->get('/v1/tickets', $query)->object();
    }

    public function listServices()
    {
        return $this->apiClient()->get('/v1/services')->object();
    }
}
