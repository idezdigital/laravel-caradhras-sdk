<?php

namespace Idez\Caradhras\Tests\Unit\Clients;

use GuzzleHttp\Exception\ClientException;
use Idez\Caradhras\Clients\CaradhrasCompanyClient;
use Idez\Caradhras\Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\Client\Request;
use Illuminate\Support\Facades\Http;

class CaradhrasCompanyClientTest extends TestCase
{
    use WithFaker;

    private CaradhrasCompanyClient $client;

    protected function setUp(): void
    {
        parent::setUp();
        $this->client = app(CaradhrasCompanyClient::class);
    }

    private function getApiBaseUrl(): string
    {
        return $this->client->getApiBaseUrl();
    }

    public function testCanGetPendingDocuments()
    {
        $registrationId = $this->faker->uuid;
        $body = [
            "registrationId" => $registrationId,
            "nationalRegistration" => "99999999999999",
            "legalNature" => "9999",
            "status" => "WAITING_DOCUMENTS",
            "note" => [
                "id" => "ffffffff-gggg-hhhh-iiii-jjjjjjjjjjjj",
                "create" => "2023-05-10T12:41:08.581590Z",
                "text" => "{'name': 'stepBasicDataCompanyRules', 'description': 'PJ Dados Básicos - Validacao com sucesso', 'status': 'APPROVED', 'reasonCode': 'PJBDC-012'}",
            ],
            "documents" => [
                "uploaded" => [
                    "company" => [],
                    "individuals" => [],
                ],
                "pendingUpload" => [
                    "company" => [
                        "mustHave" => [],
                        "atLeastOne" => [],
                    ],
                    "individuals" => [
                        [
                            "partnerId" => "kkkkkkkk-llll-mmmm-nnnn-oooooooooooo",
                            "profile" => "OWNER",
                            "type" => [
                                "MASTER",
                            ],
                            "mustHave" => [
                                "SELFIE",
                            ],
                            "atLeastOne" => [
                                [
                                    "IDENTITY_CARD_FRONT",
                                    "IDENTITY_CARD_VERSE",
                                ],
                                [
                                    "DRIVER_LICENSE_FRONT",
                                    "DRIVER_LICENSE_VERSE",
                                ],
                                [
                                    "DIGITAL_DRIVER_LICENSE",
                                ],
                            ],
                        ],
                    ],
                ],
            ],
        ];

        $url = $this->getApiBaseUrl() . "/v1/registrations/{$registrationId}/documents/status";
        Http::fake([
            $url => Http::response($body),
        ]);

        $response = $this->client->getPendingDocuments($registrationId);

        Http::assertSent(
            fn (Request $request) => $request->url() === $url && $request->method() === 'GET'
        );

        $this->assertEquals(json_decode(json_encode($body)), $response);
        $this->assertIsObject($response);
    }

    public function testGetPendingDocumentsThrowsError()
    {
        $registrationId = $this->faker->uuid;
        $url = $this->getApiBaseUrl() . "/v1/registrations/{$registrationId}/documents/status";
        Http::fake([
            $url => Http::response([], 400),
        ]);

        $this->expectException(ClientException::class);
        $this->client->getPendingDocuments($registrationId);
    }
}
