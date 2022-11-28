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

// <issuePhysicalCard>
// não tem testes ainda
// </issuePhysicalCard>

// <issueVirtualCard>
// não tem testes ainda
// </issueVirtualCard>

// <getAddressByIndividualId>
// não tem testes ainda
// </getAddressByIndividualId>

// <updateAccountProduct>
// não tem testes ainda
// </updateAccountProduct>

// <transactions>
// não tem testes ainda
// </transactions>

// <testCanGetCardDetails>
    // public function testCanGetCardDetails()
    // {
    //     $fakeCrCardId = $this->faker->numberBetween(111, 999);
    //     $fakeCrAccountId = $this->faker->numberBetween(111, 999);

    //     $account = Account::factory()
    //         ->active()
    //         ->create([
    //             'cr_account_id' => $fakeCrAccountId,
    //         ]);

    //     $card = Card::factory()
    //         ->virtual()
    //         ->status(CardStatus::Active)
    //         ->create([
    //             'account_id' => $account->id,
    //             'cr_card_id' => $fakeCrCardId,
    //         ]);

    //     $expectedCardData = CardDetails::factory()->fromCard($card)->make();

    //     $expectedRequestUrl = $this->caradhrasCoreClient->getApiBaseUrl() . "/cartoes/{$card->cr_card_id}/consultar-dados-reais";

    //     Http::fake([
    //         $expectedRequestUrl => Http::response($expectedCardData->jsonSerialize()),
    //     ]);

    //     $cardDetails = $this->caradhrasCoreClient->getCardDetails($card->cr_card_id);

    //     Http::assertSent(
    //         fn (Request $request) => $request->url() === $expectedRequestUrl
    //     );

    //     [$first4, $last4] = preg_split('/\*{8}/', $card->number);

    //     $cardNumberRegex = "/^{$first4}\d{8}{$last4}$/";

    //     $this->assertInstanceOf(CardDetails::class, $cardDetails);
    //     $this->assertEquals($fakeCrCardId, $cardDetails->idCartao);
    //     $this->assertEquals($fakeCrAccountId, $cardDetails->idConta);
    //     $this->assertEquals($account->name, $cardDetails->nomePlastico);
    //     $this->assertEquals(1, $cardDetails->flagVirtual);
    //     $this->assertEquals(1, preg_match('/\d{3}/', $cardDetails->cvv2));
    //     $this->assertEquals(1, preg_match($cardNumberRegex, $cardDetails->numeroCartao));
    // }
    // public function testThrowsCustomExceptionOnGettingCardDetailsFailures()
    // {
    //     $card = Card::factory()
    //         ->virtual()
    //         ->status(CardStatus::Active)
    //         ->create();

    //     $fakeResponseError = [
    //         'uuid' => $this->faker->uuid(),
    //         'message' => 'fake error message',
    //     ];

    //     $expectedRequestUrl = $this->caradhrasCoreClient->getApiBaseUrl() . "/cartoes/{$card->cr_card_id}/consultar-dados-reais";

    //     Http::fake([
    //         $expectedRequestUrl => Http::response($fakeResponseError, 504),
    //     ]);

    //     $this->expectException(GetCardDetailsException::class);
    //     $this->expectExceptionCode(502);
    //     $this->expectExceptionMessage(trans('errors.card.failed_get_details'));

    //     try {
    //         $this->caradhrasCoreClient->getCardDetails($card->cr_card_id);
    //     } catch (GetCardDetailsException $exception) {
    //         $this->assertEquals($fakeResponseError, $exception->getData());
    //         $this->assertEquals('card.failed_get_details', $exception->getKey());

    //         Http::assertSent(
    //             fn (Request $request) => $request->url() === $expectedRequestUrl
    //         );

    //         throw $exception;
    //     }
    // }
// </testCanGetCardDetails>

// <blockAccount>
// não tem testes ainda
// </blockAccount>

// <getCardLimit>
// não tem testes ainda
// </getCardLimit>

// <updateCardLimit>
// não tem testes ainda
// </updateCardLimit>

// <createCardLimit>
// não tem testes ainda
// </createCardLimit>

// <listCards>
    // public function testCanListCards()
    // {
    //     $account = Account::factory()->personal()->create();

    //     $crRequest = [
    //         'idPessoa' => $account->holder->cr_person_id,
    //     ];

    //     $url = $this->caradhrasMainClient->getApiBaseUrl() . '/cartoes?' . http_build_query($crRequest);

    //     $response = [
    //         "message" => "Ok",
    //     ];

    //     Http::fake([
    //         $url => Http::response($response, 200),
    //     ]);

    //     $request = $this->caradhrasMainClient->listCards($crRequest);

    //     $this->assertEquals($request->message, 'Ok');

    //     Http::assertSent(
    //         fn (Request $request) => $request->url() === $url &&
    //             $request->method() === 'GET'
    //     );
    // }

    // public function testFailedToListCardsThrowException()
    // {
    //     $account = Account::factory()->personal()->create();

    //     $crRequest = [
    //         'idPessoa' => $account->holder->cr_person_id,
    //     ];

    //     $url = $this->caradhrasMainClient->getApiBaseUrl() . '/cartoes?' . http_build_query($crRequest);

    //     $responseError = [
    //         "message" => "Error",
    //     ];

    //     Http::fake([
    //         $url => Http::response($responseError, 500),
    //     ]);

    //     $this->expectException(FindCardsException::class);
    //     $this->expectExceptionCode(404);
    //     $this->expectExceptionMessage(trans('errors.card.failed_find_cards'));

    //     try {
    //         $this->caradhrasMainClient->listCards($crRequest);
    //     } catch (Throwable $exception) {
    //         Http::assertSent(
    //             fn (Request $request) => $request->url() === $url &&
    //                 $request->method() === 'GET'
    //         );

    //         throw $exception;
    //     }
    // }
