<?php

namespace Idez\Caradhras\Tests\Unit\Clients;

use Idez\Caradhras\Clients\CaradhrasLimitsClient;
use Idez\Caradhras\Data\LimitCollection;
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

        $expectedResponse = $this->generateFakeResponse();

        Http::fake([
            $expectedRequestUrl => Http::response($expectedResponse),
        ]);

        $limitRequests = $this->limitsClient->getAccountLimitRequests($accountId);

        $this->assertInstanceOf(LimitCollection::class, $limitRequests);

        $items = $limitRequests->items;

        for ($i = 0; $i < count($items); $i++) {
            $limitToValidate = $items[$i]->toArray();
            $expectedLimit = $expectedResponse['items'][$i];

            $this->assertEquals($limitToValidate, $expectedLimit);
        }
    }

    public function generateFakeResponse(): array
    {
        return [
            'previousPage' => 1,
            'currentPage' => 1,
            'nextPage' => 1,
            'last' => true,
            'totalPages' => 1,
            'totalItems' => 3,
            'maxItemsPerPage' => 150,
            'totalItemsPage' => 3,
            'items' => [
                [
                    'idAccount' => 1234,
                    'requestLimit' => 2000000,
                    'limitType' => '3',
                    'idRequest' => 5678,
                    'idBatch' => null,
                    'status' => "APPROVED",
                    'idServicesGroup' => 1,
                    'beneficiaryType' => "",
                    'requestResultDate' => "2023-06-21T20:00:33Z",
                    'requestDate' => "2023-06-20T19:55:29Z",
                    'requestDeadline' => "2023-06-22T19:55:28Z",
                    'requestMinApprovalDate' => "2023-06-20T19:55:29Z",
                    'trustedDestination' => [
                        'nationalRegistration' => null,
                        'ispb' => null,
                        'bankBranchNumber' => null,
                        'bankAccountNumber' => null,
                        'idAccount' => null
                    ]
                ],
                [
                    'idAccount' => 1234,
                    'requestLimit' => 150000,
                    'limitType' => "1",
                    'idRequest' => 5679,
                    'idBatch' => null,
                    'status' => "APPROVED",
                    'idServicesGroup' => 1,
                    'beneficiaryType' => "",
                    'requestResultDate' => "2023-06-20T19:53:10Z",
                    'requestDate' => "2023-06-19T17:56:51Z",
                    'requestDeadline' => "2023-06-21T17:56:51Z",
                    'requestMinApprovalDate' => "2023-06-19T17:56:51Z",
                    'trustedDestination' => [
                        'nationalRegistration' => null,
                        'ispb' => null,
                        'bankBranchNumber' => null,
                        'bankAccountNumber' => null,
                        'idAccount' => null
                    ]
                ],
                [
                    'idAccount' => 1234,
                    'requestLimit' => 150000,
                    'limitType' => "2",
                    'idRequest' => 5670,
                    'idBatch' => null,
                    'status' => "APPROVED",
                    'idServicesGroup' => 1,
                    'beneficiaryType' => "",
                    'requestResultDate' => "2023-06-20T19:53:06Z",
                    'requestDate' => "2023-06-19T17:54:08Z",
                    'requestDeadline' => "2023-06-21T17:54:08Z",
                    'requestMinApprovalDate' => "2023-06-19T17:54:08Z",
                    'trustedDestination' => [
                        'nationalRegistration' => null,
                        'ispb' => null,
                        'bankBranchNumber' => null,
                        'bankAccountNumber' => null,
                        'idAccount' => null
                    ]
                ]
            ]
         ];
    }
}
