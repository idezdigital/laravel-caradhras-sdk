<?php

namespace Idez\Caradhras\Clients;

class CaradhrasRemunerationsClient extends BaseApiClient
{
    public const API_PREFIX = 'remunerations';

    public function listAccountRemunerations($crAccountId, array $filters = []): \Illuminate\Http\Client\Response
    {
        return $this->apiClient()->get(
            url: '/v1/accounts/'.$crAccountId.'/remunerations',
            query: $filters
        );
    }
}
