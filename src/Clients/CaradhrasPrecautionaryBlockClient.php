<?php

namespace Idez\Caradhras\Clients;

use Idez\Caradhras\Exceptions\CaradhrasException;
use Illuminate\Support\Str;

class CaradhrasPrecautionaryBlockClient extends BaseApiClient
{
    public const API_PREFIX = 'precautionary-block';

    /**
     * @throws CaradhrasException
     */
    public function unlockTransaction(string $externalId): true
    {
        $response = $this
            ->apiClient(false)
            ->delete("/v1/locks/$externalId");

        if ($response->failed()) {
            $errorMessage = $response->json('error.description');

            if ($response->status() === 404) {
                throw new CaradhrasException('Transaction not found', 404);
            }

            if ($response->status() === 422 && Str::startsWith($errorMessage, 'externalId already used')) {
                throw new CaradhrasException('Transaction already unlocked', 422);
            }

            throw new CaradhrasException($errorMessage, $response->status(), data: $response->json());
        }


        return true;
    }

    public function listLockedTransactions(int $page, $limit = 50): object
    {
        return $this->apiClient()->get('/v1/locks', [
            'page' => $page,
            'limit' => $limit,
        ])->object();
    }

}