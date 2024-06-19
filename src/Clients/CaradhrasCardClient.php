<?php

namespace Idez\Caradhras\Clients;

use Idez\Caradhras\Data\Card;
use Idez\Caradhras\Data\CardCollection;
use Idez\Caradhras\Data\CardLimit;
use Idez\Caradhras\Data\CardMccGroupControl;
use Idez\Caradhras\Data\CardSettings;
use Idez\Caradhras\Data\Individual;
use Idez\Caradhras\Enums\Cards\CardStatus;
use Idez\Caradhras\Exceptions\CaradhrasException;
use Idez\Caradhras\Exceptions\CVVMismatchException;
use Idez\Caradhras\Exceptions\FailedRequestCardBatchException;
use Idez\Caradhras\Exceptions\FindCardsException;
use Idez\Caradhras\Exceptions\GetCardDetailsException;
use Idez\Caradhras\Exceptions\IssuePhysicalCardException;
use Illuminate\Support\Str;

class CaradhrasCardClient extends BaseApiClient
{
    /**
     * Set card password.
     *
     * @param  string  $password
     *
     * @throws \Illuminate\Http\Client\RequestException
     */
    public function setCardPassword(string $cardId, $password): object
    {
        return $this->apiClient()
            ->withHeaders(['senha' => str_pad($password, 4, '0', STR_PAD_LEFT)])
            ->post("/cartoes/{$cardId}/cadastrar-senha")
            ->throw()
            ->object();
    }

    /**
     * Associate card to account.
     *
     * @throws RequestException
     */
    public function associatePrepaidCardToAccount(int $cardId, int $accountId, int $individualId): bool
    {
        $this->apiClient()
            ->post("/contas/{$accountId}/atribuir-cartao-prepago", [
                'idCartao' => $cardId,
                'idPessoaFisica' => $individualId,
            ])
            ->throw();

        return true;
    }

    public function unlockSystemBlockedCard(int $cardId): Card
    {
        $unlockRequest = $this->apiClient()->post("/cartoes/{$cardId}/desbloquear-senha-incorreta");

        return new Card($unlockRequest);
    }

    /**
     * Update card password.
     *
     * @param  string  $password
     * @return array|object
     */
    public function updateCardPassword(string $cardId, $password)
    {
        return $this->apiClient()
            ->withHeaders(['senha' => str_pad($password, 4, '0', STR_PAD_LEFT)])
            ->put("/cartoes/{$cardId}/alterar-senha")
            ->object();
    }

    /**
     * @throws IssuePhysicalCardException
     */
    public function issuePhysicalCard(int $accountId, int $individualId): Card
    {
        $data = [
            'id_pessoa' => $individualId,
            'id_tipo_plastico' => config('caradhras.app.plastic_id'),
        ];

        $issueCardResponse = $this->apiClient(false)
            ->post("/contas/{$accountId}/gerar-cartao-grafica", $data);

        if ($issueCardResponse->failed()) {
            throw new IssuePhysicalCardException();
        }

        $cardId = $issueCardResponse->json('idCartao');

        return $this->getCard($cardId);
    }

    /**
     * Create virtual card.
     */
    public function issueVirtualCard(int $accountId, int $individualId): Card
    {
        $data = http_build_query([
            'dataValidade' => now()->addYears(5)->toIso8601String(),
            'idPessoaFisica' => $individualId,
        ]);

        $card = $this->apiClient()
            ->post("/contas/{$accountId}/gerar-cartao-virtual?".$data)
            ->object();

        return $this->getCard($card->idCartao);
    }

    public function getAddressByIndividualId(int $individualId, int $tipoEndereco = 1)
    {
        return $this->apiClient()
            ->get('/enderecos', ['idPessoa' => $individualId, 'idTipoEndereco' => $tipoEndereco])
            ->throw()
            ->object();
    }

    /**
     * Get Card details.
     *
     * @throws GetCardDetailsException
     */
    public function getCardDetails(int $cardId, int $individualId): string
    {
        $response = $this
            ->apiClient(false)
            ->get("/cards/{$cardId}/cardholders/{$individualId}/data/real");

        if ($response->failed()) {
            throw new GetCardDetailsException();
        }

        return $response;
    }

