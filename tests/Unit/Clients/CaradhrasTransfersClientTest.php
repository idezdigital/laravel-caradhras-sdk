<?php

namespace Idez\Caradhras\Tests\Unit\Clients;

use Idez\Caradhras\Clients\CaradhrasTransfersClient;
use Idez\Caradhras\Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\Client\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

class CaradhrasTransfersClientTest extends TestCase
{
    use WithFaker;

    protected \Idez\Caradhras\Clients\CaradhrasTransfersClient $caradhras;

    protected function setUp(): void
    {
        parent::setUp();
        $this->caradhras = app(CaradhrasTransfersClient::class);
    }

    private function getApiBaseUrl(): string
    {
        return $this->caradhras->getApiBaseUrl();
    }

    public function testCaradhrasCompanyApiPrefixIsCorrect()
    {
        $this->assertEquals('transfers', $this->caradhras::API_PREFIX);
    }

    /** @test */
    public function testCanGetBankTransfer()
    {
        $registration = Str::uuid()->toString();
        $url = $this->getApiBaseUrl() . "/v1/cashout/receipt/{$registration}";

        $response = ['result' => []];

        Http::fake([
            $url => Http::response($response, 200),
        ]);

        $response = $this->caradhras->getTransfer($registration);

        Http::assertSent(
            fn (Request $request) => $request->url() === $url
        );

        $this->assertIsObject($response);
    }

    /** @test */
    public function testCanListBankTransfer()
    {
        $url = $this->getApiBaseUrl() . "/v1/cashout/transactions";

        $response = ['items' => [1,2,3]];

        Http::fake([
            $url => Http::response($response, 200),
        ]);

        $response = $this->caradhras->listTransfers();

        Http::assertSent(
            fn (Request $request) => $request->url() === $url
        );

        $this->assertIsObject($response);
    }

    /** @test */
    public function testCanListBankTransferUsingFilters()
    {
        $url = $this->getApiBaseUrl() . "/v1/cashout/transactions?page=2";

        $response = ['items' => [1,2,3]];

        Http::fake([
            $url => Http::response($response, 200),
        ]);

        $response = $this->caradhras->listTransfers(['page' => 2]);

        Http::assertSent(
            fn (Request $request) => $request->url() === $url
        );

        $this->assertIsObject($response);
    }

    /** @test */
    public function testCanCreateBankTransfer()
    {
        $url = $this->getApiBaseUrl() . "/v1/cashout";

        $response = ['result' => []];

        Http::fake([
            $url => Http::response($response, 200),
        ]);

        $response = $this->caradhras->transfer(
            accountId: $this->faker->randomNumber(),
            beneficiary: [],
            amount: $this->faker->randomFloat(2, 10, 100),
            description: $this->faker->text(20)
        );

        Http::assertSent(
            fn (Request $request) => $request->url() === $url
        );

        $this->assertIsObject($response);
    }
}
