<?php

namespace Idez\Caradhras\Tests\Unit\Clients;

use Idez\Caradhras\Clients\CaradhrasAliasClient;
use Idez\Caradhras\Enums\AliasBankProvider;
use Idez\Caradhras\Exceptions\CaradhrasException;
use Idez\Caradhras\Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\Client\Request;
use Illuminate\Support\Facades\Http;

class CaradhrasAliasClientTest extends TestCase
{
    use WithFaker;

    private CaradhrasAliasClient $aliasClient;

    protected function setUp(): void
    {
        parent::setUp();
        $this->aliasClient = app(CaradhrasAliasClient::class);
    }

    public function testIfThrowsCorrectExceptionWhenAccountHasPendingDocuments(): void
    {
        $accountId = $this->faker->randomNumber(5);

        $expectedRequestUrl = $this->aliasClient->getApiBaseUrl() . "/v1/accounts*";

        $fakeResponse = [
            "message" => "Transaction not allowed due to lack of regulatory informations or documents.",
        ];

        Http::fake([
            $expectedRequestUrl => Http::sequence()
                ->push(['items' => []])
                ->push($fakeResponse, 409),
        ]);

        $this->expectException(CaradhrasException::class);
        $this->expectExceptionMessage("A conta possui informações ou documentos pendentes.");

        $this->aliasClient->findOrCreate($accountId, AliasBankProvider::Votorantim);
    }

    public function testIfCanListWithSuccess(): void
    {
        $accountId = $this->faker->randomNumber(5);

        $expectedRequestUrl = $this->aliasClient->getApiBaseUrl() . "/v1/accounts?idAccount={$accountId}";

        $expectedItems = [
            [
                'idAccount' => $accountId,
                'bankNumber' => '301',
                'bankAccountStatus' => 'OPEN',
            ],
            [
                'idAccount' => $accountId,
                'bankNumber' => '208',
                'bankAccountStatus' => 'CLOSED',
            ],
        ];

        $fakeResponse = [
            'items' => $expectedItems,
        ];

        Http::fake([
            $expectedRequestUrl => Http::response(body: $fakeResponse, status: 200),
        ]);

        $result = $this->aliasClient->list($accountId);

        $this->assertEquals($result, json_decode(json_encode($expectedItems)));

        Http::assertSent(
            function (Request $request) use ($expectedRequestUrl, $accountId) {
                return $request->url() === $expectedRequestUrl &&
                    $request['idAccount'] === $accountId;
            }
        );
    }

    public function testIfCanCancelAccountWithSuccess(): void
    {
        $accountId = $this->faker->randomNumber(5);
        $bankNumber = AliasBankProvider::Votorantim;

        $expectedRequestUrl = $this->aliasClient->getApiBaseUrl() . '/v1/accounts';

        Http::fake([
            $expectedRequestUrl => Http::response(status: 200),
        ]);

        $this->aliasClient->delete($accountId, $bankNumber);

        Http::assertSent(
            function (Request $request) use ($expectedRequestUrl, $accountId, $bankNumber) {
                return $request->url() === $expectedRequestUrl &&
                    $request['idAccount'] === $accountId &&
                    $request['bankNumber'] === $bankNumber->value;
            }
        );
    }
}
