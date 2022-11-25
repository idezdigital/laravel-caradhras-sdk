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
// <getTransfer>
//     // public function testCanGetATransfer()
//     // {
//     //     /** @var Transfer $transfer */
//     //     $transfer = Transfer::factory()
//     //         ->status(TransferStatus::Processing)
//     //         ->create();

//     //     $fakeTransactionCode = $this->faker->uuid();

//     //     $fakeTransferResponse = [
//     //         "transactionCode" => $fakeTransactionCode,
//     //         "originalAccount" => $transfer->payer->cr_account_id,
//     //         "destinationAccount" => $transfer->payee->cr_account_id,
//     //         "description" => $transfer->description,
//     //         "idAdjustment" => $transfer->cr_origin_adjustment_id,
//     //         "idIssuer" => $this->faker->numberBetween(100, 999),
//     //         "idAdjustmentDestination" => $transfer->cr_destination_adjustment_id,
//     //         "amount" => (string) $transfer->amount,
//     //         "transactionDate" => $transfer->created_at->toIso8601String(),
//     //         "status" => 'APPROVED',
//     //     ];

//     //     $expectedRequestUrl = $this->coreClient->getApiBaseUrl() . "/p2ptransfer?idAdjustment=$transfer->cr_origin_adjustment_id";

//     //     Http::fake([
//     //         $expectedRequestUrl => Http::response([$fakeTransferResponse]),
//     //     ]);

//     //     $transferAdapter = $this->mainClient->getTransfer(['idAdjustment' => $transfer->cr_origin_adjustment_id]);

//     //     $this->assertEquals($transfer->amount, (string) $transferAdapter->amount);
//     //     $this->assertEquals($fakeTransactionCode, $transferAdapter->transactionCode);
//     //     $this->assertEquals($transfer->payer->cr_account_id, $transferAdapter->originalAccount);
//     //     $this->assertEquals($transfer->payee->cr_account_id, $transferAdapter->destinationAccount);
//     //     $this->assertEquals($transfer->description, $transferAdapter->description);
//     //     $this->assertEquals('APPROVED', $transferAdapter->status->value);
//     // }

//     // public function testClientHandleErrorsOnGettingTransfer()
//     // {
//     //     $fakeAdjustmentId = $this->faker->randomNumber(3, true);

//     //     $expectedRequestUrl = $this->coreClient->getApiBaseUrl() . "/p2ptransfer?idAdjustment=$fakeAdjustmentId";

//     //     Http::fake([
//     //         $expectedRequestUrl => Http::response([]),
//     //     ]);

//     //     $this->expectException(CaradhrasException::class);
//     //     $this->expectExceptionCode(404);
//     //     $this->expectExceptionMessage('Transfer Not Found.');

//     //     try {
//     //         $this->mainClient->getTransfer(['idAdjustment' => $fakeAdjustmentId]);
//     //     } catch (CaradhrasException $exception) {
//     //         Http::assertSent(fn (Request $request) => $request->url() === $expectedRequestUrl && $request->method() === 'GET');

//     //         throw $exception;
//     //     }
//     // }
// </getTransfer>

// <findIndividual>
//     public function testCanFindAnIndividual()
//     {
//         /** @var Account $account */
//         $account = Account::factory()->personal()->create();

//         $expectedUrl = $this->coreClient->getApiBaseUrl() . "/v2/individuals?document={$account->document}";

