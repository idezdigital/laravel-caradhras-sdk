<?php

namespace Idez\Caradhras\Clients;

use Idez\Caradhras\Enums\AliasBankProvider;
use Idez\Caradhras\Exceptions\CaradhrasException;
use Illuminate\Http\Client\Response;

class CaradhrasAliasClient extends BaseApiClient
{
    public const API_PREFIX = 'aliasbank';

    /**
     * Create an alias.
     *
     * @param  int  $accountId
     * @param  \Idez\Caradhras\Enums\AliasBankProvider  $bankProvider
     * @return Response
     */
    public function create(int $accountId, AliasBankProvider $bankProvider = AliasBankProvider::Votorantim): Response
    {
        return $this
            ->apiClient()
            ->post('/v1/accounts', [
                'idAccount' => $accountId,
                'bankNumber' => $bankProvider->value,
            ]);
    }

    /**
     * Find or create an alias.
     *
     * @param  int  $accountId
     * @param  \Idez\Caradhras\Enums\AliasBankProvider  $bankProvider
     * @return object
     * @throws \Idez\Caradhras\Exceptions\CaradhrasException
     */
    public function findOrCreate(int $accountId, AliasBankProvider $bankProvider = AliasBankProvider::Votorantim): object
    {
        $response = $this
            ->apiClient(false)
            ->post('/v1/accounts', [
                'idAccount' => $accountId,
                'bankNumber' => $bankProvider->value,
            ]);

        if (! in_array($response->status(), [201, 409])) {
            throw match ($response->status()) {
                400 => new CaradhrasException('Conta inválida.'),
                404 => new CaradhrasException('Conta não encontrada.'),
                default => new CaradhrasException('Tente novamente mais tarde.'),
            };
        }

        return $response->object()->data;
    }
}
