<?php

namespace Idez\Caradhras\Tests\Unit\Clients;

use App\Models\Account;
use Idez\Caradhras\Clients\CaradhrasMainClient;
use Idez\Caradhras\Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\Client\Request;
use Illuminate\Support\Facades\Http;

class CaradhrasMainClientTest extends TestCase
{
    use WithFaker;

    private CaradhrasMainClient $mainClient;

    protected function setUp(): void
    {
        parent::setUp();
        $this->mainClient = app(CaradhrasMainClient::class);
    }

    //    public function testCantGetAnIndividualWithoutCrPersonId()
    //    {
    //        Http::fake();
    //        /** @var Account $account */
    //        $account = Account::factory()->personal(['cr_person_id' => null])->create();
    //
    //        $this->expectException(\App\Exceptions\CaradhrasException::class);
    //        $this->expectExceptionCode(500);
    //        $this->expectExceptionMessage('Failed to get individual.');
    //
    //        try {
    //            $this->coreClient->getIndividual($account->holder->cr_person_id);
    //        } catch (Throwable $e) {
    //            Http::assertNothingSent();
    //
    //            throw  $e;
    //        }
    //    }

    //    public function testCanGetAnIndividual()
    //    {
    //        /** @var Account $account */
    //        $account = Account::factory()->personal()->create();
    //
    //        $expectedUrl = $this->mainClient->getApiBaseUrl() . "/v2/individuals/{$account->holder->cr_person_id}?" . http_build_query(['statusSPD' => 'true']);
    //
    //        $response = [
    //            "id" => $account->holder->cr_person_id,
    //            "idRegistration" => $account->cr_registration_id,
    //            "idNumber" => "123123",
    //            "name" => $account->name,
    //            "document" => $account->document,
    //            "birthDate" => "20201-06-24",
    //            "gender" => "",
    //            "identityIssuingEntity" => "",
    //            "federativeUnit" => "",
    //            "issuingDateIdentity" => "2018-10-22",
    //            "motherName" => $account->holder->mother_name,
    //            "fatherName" => "",
    //            "idMaritalStatus" => null,
    //            "idProfession" => "1",
    //            "idNationality" => 1,
    //            "idOccupationType" => null,
    //            "bankNumber" => 0,
    //            "branchNumber" => 0,
    //            "accountNumber" => "",
    //            "email" => $account->email,
    //            "companyName" => "",
    //            "incomeValue" => 3000,
    //            "isPep" => false,
    //            "status" => 'WAITING_DOCUMENTS',
    //            "statusSPD" => [
    //                [
    //                    "statusId" => "1",
    //                    "name" => "Aguardando Documentos",
    //                    "reason" => "Aguardando Documentos",
    //                    "createDate" => "2022-01-02T17:54:35.089Z",
    //                ],
    //            ],
    //        ];
    //
    //        Http::fake([
    //            $expectedUrl => Http::response($response),
    //        ]);
    //
    //        $individual = $this->mainClient->getIndividual($account->holder->cr_person_id);
    //
    //        $this->assertEquals($account->name, $individual->name);
    //        $this->assertEquals($account->document, $individual->document);
    //        $this->assertEquals($account->holder->cr_person_id, $individual->id);
    //
    //        Http::assertSent(
    //            fn (Request $request) => $request->url() === $expectedUrl &&
    //                $request->method() === 'GET'
    //        );
    //    }
}
