<?php

namespace Idez\Caradhras\Tests\Unit\Clients;

use Idez\Caradhras\Clients\CaradhrasRemunerationsClient;
use Idez\Caradhras\Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\Client\Request;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Http;

class CaradhrasRemunerationsClientTest extends TestCase
{
    use WithFaker;

    private CaradhrasRemunerationsClient $crClient;

    protected function setUp(): void
    {
        parent::setUp();

        $this->crClient = app(CaradhrasRemunerationsClient::class);
    }

    public function testCanListAccountRemunerations(): void
    {
        $accountId = $this->faker->randomNumber(5);

        $expectedRequestUrl = $this->crClient->getApiBaseUrl().'/v1/accounts/'.$accountId.'/remunerations';

        $makeFakeRemuneration = fn () => [
            'idAccount' => $this->faker()->randomNumber(5),
            'remunerationId' => $this->faker()->uuid,
            'remunerationDate' => $this->faker()->date(),
            'remunerationAmount' => $this->faker()->randomFloat(2, 0, 100),
            'remunerationStatus' => $this->faker()->randomElement(['PAID', 'PENDING']),
            'remunerationIof' => $this->faker()->randomFloat(2, 0, 100),
            'remunerationIr' => $this->faker()->randomFloat(2, 0, 100),
        ];

        $expectedResponse = [
            'previousPage' => 1,
            'currentPage' => 1,
            'nextPage' => 2,
            'last' => false,
            'totalPages' => 5,
            'totalItems' => 100,
            'maxItemsPerPage' => 2,
            'totalItemsPage' => 2,
            'items' => [
                $makeFakeRemuneration(),
                $makeFakeRemuneration(),
            ],
        ];

        Http::fake([
            $expectedRequestUrl => Http::response($expectedResponse),
        ]);

        $response = $this->crClient->listAccountRemunerations($accountId);

        Http::assertSent(
            fn (Request $request) => $request->url() === $expectedRequestUrl
                && str($request->url())->startsWith('https://remunerations.')
                && $request->method() === 'GET'
        );

        $this->assertInstanceOf(Response::class, $response);
        $this->assertEquals($expectedResponse, $response->json());
    }

    public function testCanListAccountRemunerationsWithCustomFilters(): void
    {
        $accountId = $this->faker->randomNumber(5);
        $filters = [
            'initialDate' => $this->faker()->date(),
            'endDate' => $this->faker()->date(),
        ];

        $expectedRequestUrl = $this->crClient->getApiBaseUrl().'/v1/accounts/'.$accountId.'/remunerations'.'?'.http_build_query($filters);

        $makeFakeRemuneration = fn () => [
            'idAccount' => $this->faker()->randomNumber(5),
            'remunerationId' => $this->faker()->uuid,
            'remunerationDate' => $this->faker()->date(),
            'remunerationAmount' => $this->faker()->randomFloat(2, 0, 100),
            'remunerationStatus' => $this->faker()->randomElement(['PAID', 'PENDING']),
            'remunerationIof' => $this->faker()->randomFloat(2, 0, 100),
            'remunerationIr' => $this->faker()->randomFloat(2, 0, 100),
        ];

        $expectedResponse = [
            'previousPage' => 1,
            'currentPage' => 1,
            'nextPage' => 2,
            'last' => false,
            'totalPages' => 5,
            'totalItems' => 100,
            'maxItemsPerPage' => 2,
            'totalItemsPage' => 2,
            'items' => [
                $makeFakeRemuneration(),
                $makeFakeRemuneration(),
            ],
        ];

        Http::fake([
            $expectedRequestUrl => Http::response($expectedResponse),
        ]);

        $response = $this->crClient->listAccountRemunerations($accountId, $filters);

        Http::assertSent(
            fn (Request $request) => $request->url() === $expectedRequestUrl
                && str($request->url())->startsWith('https://remunerations.')
                && $request->method() === 'GET'
        );

        $this->assertInstanceOf(Response::class, $response);
        $this->assertEquals($expectedResponse, $response->json());
    }
}
