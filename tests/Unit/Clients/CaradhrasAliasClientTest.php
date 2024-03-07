<?php

namespace Idez\Caradhras\Tests\Unit\Clients;

use Idez\Caradhras\Clients\CaradhrasAliasClient;
use Idez\Caradhras\Data\AliasBankAccount;
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

        $this->aliasClient->findOrCreate($accountId);
    }

    public function testIfCanListWithSuccess(): void
    {
        $accountId = $this->faker->randomNumber(5);

        $expectedRequestUrl = $this->aliasClient->getApiBaseUrl() . "/v1/accounts?idAccount={$accountId}";

        $aliasAccount1 = new AliasBankAccount([
            'idAccount' => $accountId,
            'bankNumber' => '301',
            'bankAccountStatus' => 'OPEN',
        ]);

        $aliasAccount2 = new AliasBankAccount([
            'idAccount' => $accountId,
            'bankNumber' => '208',
            'bankAccountStatus' => 'CLOSED',
        ]);

        $fakeResponse = [
            'items' => [
                $aliasAccount1->jsonSerialize(),
                $aliasAccount2->jsonSerialize(),
            ]
        ];

        Http::fake([
            $expectedRequestUrl => Http::response($fakeResponse),
        ]);

        $result = $this->aliasClient->list($accountId);

        $this->assertContainsOnlyInstancesOf(AliasBankAccount::class, $result);

        $this->assertEquals($aliasAccount1, $result[0]);
        $this->assertEquals($aliasAccount2, $result[1]);

        Http::assertSent(
            fn (Request $request) => $request->url() === $expectedRequestUrl
                && $request->method() === 'GET'
                && $request['idAccount'] === $accountId
        );
    }

    public function testIfCanCancelAccountWithSuccess(): void
    {
        $accountId = $this->faker->randomNumber(5);
        $bankNumber = AliasBankProvider::Dock;

        $expectedRequestUrl = $this->aliasClient->getApiBaseUrl() . '/v1/accounts';

        Http::fake([
            $expectedRequestUrl => Http::response(['message' => 'This account are closed']),
        ]);

        $this->aliasClient->delete($accountId, $bankNumber);

        Http::assertSent(
            fn (Request $request) => $request->url() === $expectedRequestUrl
                && $request->method() === 'DELETE'
                && $request['idAccount'] === $accountId
                && $request['bankNumber'] === $bankNumber->value
        );
    }
}
