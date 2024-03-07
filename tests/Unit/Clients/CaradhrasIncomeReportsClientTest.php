<?php

namespace Idez\Caradhras\Tests\Unit\Clients;

use Idez\Caradhras\Clients\CaradhrasIncomeReportsClient;
use Idez\Caradhras\Exceptions\FindIncomeReportsException;
use Idez\Caradhras\Exceptions\SendIncomeReportsToEmailException;
use Idez\Caradhras\Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\Client\Request;
use Illuminate\Support\Facades\Http;

class CaradhrasIncomeReportsClientTest extends TestCase
{
    use WithFaker;

    public CaradhrasIncomeReportsClient $client;

    protected function setUp(): void
    {
        parent::setUp();
        $this->client = app(CaradhrasIncomeReportsClient::class);
    }

    private function getApiBaseUrl(): string
    {
        return $this->client->getApiBaseUrl();
    }

    public function testCaradhrasIncomeReportsApiPrefixIsCorrect()
    {
        $this->assertEquals('declarables', $this->client::API_PREFIX);
    }

    /** @test */
    public function testCanGetAvailableIncomeReports()
    {
        $body = [
            'reports' => [
                'year' => 2020,
                'quarter' => null,
                'reportCode' => '2020',
            ],
        ];

        $url = $this->getApiBaseUrl().'/v1/reports/1';
        Http::fake([
            $url => Http::response($body),
        ]);

        $response = $this->client->getAvailable(1);

        Http::assertSent(
            fn (Request $request) => $request->url() === $url && $request->method() === 'GET'
        );

        $this->assertEquals($response, json_decode(json_encode($body)));
        $this->assertIsObject($response);
    }

    /** @test */
    public function tesFailedToGetAvailableIncomeReportsThrowCorrectException()
    {
        $url = $this->getApiBaseUrl().'/v1/reports/1';
        Http::fake([
            $url => Http::response('error', 500),
        ]);

        $this->expectException(FindIncomeReportsException::class);
        $this->expectExceptionCode(502);

        $response = $this->client->getAvailable(1);

        Http::assertSent(
            fn (Request $request) => $request->url() === $url && $request->method() === 'GET'
        );

        $this->assertIsObject($response);
    }

    /** @test */
    public function tesFailedToSendIncomeReportsToEmailThrowCorrectException()
    {
        $url = $this->getApiBaseUrl().'/v1/requests';
        Http::fake([
            $url => Http::response('error', 500),
        ]);

        $this->expectException(SendIncomeReportsToEmailException::class);
        $this->expectExceptionCode(502);

        $response = $this->client->sendToEmail(1, 2020, $this->faker->email());

        Http::assertSent(
            fn (Request $request) => $request->url() === $url && $request->method() === 'POST'
        );

        $this->assertIsObject($response);
    }

    /** @test */
    public function tesCanToSendIncomeReportsToEmailThrowCorrectException()
    {
        $body = [
            'ticket' => $this->faker->uuid(),
        ];
        $url = $this->getApiBaseUrl().'/v1/requests';
        Http::fake([
            $url => Http::response($body, 202),
        ]);

        $response = $this->client->sendToEmail(1, 2020, $this->faker->email());

        Http::assertSent(
            fn (Request $request) => $request->url() === $url && $request->method() === 'POST'
        );

        $this->assertEquals(json_decode(json_encode($body)), $response);
        $this->assertIsObject($response);
    }
}
