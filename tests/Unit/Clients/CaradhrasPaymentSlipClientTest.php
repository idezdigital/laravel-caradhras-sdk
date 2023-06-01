<?php

namespace Idez\Caradhras\Tests\Unit\Clients;

use Carbon\Carbon;
use Idez\Caradhras\Clients\CaradhrasPaymentSlipClient;
use Idez\Caradhras\Data\InvoicePaymentSlip;
use Idez\Caradhras\Data\InvoicePaymentSlipPayer;
use Idez\Caradhras\Exceptions\CaradhrasException;
use Idez\Caradhras\Tests\TestCase;
use Illuminate\Support\Facades\Http;

class CaradhrasPaymentSlipClientTest extends TestCase
{
    private CaradhrasPaymentSlipClient $paymentSlipClient;

    protected function setUp(): void
    {
        parent::setUp();

        $this->paymentSlipClient = app(CaradhrasPaymentSlipClient::class);
    }

    public function testCreateInvoiceSuccess(): void
    {
        $accountId = 100;
        $amount = 50.00;
        $dueDate = Carbon::tomorrow();
        $invoiceTypeCode = '02';
        $payer = new InvoicePaymentSlipPayer();
        $beneficiaryType = 1;

        $expectedRequestUrl = $this->paymentSlipClient->getApiBaseUrl() . "/v1/invoice?beneficiaryType={$beneficiaryType}";

        Http::fake([
            $expectedRequestUrl => Http::response([
                'amount' => $amount,
                'dueDate' => $dueDate,
            ]),
        ]);

        $invoicePaymentSlip = $this->paymentSlipClient->createInvoice(
            accountId: $accountId,
            amount: $amount,
            dueDate: $dueDate,
            invoiceTypeCode: $invoiceTypeCode,
            payer: $payer,
            beneficiaryType: $beneficiaryType
        );

        $this->assertInstanceOf(InvoicePaymentSlip::class, $invoicePaymentSlip);
    }

    public function testCreateInvoiceWithInvalidBeneficiaryType(): void
    {
        $accountId = 100;
        $amount = 50.00;
        $dueDate = Carbon::tomorrow();
        $invoiceTypeCode = '02';
        $payer = new InvoicePaymentSlipPayer();
        $beneficiaryType = 3;

        $this->expectException(CaradhrasException::class);
        $this->expectExceptionMessage('Invalid beneficiary type.');
        $this->expectExceptionCode(400);

        $this->paymentSlipClient->createInvoice(
            accountId: $accountId,
            amount: $amount,
            dueDate: $dueDate,
            invoiceTypeCode: $invoiceTypeCode,
            payer: $payer,
            beneficiaryType: $beneficiaryType
        );
    }
}