    /**
     * Get card limit.
     *
     * @throws Idez\Caradhras\Exceptions\CaradhrasException
     */
    public function getCardLimit(int $cardId, int $limitId): CardLimit
    {
        if (blank($cardId)) {
            throw new CaradhrasException(trans('errors.card.not_issued'), 500);
        }

        if (blank($limitId)) {
            throw new CaradhrasException(trans('errors.card.undefined_limit'), 500);
        }

        $response = $this
            ->apiClient()
            ->get("/cartoes/{$cardId}/controles-limites/{$limitId}");

        return new CardLimit($response->json());
    }

    public function updateCardLimit(int $cardId, int $limitId, ?float $amount = null): CardLimit
    {
        $response = $this
            ->apiClient()
            ->patch("/cartoes/{$cardId}/controles-limites/{$limitId}", [
                'limiteDiario' => $amount,
                'limiteSemanal' => $amount,
                'limiteMensal' => $amount,
            ]);

        return new CardLimit($response->json());
    }

    /**
     * Create card limit.
     *
     * @throws Exception
     */
    public function createCardLimit(int $cardId, float $amount): CardLimit
    {
        $createLimitRequest = $this
            ->apiClient()
            ->post("/cartoes/{$cardId}/controles-limites", [
                'limiteDiario' => $amount,
            ]);

        return new CardLimit($createLimitRequest->json());
    }

    /**
     * Get cards.
     *
     * @throws FindCardsException
     */
    public function listCards(array $query = []): CardCollection
    {
        $response = $this->apiClient(throwsHttpError: false)->get('/cartoes', $query);

        if ($response->failed()) {
            if ($response->status() === 404) {
                return new CardCollection(['totalElements' => 0, 'content' => []]);
            }

            throw new FindCardsException();
        }

        return new CardCollection($response->json());
    }

    /**
     * Validate cvv.
     *
     * @throws Idez\Caradhras\Exceptions\CaradhrasException
     * @throws CVVMismatchException
     * @throws Exception
     */
    public function validateCVV(int $cardId, string $cvv): bool
    {
        $response = $this
            ->apiClient(false)
            ->post("/cartoes/{$cardId}/validar-cvv", [
                'cvv' => $cvv,
            ]);

        if ($response->failed()) {
            if ($response->status() === 400 && str_contains($response->object()->message, 'criptografia do HSM')) {
                throw new CVVMismatchException();
            }

            throw new CaradhrasException('Failed to validate card CVV.', 502);
        }

        return true;
    }

    /**
     * Cancel card.
     *
     * @throws CaradhrasException
     * @throws \Idez\Caradhras\Exceptions\Cards\CardAlreadyCanceledException
     */
    public function cancelCard(string $cardId, string $description = ''): object
    {
        if (blank($cardId)) {
            throw new CaradhrasException('Invalid card.');
        }

        $response = $this
            ->apiClient(false)
            ->post("/cartoes/{$cardId}/cancelar?id_status=3&observacao={$description}");

        if ($response->failed()) {
            if ($response->status() === 400 && Str::of($response->json('message'))->contains('Cartão já encontra-se cancelado')) {
                throw new \Idez\Caradhras\Exceptions\Cards\CardAlreadyCanceledException();
            }

            throw new CaradhrasException($response->json('message'), $response->status());
        }

        return $response->object();
    }

    /**
     * Create an individual from data.
     */
    public function createIndividual(array $data): Individual
    {
        $response = $this->apiClient()->asForm()->post('/pessoas', $data);

        return new Individual($response->object());
    }

    /**
     * Create request of Noname Cards batch
     *
     * @throws \Idez\Caradhras\Exceptions\FailedRequestCardBatchException;
     */
    public function createNonameCardsBatch(
        int $businessSourceId,
        int $productId,
        int $plasticId,
        int $cardImageId,
        int $addressId,
        int $cardQuantity
    ): object {
        $query = http_build_query([
            'idOrigemComercial' => $businessSourceId,
            'idProduto' => $productId,
            'idTipoCartao' => $plasticId,
            'idImagem' => $cardImageId,
            'idEndereco' => $addressId,
            'quantidadeCartoes' => $cardQuantity,
        ]);

        $request = $this->apiClient(false)
            ->post('/cartoes/lotes-cartoes-pre-pagos?'.$query);

        if ($request->failed()) {
            throw new FailedRequestCardBatchException();
        }

        return $request->object();
    }

