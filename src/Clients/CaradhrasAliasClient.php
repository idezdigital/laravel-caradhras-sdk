<?php

namespace Idez\Caradhras\Clients;

use Exception;
use Idez\Caradhras\Enums\AliasBankProvider;
use Idez\Caradhras\Exceptions\CaradhrasException;
use Illuminate\Http\Client\PendingRequest;
use Illuminate\Http\Client\Response;

class CaradhrasAliasClient extends BaseApiClient
{
    public const API_PREFIX = 'aliasbank';

    private const ERROR_LACK_REGULATORY = "Transaction not allowed due to lack of regulatory informations or documents.";

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
            ->retry(5, 2000, fn (Exception $exception, PendingRequest $request) => $exception->getCode() >= 500, false)
            ->post('/v1/accounts', [
                'idAccount' => $accountId,
                'bankNumber' => $bankProvider->value,
            ]);

        if ($response->failed()) {
            throw match ($response->status()) {
                400 => new CaradhrasException('Conta inválida.', $response->status()),
                404 => new CaradhrasException('Conta não encontrada.', $response->status()),
                409 => new CaradhrasException($response->json('message') === self::ERROR_LACK_REGULATORY
                    ? "A conta possui informações ou documentos pendentes."
                    : "Erro Desconhecido", $response->status()),
                default => new CaradhrasException('Tente novamente mais tarde.', $response->status()),
            };
        }

        return $response->object()?->data;
    }

    public function find(int $accountId, AliasBankProvider $bankProvider = AliasBankProvider::Votorantim): array
    {
        $response = $this
            ->apiClient(false)
            ->retry(3, 2000)
            ->get('/v1/accounts', [
                'idAccount' => $accountId,
                'bankNumber' => $bankProvider->value,
            ])->object();

        return $response->items;
    }
}