// </listCards>

// <validateCVV>
    // public function testValidateCVVThrowsException()
    // {
    //     /** @var Card $card */
    //     $card = Card::factory()
    //         ->physical()
    //         ->status(CardStatus::Active)
    //         ->create();

    //     $fakeResponseError = [
    //         'timestamp' => '2021-05-18T13:57:00.874Z',
    //         'code' => 400,
    //         'exception' => 'BadRequestExceptionPIER',
    //         'message' => 'Erro de inconsistência de dados de criptografia do HSM',
    //         'path' => '/v2/api/cartoes/100/validar-cvv',
    //     ];

    //     $expectedRequestUrl = $this->caradhrasMainClient->getApiBaseUrl() . "/cartoes/{$card->cr_card_id}/validar-cvv";

    //     Http::fake([
    //         $expectedRequestUrl => Http::response($fakeResponseError, 400),
    //     ]);

    //     $this->expectException(CVVMismatchException::class);
    //     $this->expectExceptionCode(400);
    //     $this->expectExceptionMessage(trans('errors.card.cvv_mismatch'));

    //     $this->caradhrasMainClient->validateCVV($card->cr_card_id, 123);
    // }

    // /**
    //  * @throws CaradhrasException
    //  * @throws CVVMismatchException
    //  */
    // public function testCanResponseTrueOnValidateCVV(): void
    // {
    //     /** @var Card $card */
    //     $card = Card::factory()
    //         ->physical()
    //         ->status(CardStatus::Active)
    //         ->create();

    //     $fakeResponse = [];

    //     $expectedRequestUrl = $this->caradhrasMainClient->getApiBaseUrl() . "/cartoes/{$card->cr_card_id}/validar-cvv";

    //     Http::fake([
    //         $expectedRequestUrl => Http::response($fakeResponse, 200),
    //     ]);

    //     $response = $this->caradhrasMainClient->validateCVV($card->cr_card_id, 123);
    //     $this->assertTrue($response);
    // }
// </validateCVV>

// <cancelCard>
    // public function testCanCancelCard()
    // {
    //     Event::fake(CardUpdated::class);

    //     $card = Card::factory()->create([
    //         'account_id' => $this->account,
    //         'status' => CardStatus::Active,
    //     ]);

    //     $this->partialMock(CaradhrasMainClient::class, function ($mock) {
    //         $mock->shouldReceive('cancelCard')
    //             ->andReturn(new \App\Models\Caradhras\Card([
    //                 'idStatus' => CrCardStatus::Canceled->value,
    //             ]));
    //     });

    //     $request = $this->actingAs($this->user, 'api')
    //         ->deleteJson("/api/cards/{$card->id}");

    //     $request->assertSuccessful()
    //         ->assertJson([
    //             'id' => $card->id,
    //             'status' => CardStatus::Canceled->value,
    //         ]);

    //     $this->assertDatabaseHas('cards', [
    //         'id' => $card->id,
    //         'status' => CardStatus::Canceled->value,
    //     ]);

    //     Event::assertDispatched(CardUpdated::class);
    // }
// </cancelCard>

// <createIndividual>
// não tem testes ainda
// </createIndividual>

// <linkAccountAdditional>
// não tem testes ainda
// </linkAccountAdditional>

// <createAddress>
// não tem testes ainda
// </createAddress>

// <getPendingAccountDocuments>
// não tem testes ainda
// </getPendingAccountDocuments>

// <createPhoneRecharge>
// não tem testes ainda
// </createPhoneRecharge>

// <>
// não tem testes ainda
// </>

// <>
// não tem testes ainda
// </>

// <>
// não tem testes ainda
// </>

// <>
// não tem testes ainda
// </>

// <>
// não tem testes ainda
// </>

// <>
// não tem testes ainda
// </>

// <>
// não tem testes ainda
// </>

// <>
// não tem testes ainda
// </>

// <>
// não tem testes ainda
// </>

// <>
// não tem testes ainda
// </>

// <>
// não tem testes ainda
// </>

// <>
// não tem testes ainda
// </>

// <>
// não tem testes ainda
// </>

// <>
// não tem testes ainda
// </>
}