    public function getCard(int $cardId): Card
    {
        $request = $this->apiClient()->get("/cartoes/{$cardId}")
            ->object();

        return new Card($request);
    }

    /**
     * Lock card.
     *
     * @throws Idez\Caradhras\Exceptions\CaradhrasException
     */
    public function lockCard(int $cardId, string $description): object
    {
        $request = $this->apiClient(false)
            ->post("/cartoes/{$cardId}/bloquear?id_status=2&observacao={$description}");

        if ($request->failed()) {
            throw match ($request->status()) {
                400, 403, 404 => new CaradhrasException(trans('errors.services.caradhras.card.lock_failed'), $request->status()),
                default => new CaradhrasException(trans('errors.services.caradhras.generic_error'), 502)
            };
        }

        return $request->object();
    }

    /**
     * Unlock card.
     *
     * @throws Idez\Caradhras\Exceptions\CaradhrasException
     */
    public function unlockCard(int $cardId): Card
    {
        $card = $this->getCard($cardId);

        return match (CardStatus::tryFrom($card->idStatus)) {
            CardStatus::BlockedPassword => $this->unlockSystemBlockedCard($cardId),
            CardStatus::BlockedTemporary => $this->unlockUserBlockedCard($cardId),
            default => $card
        };
    }

    /**
     * @throws CaradhrasException
     */
    public function unlockUserBlockedCard($cardId): Card
    {
        $request = $this->apiClient(false)
            ->post("/cartoes/{$cardId}/desbloquear");

        if ($request->failed()) {
            throw new CaradhrasException(match ($request->status()) {
                400 => trans('errors.card.unlock_failed'),
                403 => trans('errors.card.not_printed'),
                default => trans('errors.generic'),
            }, 502);
        }

        return new Card($request);
    }

    public function createCardSettings(int $cardId, CardSettings $cardSettings)
    {
        $request = $this->apiClient()
            ->post("/cartoes/{$cardId}/controles-configuracoes", $cardSettings->toArray())
            ->throw();

        return new CardSettings($request->json());
    }

    public function updateCardSettings(int $cardId, int $settingsId, CardSettings $cardSettings)
    {
        return $this->apiClient()
            ->patch("/cartoes/{$cardId}/controles-configuracoes/{$settingsId}", $cardSettings->toArray())
            ->throw()
            ->object();
    }

    public function getCardSettings(int $cardId, int $settingsId)
    {
        return $this->apiClient()
            ->get("/cartoes/{$cardId}/controles-configuracoes/{$settingsId}")
            ->object();
    }

    /**
     * Attach MCC groups to a card.
     *
     * @param  array<int>  $idsGruposMCC
     * @return CardMccGroupControl[]
     *
     * @throws \Illuminate\Http\Client\RequestException
     */
    public function attachMccGroupsToCard(int $cardId, array $idsGruposMCC): array
    {
        $response = $this->apiClient()
            ->post("/cartoes/{$cardId}/controles-grupomcc", [
                'idsGruposMCC' => $idsGruposMCC,
            ])
            ->throw();

        return array_map(
            fn ($cardMccGroupControl) => new CardMccGroupControl($cardMccGroupControl),
            $response->json()
        );
    }

    /**
     * Delete a MCC groups from card.
     *
     * @throws \Illuminate\Http\Client\RequestException
     */
    public function deleteCardMccGroup(int $cardId, int $mccGroupControlId): bool
    {
        $response = $this->apiClient()
            ->delete("/cartoes/{$cardId}/controles-grupomcc/{$mccGroupControlId}")
            ->throw();

        return $response->successful();
    }

    public function updateHolderName(int $accountId, int $personId, string $name)
    {
        $response = $this->apiClient()
            ->patch("/contas/{$accountId}/pessoas/{$personId}/portadores", [
                'nomeImpresso' => $name,
            ])
            ->throw();

        return $response->object();
    }
}
