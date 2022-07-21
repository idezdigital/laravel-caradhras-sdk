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

        $expectedRequestUrl = $this->aliasClient->getApiBaseUrl() . "/v1/accounts";

        $fakeResponse = [
            "message" => "Transaction not allowed due to lack of regulatory informations or documents.",
        ];

        Http::fake([
            $expectedRequestUrl => Http::response($fakeResponse, 409),
        ]);

        $this->expectException(CaradhrasException::class);
        $this->expectExceptionMessage("A conta possui informações ou documentos pendentes.");

        $this->aliasClient->findOrCreate($accountId, AliasBankProvider::Votorantim);

        Http::assertSent(
            fn (Request $request) => $request->url() === $expectedRequestUrl
        );
    }
}
