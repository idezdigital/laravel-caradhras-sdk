<?php

namespace Idez\Caradhras\Tests\Unit\Clients;

use Idez\Caradhras\Exceptions\CaradhrasException;
use Idez\Caradhras\Clients\CaradhrasPrecautionaryBlockClient;
use Idez\Caradhras\Tests\TestCase;
use Illuminate\Support\Facades\Http;

class CaradhrasPrecautionaryBlockClientTest extends TestCase
{
    protected CaradhrasPrecautionaryBlockClient $caradhras;

    protected function setUp(): void
    {
        parent::setUp();
        $this->caradhras = app(CaradhrasPrecautionaryBlockClient::class);
    }

    public function testCaradhrasApiPrefixIsCorrect()
    {
        $this->assertEquals('precautionary-block', $this->caradhras::API_PREFIX);
    }

    public function testCanUnlockTransaction()
    {
        Http::fake([
            'v1/locks/E00038166201907261559y6j6mt9l0pi' => Http::response(status: 204),
        ]);

        $this->assertTrue($this->caradhras->unlockTransaction('E00038166201907261559y6j6mt9l0pi'));
    }

    public function testCanUnlockTransactionWithFailed()
    {
        $this->expectException(CaradhrasException::class);
        $this->expectExceptionMessage('Bad request');

        Http::fake([
            'v1/locks/E00038166201907261559y6j6mt9l0pi' => Http::response(
                [
                    "error" => [
                        "id" => "a3e6e02b-8f20-4c4e-8e40-c4aa8083d070",
                        "description" => "Bad request",
                        "code" => 0,
                        "error_details" => [
                            [
                                "attribute" => "query.page, query.limit",
                                "messages" => [
                                    "INVALID_DATA_TYPE",
                                    "INVALID_LENGTH",
                                    "INVALID_FORMAT",
                                    "INVALID_VALUE",
                                    "REQUIRED_ATTRIBUTE_MISSING"
                                ]
                            ]
                        ]
                    ]
                ],
                400
            ),
        ]);

        $this->caradhras->unlockTransaction('E00038166201907261559y6j6mt9l0pi');
    }

    public function testCanUnlockTransactionWithReturns404StatusCode()
    {
        $this->expectException(CaradhrasException::class);
        $this->expectExceptionMessage('Transaction not found');

        Http::fake([
            'v1/locks/E00038166201907261559y6j6mt9l0pi' => Http::response(
                [
                    "error" => [
                        "id" => "a3e6e02b-8f20-4c4e-8e40-c4aa8083d070",
                        "description" => "Not found",
                        "code" => 404
                    ]
                ],
                404
            ),
        ]);

        $this->caradhras->unlockTransaction('E00038166201907261559y6j6mt9l0pi');
    }

    public function testCanUnlockTransactionWithExternalIdAlredyUsed()
    {
        $this->expectException(CaradhrasException::class);
        $this->expectExceptionMessage('Transaction already unlocked');

        Http::fake([
            'v1/locks/E00038166201907261559y6j6mt9l0pi' => Http::response(
                [
                    "error" => [
                        "id" => "a3e6e02b-8f20-4c4e-8e40-c4aa8083d070",
                        "description" => "externalId already used",
                        "code" => "BLOQ-0001"
                    ]
                ],
                422
            ),
        ]);

        $this->caradhras->unlockTransaction('E00038166201907261559y6j6mt9l0pi');
    }

    public function testCanListLockedTransactions()
    {
        Http::fake(['v1/locks?page=1&limit=10' => [
            "content" => [
                [
                    "external_id" => "string",
                    "created_date" => "2019-08-24T14:15:22Z",
                    "account_id" => 0,
                    "amount" => 0
                ]
            ],
            "metadata" => [
                [
                    "pagination" => [
                        [
                            "page" => 0,
                            "limit" => 0
                        ]
                    ]
                ]
            ]
        ]]);

        $response = $this->caradhras->listLockedTransactions(1, 10);
        $this->assertNotEmpty($response);
    }
}