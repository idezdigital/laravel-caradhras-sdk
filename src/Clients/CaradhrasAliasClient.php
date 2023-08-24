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
        $alias = $this->find($accountId, $bankProvider);

        if (filled($alias)) {
            return head($alias);
        }

        $response = $this
            ->apiClient(false)
            ->post('/v1/accounts', [
                'idAccount' => $accountId,
                'bankNumber' => $bankProvider->value,
            ]);

        if (! in_array($response->status(), [200, 201, 202, 409])) {
            throw match ($response->status()) {
                400 => new CaradhrasException('Conta inválida.', $response->status()),
                404 => new CaradhrasException('Conta não encontrada.', $response->status()),
                default => new CaradhrasException('Tente novamente mais tarde.', $response->status()),
            };
        }

        $responseObject = $response->object();

        if (
            $response->status() === 409 &&
            $responseObject?->message === "Transaction not allowed due to lack of regulatory informations or documents."
        ) {
            throw new CaradhrasException("A conta possui informações ou documentos pendentes.", $response->status());
        }

        return $responseObject->data;
    }

    public function find(int $accountId, AliasBankProvider $bankProvider = AliasBankProvider::Votorantim): array
    {
        $response = $this
            ->apiClient(false)
            ->get('/v1/accounts', [
                'idAccount' => $accountId,
                'bankNumber' => $bankProvider->value,
            ])->object();

        return $response->items;
    }

    /**
     * Delete an alias.
     *
     * @param  int  $accountId
     * @param  \Idez\Caradhras\Enums\AliasBankProvider $bankProvider
     * @return Response
     */
    public function delete(int $accountId, AliasBankProvider $bankProvider): Response
    {
        return $this
            ->apiClient(false)
            ->delete('/v1/accounts', [
                'idAccount' => $accountId,
                'bankNumber' => $bankProvider->value,
            ]);
    }
}
