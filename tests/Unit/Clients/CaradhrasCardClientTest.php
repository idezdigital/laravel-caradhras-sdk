<?php

namespace Idez\Caradhras\Tests\Unit\Clients;

use Idez\Caradhras\Clients\CaradhrasCardClient;
use Idez\Caradhras\Data\CardMccGroupControl;
use Idez\Caradhras\Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Http;

class CaradhrasCardClientTest extends TestCase
{
    use WithFaker;

    private CaradhrasCardClient $cardClient;

    protected function setUp(): void
    {
        parent::setUp();
        $this->cardClient = app(CaradhrasCardClient::class);
    }

    public function testCanGetCardMccGroups(): void
    {
        $cardId = random_int(111111, 999999);
        $expectedRequestUrl = $this->cardClient->getApiBaseUrl()."/cartoes/controles-grupomcc?idAccount={$cardId}";

        $expectedResponse = $this->generateFakeResponse();

        Http::fake([
            $expectedRequestUrl => Http::response($expectedResponse),
        ]);

        $groups = $this->cardClient->getCardMccGroups($cardId);

        foreach ($groups as $group) {
            $this->assertInstanceOf(CardMccGroupControl::class, $group);
        }
    }

    public function generateFakeResponse(): array
    {
        return [
            'number' => 0,
            'size' => 50,
            'totalPages' => 1,
            'numberOfElements' => 2,
            'totalElements' => 2,
            'firstPage' => false,
            'hasPreviousPage' => false,
            'hasNextPage' => false,
            'hasContent' => true,
            'first' => true,
            'last' => true,
            'nextPage' => 0,
            'previousPage' => 0,
            'content' =>
            [
                [
                'id' => 92,
                'idCartao' => 311411,
                'grupoMCC' =>
                [
                    'id' => 19,
                    'descricao' => 'Food/Drink',
                    'descricaoExtrato' => 'Supermercados / Mercearia / Padarias / Lojas de Conveniência',
                    'duracao' => 7,
                    'percentualMin' => 10.0,
                    'percentualMax' => 10.0,
                    'intervaloMatch' => 5,
                ],
                ],
                [
                'id' => 93,
                'idCartao' => 311411,
                'grupoMCC' =>
                [
                    'id' => 14,
                    'descricao' => 'Eating/Drinking',
                    'descricaoExtrato' => 'Restaurante / Lanchonete / Bar',
                    'duracao' => 7,
                    'percentualMin' => 10.0,
                    'percentualMax' => 10.0,
                    'intervaloMatch' => 5,
                ],
                ],
            ],
            ];
    }
}
