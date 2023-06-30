<?php

namespace Idez\Caradhras\Tests\Unit\Clients;

use Idez\Caradhras\Clients\CaradhrasLimitsClient;
use Idez\Caradhras\Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Http;

class CaradhrasLimitsClientTest extends TestCase
{
    use WithFaker;

    private CaradhrasLimitsClient $limitsClient;

    protected function setUp(): void
    {
        parent::setUp();
        $this->limitsClient = app(CaradhrasLimitsClient::class);
    }

    public function testCanGetAccountLimitRequests(): void
    {
        $accountId = uniqid();
        $expectedRequestUrl = $this->limitsClient->getApiBaseUrl() . "/limits/v2/requests?idAccount={$accountId}";

        $fakeResponse = [
            [
                'accountId' => $accountId,
                'type' => 002,
                'status' => 'PENDING',
            ],
            [
                'accountId' => $accountId,
                'type' => 005,
                'status' => 'APPROVED',
            ],
            [
                'accountId' => $accountId,
                'type' => 010,
                'status' => 'PENDING',
            ],
        ];

        Http::fake([
            $expectedRequestUrl => Http::response($fakeResponse),
        ]);

        $response = $this->limitsClient->getAccountLimitRequests($accountId);

        $this->assertEquals(
            json_decode(json_encode($fakeResponse)),
            $response
        );
    }
}
