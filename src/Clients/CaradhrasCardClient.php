<?php

namespace Idez\Caradhras\Clients;

use Idez\Caradhras\Data\Card;
use Idez\Caradhras\Data\CardCollection;
use Idez\Caradhras\Data\CardDetails;
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

class CaradhrasCardClient extends BaseApiClient
{
    public const CARD_NOT_PRINTED = 'Invalid card status for activation.';

    /**
     * Associate card to account.
     *
     * @param int $cardId
     * @param int $accountId
     * @param int $individualId
     * @return bool
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

    /**
     * @param  int  $cardId
     * @return Card
     */
    public function unlockSystemBlockedCard(int $cardId): Card
    {
        $unlockRequest = $this->apiClient()->post("/cartoes/{$cardId}/desbloquear-senha-incorreta");

        return new Card($unlockRequest);
    }

    /**
     * Update card password.
     *
     * @param  string  $cardId
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
     * Issue physical card.
     *
     * @param  int  $accountId
     * @param  int  $individualId
     * @return Card
     * @throws \Illuminate\Http\Client\RequestException
     * @throws \Exception
     */
    public function issuePhysicalCard(int $accountId, int $individualId): Card
    {
        $data = [
            'id_pessoa' => $individualId,
            'id_tipo_plastico' => config('app.plastic_id'),
        ];

        $issueCardResponse = $this->apiClient(false)
            ->post("/contas/{$accountId}/gerar-cartao-grafica", $data);

        if ($issueCardResponse->failed()) {
            throw new IssuePhysicalCardException();
        }

        $cardId = $issueCardResponse->json('idCartao');

        $this->setCardPassword($cardId, random_int(1000, 9999));

        return $this->getCard($cardId);
    }

    /**
     * Create virtual card.
     *
     * @param  int  $accountId
     * @param  int  $individualId
     * @return Card
     */
    public function issueVirtualCard(int $accountId, int $individualId): Card
    {
        $data = http_build_query([
            'dataValidade' => now()->addYears(5)->format('Y-m-d\TH:i:s'),
            'idPessoaFisica' => $individualId,
        ]);

        $card = $this->apiClient()
            ->post("/contas/{$accountId}/gerar-cartao-virtual?" . $data)
            ->object();

        return $this->getCard($card->idCartao);
    }

    public function getAddressByIndividualId(int $individualId, int $tipoEndereco = 1)
    {
        return $this->apiClient()
            ->get("/enderecos", ['idPessoa' => $individualId, 'idTipoEndereco' => $tipoEndereco])
            ->throw()
            ->object();
    }

    /**
     * Get Card details.
     *
     * @param  int  $cardId
     * @return CardDetails
     * @throws Idez\Caradhras\Exceptions\GetCardDetailsException
     */
    public function getCardDetails(int $cardId): CardDetails
    {
        $response = $this
            ->apiClient(false)
            ->get("/cartoes/{$cardId}/consultar-dados-reais");

        if ($response->failed()) {
            throw new GetCardDetailsException($response->json());
        }

        return new CardDetails($response->json());
    }

    /**
     * Get card limit.
     * @param  int  $cardId
     * @param  int  $limitId
     * @return CardLimit
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

    /**
     *
     * @param  int  $cardId
     * @param  int  $limitId
     * @param  float|null  $amount
     * @return CardLimit
     */
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
     * @param  int  $cardId
     * @param  float  $amount
     * @return CardLimit
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
     * @param  array  $query
     * @return CardCollection
     * @throws FindCardsException
     */
    public function listCards(array $query = []): CardCollection
    {
        $response = $this->apiClient(throwsHttpError: false)->get("/cartoes", $query);

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
     * @param  int  $cardId
     * @param  string  $cvv
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
     * @param  string  $cardId
     * @param  string  $description
     * @return object
     * @throws Idez\Caradhras\Exceptions\CaradhrasException
     * @throws \Illuminate\Http\Client\RequestException
     */
    public function cancelCard(string $cardId, string $description = ''): object
    {
        if (blank($cardId)) {
            throw new CaradhrasException('Invalid card.');
        }

        return $this
            ->apiClient()
            ->post("/cartoes/{$cardId}/cancelar?id_status=3&observacao={$description}")
            ->throw()
            ->object();
    }

    /**
     * Create an individual from data.
     *
     * @param  array  $data
     * @return Individual
     */
    public function createIndividual(array $data): Individual
    {
        $response = $this->apiClient()->asForm()->post("/pessoas", $data);

        return new Individual($response->object());
    }

    /**
     * Create request of Noname Cards batch
     *
     * @param  int  $businessSourceId
     * @param  int  $productId
     * @param  int  $plasticId
     * @param  int  $cardImageId
     * @param  int  $addressId
     * @param  int  $cardQuantity
     * @return object
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
            ->post('/cartoes/lotes-cartoes-pre-pagos?' . $query);

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
     * @param  int  $cardId
     * @param  string  $description
     * @return object
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
     * @param  int  $cardId
     * @return Card
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
     * @param $cardId
     * @return Card
     * @throws CaradhrasException
     */
    public function unlockUserBlockedCard($cardId): Card
    {
        $request = $this->apiClient(false)
            ->post("/cartoes/{$cardId}/desbloquear");

        if ($request->failed()) {
            throw new CaradhrasException(match ($request->status()) {
                400 => trans('errors.card.unlock_failed'),
                403 => 'Status de cartão inválido para ativação.',
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
     * @param  int  $cardId
     * @param  array<int>  $idsGruposMCC
     * @return CardMccGroupControl[]
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
            $response->object()
        );
    }

    /**
     * Delete a MCC groups from card.
     *
     * @param  int  $cardId
     * @param  int  $mccGroupControlId
     * @return bool
     * @throws \Illuminate\Http\Client\RequestException
     */
    public function deleteCardMccGroup(int $cardId, int $mccGroupControlId): bool
    {
        $response = $this->apiClient()
            ->delete("/cartoes/{$cardId}/controles-grupomcc/{$mccGroupControlId}")
            ->throw();

        return $response->successful();
    }
}
