<?php

namespace Idez\Caradhras\Clients;

class CaradhrasRemunerationsClient extends BaseApiClient
{
    public const API_PREFIX = 'remunerations';

    public function listAccountRemunerations($crAccountId, array $filters = []): array
    {
        return $this->apiClient()
            ->get(
                url: '/v1/accounts/'.$crAccountId.'/remunerations',
                query: $filters
            )->json();
    }
}