//         $response = [
//             "previousPage" => 0,
//             "currentPage" => 1,
//             "nextPage" => 1,
//             "last" => true,
//             "totalPages" => 1,
//             "totalItems" => 1,
//             "maxItemsPerPage" => 50,
//             "totalItemsPage" => 1,
//             "items" => [
//                 [
//                     "id" => $account->holder->cr_person_id,
//                     "idRegistration" => $account->cr_registration_id,
//                     "idNumber" => "123123",
//                     "name" => $account->name,
//                     "document" => $account->document,
//                     "birthDate" => "20201-06-24",
//                     "gender" => "",
//                     "identityIssuingEntity" => "",
//                     "federativeUnit" => "",
//                     "issuingDateIdentity" => "2018-10-22",
//                     "motherName" => $account->holder->mother_name,
//                     "fatherName" => "",
//                     "idMaritalStatus" => null,
//                     "idProfession" => "1",
//                     "idNationality" => 1,
//                     "idOccupationType" => null,
//                     "bankNumber" => 0,
//                     "branchNumber" => 0,
//                     "accountNumber" => "",
//                     "email" => $account->email,
//                     "companyName" => "",
//                     "incomeValue" => 3000,
//                     "isPep" => false,
//                     "status" => 'WAITING_DOCUMENTS',
//                     "statusSPD" => [
//                         [
//                             "statusId" => "1",
//                             "name" => "Aguardando Documentos",
//                             "reason" => "Aguardando Documentos",
//                             "createDate" => "2022-01-02T17:54:35.089Z",
//                         ],
//                     ],
//                 ],
//                 [
//                     "id" => $account->holder->cr_person_id,
//                     "idRegistration" => $this->faker->uuid(),
//                     "idNumber" => "123123",
//                     "name" => $account->name,
//                     "document" => $account->document,
//                     "birthDate" => "20201-06-24",
//                     "gender" => "",
//                     "identityIssuingEntity" => "",
//                     "federativeUnit" => "",
//                     "issuingDateIdentity" => "2018-10-22",
//                     "motherName" => $account->holder->mother_name,
//                     "fatherName" => "",
//                     "idMaritalStatus" => null,
//                     "idProfession" => "1",
//                     "idNationality" => 1,
//                     "idOccupationType" => null,
//                     "bankNumber" => 0,
//                     "branchNumber" => 0,
//                     "accountNumber" => "",
//                     "email" => $account->email,
//                     "companyName" => "",
//                     "incomeValue" => 3000,
//                     "isPep" => false,
//                     "status" => 'WAITING_DOCUMENTS',
//                     "statusSPD" => [
//                         [
//                             "statusId" => "1",
//                             "name" => "Aguardando Documentos",
//                             "reason" => "Aguardando Documentos",
//                             "createDate" => "2022-01-02T17:54:35.089Z",
//                         ],
//                     ],
//                 ],
//                 [
//                     "id" => $account->holder->cr_person_id,
//                     "idRegistration" => $this->faker->uuid(),
//                     "idNumber" => "123123",
//                     "name" => $account->name,
//                     "document" => $account->document,
//                     "birthDate" => "20201-06-24",
//                     "gender" => "",
//                     "identityIssuingEntity" => "",
//                     "federativeUnit" => "",
//                     "issuingDateIdentity" => "2018-10-22",
//                     "motherName" => $account->holder->mother_name,
//                     "fatherName" => "",
//                     "idMaritalStatus" => null,
//                     "idProfession" => "1",
//                     "idNationality" => 1,
//                     "idOccupationType" => null,
//                     "bankNumber" => 0,
//                     "branchNumber" => 0,
//                     "accountNumber" => "",
//                     "email" => $account->email,
//                     "companyName" => "",
//                     "incomeValue" => 3000,
//                     "isPep" => false,
//                     "status" => 'WAITING_DOCUMENTS',
//                     "statusSPD" => [
//                         [
//                             "statusId" => "1",
//                             "name" => "Aguardando Documentos",
//                             "reason" => "Aguardando Documentos",
//                             "createDate" => "2022-01-02T17:54:35.089Z",
//                         ],
//                     ],
//                 ],
//             ],
//         ];

//         Http::fake([
//             $expectedUrl => Http::response($response),
//         ]);

//         $individual = $this->coreClient->findIndividual($account->cr_registration_id, $account->document);

//         $this->assertEquals($account->name, $individual->name);
//         $this->assertEquals($account->document, $individual->document);
//         $this->assertEquals($account->holder->cr_person_id, $individual->id);

//         Http::assertSent(
//             fn (Request $request) => $request->url() === $expectedUrl &&
//                 $request->method() === 'GET'
//         );
//     }

//     public function testHandleNotFoundOnFindingAnIndividual()
//     {
//         /** @var Account $account */
//         $account = Account::factory()->personal()->create();

//         $expectedUrl = $this->coreClient->getApiBaseUrl() . "/v2/individuals?document={$account->document}";

//         $response = [
//             "message" => "Person not found.",
//         ];

//         Http::fake([
//             $expectedUrl => Http::response($response, 404),
//         ]);

//         $this->expectException(CaradhrasException::class);
//         $this->expectExceptionCode(404);
//         $this->expectExceptionMessage('Failed to get individual.');

//         try {
//             $this->coreClient->findIndividual($account->cr_registration_id, $account->document);
//         } catch (Throwable $e) {
//             Http::assertSent(
//                 fn (Request $request) => $request->url() === $expectedUrl &&
//                     $request->method() === 'GET'
//             );

//             throw  $e;
//         }
//     }

