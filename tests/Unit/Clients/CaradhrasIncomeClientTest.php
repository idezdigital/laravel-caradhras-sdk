<?php

namespace Idez\Caradhras\Tests\Unit\Clients;

use Idez\Caradhras\Clients\CaradhrasAliasClient;
use Idez\Caradhras\Clients\CaradhrasIncomeClient;
use Idez\Caradhras\Enums\AliasBankProvider;
use Idez\Caradhras\Exceptions\CaradhrasException;
use Idez\Caradhras\Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\Client\Request;
use Illuminate\Support\Facades\Http;

class CaradhrasIncomeClientTest extends TestCase
{
    use WithFaker;

    private CaradhrasIncomeClient $incomeClient;

    protected function setUp(): void
    {
        parent::setUp();

        $this->incomeClient = app(CaradhrasIncomeClient::class);
    }

    public function testIfCanCancelAccountWithSuccess(): void
    {
        $accountId = $this->faker->randomNumber(5);
        $profitablePercentage = $this->faker->randomNumber(2);
        $splitPercentage = $this->faker->randomNumber(2);

        $expectedRequestUrl = $this->incomeClient->getApiBaseUrl() . '/v1/setup/accounts';

        Http::fake([
            $expectedRequestUrl => Http::response(
                body: [
                    'message' => 'Success!',
                ],
                status: 200,
            ),
        ]);

        $this->incomeClient->createParametrizationForAccount($accountId, $profitablePercentage, $splitPercentage);

        Http::assertSent(
            fn (Request $request) => $request->url() === $expectedRequestUrl
                && $request->method() === 'POST'
                && $request['accountId'] === $accountId
                && $request['profitablePercentage'] === $profitablePercentage
                && $request['splitPercentage'] === $splitPercentage
        );
    }
}
