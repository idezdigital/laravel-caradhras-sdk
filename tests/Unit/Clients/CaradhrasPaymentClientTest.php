<?php

namespace Idez\Caradhras\Tests\Unit\Clients;

use Idez\Caradhras\Clients\CaradhrasPaymentClient;
use Idez\Caradhras\Exceptions\Payments\InvalidPaymentBarcodeException;
use Idez\Caradhras\Exceptions\Payments\NotRegisteredAtCipException;
use Idez\Caradhras\Exceptions\Payments\PaidOrUnregisteredException;
use Idez\Caradhras\Exceptions\Payments\PaymentParseException;
use Idez\Caradhras\Exceptions\Payments\PaymentTimeoutException;
use Idez\Caradhras\Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\Client\Request;
use Illuminate\Support\Facades\Http;

class CaradhrasPaymentClientTest extends TestCase
{
    use WithFaker;

    private CaradhrasPaymentClient $paymentClient;

    protected function setUp(): void
    {
        parent::setUp();
        $this->paymentClient = app(CaradhrasPaymentClient::class);
    }

    public function testCanValidateAPaymentWithValidBarcode()
    {
        $barcode = $this->faker->regexify('\d{48}');

        $expectedRequestUrl = $this->paymentClient->getApiBaseUrl() . "/v1/validate/{$barcode}";

        $fakeResponse = [
            "Barcode" => $barcode,
            "Result" => [
                "Success" => true,
                "BillPaymentTypeId" => 2,
                "BillPaymentTypeDescription" => "Normal",
                "HasEnoughBalance" => true,
                "WasPaid" => false,
                "PaymentSchedulingDate" => "N\/A",
                "ReachedLimit" => false,
                "ValidateBarCode" => [
                    "Id" => $this->faker->randomNumber(3),
                    "Description" => $this->faker->company(),
                    "Value" => $this->faker->randomFloat(2, 10, 100),
                    "DueDate" => "2020-04-29",
                    "IsOutTime" => false,
                    "TimeWindow" => 0,
                    "MinTime" => "07:30h",
                    "MaxTime" => "20:30h",
                    "IsDefaultTime" => true,
                    "PaymentType" => 0,
                    "HasDueDate" => false,
                    "BarCodeNumber" => $barcode,
                    "CurrentDate" => today()->toDateString(),
                ],
                "PaymentInfoNPC" => [],
            ],
        ];

        Http::fake([
            $expectedRequestUrl => Http::response($fakeResponse),
        ]);

        $validateResponse = $this->paymentClient->validate($barcode);

        $this->assertIsObject($validateResponse);
        $this->assertEquals(
            json_decode(json_encode($fakeResponse)),
            $validateResponse
        );

        Http::assertSent(
            fn (Request $request) => $request->url() === $expectedRequestUrl
        );
    }

    public function testCannotValidateAPaymentWithInvalidBarcode()
    {
        $barcode = $this->faker->randomElement([
            $this->faker->regexify('\d{34}'),
            $this->faker->regexify('\d{61}'),
        ]);

        $expectedRequestUrl = $this->paymentClient->getApiBaseUrl() . "/v1/validate/{$barcode}";

        Http::fake([
            $expectedRequestUrl => Http::response([], 400),
        ]);

        $this->expectException(InvalidPaymentBarcodeException::class);
        $this->expectExceptionMessage(trans('errors.payments.invalid_barcode'));
        $this->expectExceptionCode(400);

        $this->paymentClient->validate($barcode);
    }

    public function testHandlePaidOrUnregisteredException()
    {
        $barcode = $this->faker->regexify('\d{48}');
        $expectedRequestUrl = $this->paymentClient->getApiBaseUrl() . "/v1/validate/{$barcode}";

        Http::fake([
            $expectedRequestUrl => Http::response([
                "message" => $this->paymentClient::FAILED_TO_PROCESS_ERROR,
                "uuid" => $this->faker->uuid(),
            ], 409),
        ]);

        $this->expectException(PaidOrUnregisteredException::class);
        $this->expectExceptionMessage(trans('errors.payments.unregistered_barcode_or_already_paid'));
        $this->expectExceptionCode(502);

        $this->paymentClient->validate($barcode);
    }

    public function testHandleNotRegisteredAtCipException()
    {
        $barcode = $this->faker->regexify('\d{48}');
        $expectedRequestUrl = $this->paymentClient->getApiBaseUrl() . "/v1/validate/{$barcode}";

        Http::fake([
            $expectedRequestUrl => Http::response([
                "message" => $this->paymentClient::BARCODE_NOT_REGISTERED_AT_CIP_ERROR,
                "uuid" => $this->faker->uuid(),
            ], 422),
        ]);

        $this->expectException(NotRegisteredAtCipException::class);
        $this->expectExceptionMessage(trans('errors.payments.not_registered_at_cip'));
        $this->expectExceptionCode(502);

        $this->paymentClient->validate($barcode);
    }

    public function testHandlePaymentParseException()
    {
        $barcode = $this->faker->regexify('\d{48}');
        $expectedRequestUrl = $this->paymentClient->getApiBaseUrl() . "/v1/validate/{$barcode}";

        Http::fake([
            $expectedRequestUrl => Http::response([
                "message" => $this->paymentClient::EXTERNAL_FAILURE_ERROR,
                "uuid" => $this->faker->uuid(),
            ], 424),
        ]);

        $this->expectException(PaymentParseException::class);
        $this->expectExceptionMessage(trans('errors.payments.failed_to_parse'));
        $this->expectExceptionCode(502);

        $this->paymentClient->validate($barcode);
    }

    public function testHandlePaymentTimeoutException()
    {
        $barcode = $this->faker->regexify('\d{48}');
        $expectedRequestUrl = $this->paymentClient->getApiBaseUrl() . "/v1/validate/{$barcode}";

        Http::fake([
            $expectedRequestUrl => Http::response([
                "message" => $this->paymentClient::TIMEOUT_ERROR,
                "uuid" => $this->faker->uuid(),
            ], 504),
        ]);

        $this->expectException(PaymentTimeoutException::class);
        $this->expectExceptionMessage(trans('errors.payments.timeout'));
        $this->expectExceptionCode(504);

        $this->paymentClient->validate($barcode);
    }
}
