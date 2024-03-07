<?php

namespace Idez\Caradhras\Clients;

use Idez\Caradhras\Data\AliasBankAccount;
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
     * @param AliasBankProvider $bankProvider
     * @return Response
     */
    public function create(int $accountId, AliasBankProvider $bankProvider = AliasBankProvider::Dock): Response
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
     * @param AliasBankProvider $bankProvider
     * @return AliasBankAccount
     * @throws CaradhrasException
     */
    public function findOrCreate(int $accountId, AliasBankProvider $bankProvider = AliasBankProvider::Dock): AliasBankAccount
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

        $responseStatus = $response->status();
        $responseData = $response->json();

        if (! in_array($responseStatus, [200, 201, 202, 409])) {
            throw match ($responseStatus) {
                400 => new CaradhrasException('Conta inválida.', $responseStatus),
                404 => new CaradhrasException('Conta não encontrada.', $responseStatus),
                default => new CaradhrasException('Tente novamente mais tarde.', $responseStatus),
            };
        }

        if (
            $responseStatus === 409 &&
            data_get($responseData, 'message') === 'Transaction not allowed due to lack of regulatory informations or documents.'
        ) {
            throw new CaradhrasException('A conta possui informações ou documentos pendentes.', $responseStatus);
        }

        return new AliasBankAccount($responseData['data']);
    }

    /**
     * Find an alias.
     *
     * @param  int  $accountId
     * @param AliasBankProvider $bankProvider
     * @return AliasBankAccount[]
     */
    public function find(int $accountId, AliasBankProvider $bankProvider = AliasBankProvider::Dock): array
    {
        $response = $this
            ->apiClient(false)
            ->get('/v1/accounts', [
                'idAccount' => $accountId,
                'bankNumber' => $bankProvider->value,
            ])->json();

        return array_map(fn ($item) => new AliasBankAccount($item), $response['items']);
    }

    /**
     * List account aliases.
     *
     * @param  int  $accountId
     * @return array
     */
    public function list(int $accountId): array
    {
        $response = $this
            ->apiClient()
            ->get('/v1/accounts', [
                'idAccount' => $accountId,
            ])->json();

        return array_map(fn ($item) => new AliasBankAccount($item), $response['items']);
    }

    /**
     * Delete an alias.
     *
     * @param  int  $accountId
     * @param AliasBankProvider $bankProvider
     * @return object
     */
    public function delete(int $accountId, AliasBankProvider $bankProvider): object
    {
        return $this
            ->apiClient()
            ->delete('/v1/accounts', [
                'idAccount' => $accountId,
                'bankNumber' => $bankProvider->value,
            ])
            ->object();
    }
}