//     public function testCantGetAnIndividualWithoutCrPersonId()
//     {
//         Http::fake();
//         /** @var Account $account */
//         $account = Account::factory()->personal(['cr_person_id' => null])->create();

//         $this->expectException(\App\Exceptions\CaradhrasException::class);
//         $this->expectExceptionCode(500);
//         $this->expectExceptionMessage('Failed to get individual.');

//         try {
//             $this->coreClient->getIndividual($account->holder->cr_person_id);
//         } catch (Throwable $e) {
//             Http::assertNothingSent();

//             throw  $e;
//         }
//     }

//     public function testCanGetAnIndividual()
//     {
//         /** @var Account $account */
//         $account = Account::factory()->personal()->create();

//         $expectedUrl = $this->mainClient->getApiBaseUrl() . "/v2/individuals/{$account->holder->cr_person_id}?" . http_build_query(['statusSPD' => 'true']);

//         $response = [
//             "id" => $account->holder->cr_person_id,
//             "idRegistration" => $account->cr_registration_id,
//             "idNumber" => "123123",
//             "name" => $account->name,
//             "document" => $account->document,
//             "birthDate" => "20201-06-24",
//             "gender" => "",
//             "identityIssuingEntity" => "",
//             "federativeUnit" => "",
//             "issuingDateIdentity" => "2018-10-22",
//             "motherName" => $account->holder->mother_name,
//             "fatherName" => "",
//             "idMaritalStatus" => null,
//             "idProfession" => "1",
//             "idNationality" => 1,
//             "idOccupationType" => null,
//             "bankNumber" => 0,
//             "branchNumber" => 0,
//             "accountNumber" => "",
//             "email" => $account->email,
//             "companyName" => "",
//             "incomeValue" => 3000,
//             "isPep" => false,
//             "status" => 'WAITING_DOCUMENTS',
//             "statusSPD" => [
//                 [
//                     "statusId" => "1",
//                     "name" => "Aguardando Documentos",
//                     "reason" => "Aguardando Documentos",
//                     "createDate" => "2022-01-02T17:54:35.089Z",
//                 ],
//             ],
//         ];

//         Http::fake([
//             $expectedUrl => Http::response($response),
//         ]);

//         $individual = $this->mainClient->getIndividual($account->holder->cr_person_id);

//         $this->assertEquals($account->name, $individual->name);
//         $this->assertEquals($account->document, $individual->document);
//         $this->assertEquals($account->holder->cr_person_id, $individual->id);

//         Http::assertSent(
//             fn (Request $request) => $request->url() === $expectedUrl &&
//                 $request->method() === 'GET'
//         );
//     }
// </findIndividual>

// <associateCardToAccount>
// não tem testes ainda
// </associateCardToAccount>

// <updateIndividuals>
// não tem testes ainda
// </updateIndividuals>

// <getAccount>
// não tem testes ainda
// </getAccount>

// <backgroundCheck>
// não tem testes ainda
// </backgroundCheck>

// <getTransactions>
// não tem testes ainda
// </getTransactions>

// <updateAddress>
// não tem testes ainda
// </updateAddress>

// <p2p>
// não tem testes ainda
// </p2p>

// <getBalance>
// não tem testes ainda
// </getBalance>

// <unlockSystemBlockedCard>
// não tem testes ainda
// </unlockSystemBlockedCard>

// <listAccounts>
// não tem testes ainda
// </listAccounts>

// <getPhoneRecharge>
// não tem testes ainda
// </getPhoneRecharge>

// <setCardPassword>
// não tem testes ainda
// </setCardPassword>

// <updateCardPassword>
    // public function testCanUpdateACardPassword()
    // {
    //     $crCardId = $this->faker->randomNumber(5, true);
    //     $newPassword = $this->faker->randomNumber(4, true);

    //     $expectedUrl = $this->caradhrasCoreClient->getApiBaseUrl() . "/cartoes/$crCardId/alterar-senha";

    //     $response = [
    //         "headers" => [],
    //         "body" => "Operação executada com sucesso.",
    //         "statusCodeValue" => 200,
    //         "statusCode" => "OK",
    //     ];

    //     Http::fake([
    //         $expectedUrl => Http::response($response),
    //     ]);

    //     $this->caradhrasCoreClient->updateCardPassword($crCardId, $newPassword);

    //     Http::assertSent(
    //         fn (Request $request) => $request->method() === 'PUT' &&
    //             $request->url() === $expectedUrl &&
    //             $request->header('senha') === [(string) $newPassword]
    //     );
    // }
// </updateCardPassword>
}
