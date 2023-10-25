<?php

namespace Idez\Caradhras\Clients;

use Carbon\CarbonInterface;
use Idez\Caradhras\Data\InvoicePaymentSlip;
use Idez\Caradhras\Data\InvoicePaymentSlipDiscount;
use Idez\Caradhras\Data\InvoicePaymentSlipFine;
use Idez\Caradhras\Data\InvoicePaymentSlipInterest;
use Idez\Caradhras\Data\InvoicePaymentSlipPayer;
use Idez\Caradhras\Data\RechargePaymentSlip;
use Idez\Caradhras\Enums\PaymentSlip\PaymentSlipInvoiceType;
use Idez\Caradhras\Enums\PaymentSlip\PaymentSlipType;
use Idez\Caradhras\Exceptions\CaradhrasException;
use Idez\Caradhras\Exceptions\PaymentSlip\CreatePaymentSlipException;
use Idez\Caradhras\Exceptions\PaymentSlip\GetPaymentSlipPdfException;
use Idez\Caradhras\Exceptions\PaymentSlip\PaymentSlipExpiredException;
use Idez\Caradhras\Exceptions\PaymentSlip\PaymentSlipNotFoundException;
use Illuminate\Support\Str;

class CaradhrasPaymentSlipClient extends BaseApiClient
{
    public const API_PREFIX = 'paymentslip';

    public const DEFAULT_BANK_NUMBER = 301;

    public const PAYMENT_SLIP_EXPIRED_ERROR = 'Payment slip expired';

    /**
     * Create a payment slip of type recharge.
     *
     * @param  int  $accountId
     * @param  float  $amount
     * @param  CarbonInterface  $dueDate
     * @return RechargePaymentSlip
     *
     * @throws CreatePaymentSlipException
     */
    public function createRecharge(int $accountId, float $amount, CarbonInterface $dueDate): RechargePaymentSlip
    {
        $response = $this->apiClient(false)->post('/v1/recharge', [
            'idAccount' => $accountId,
            'amount' => $amount,
            'dueDate' => $dueDate->toDateString(),
            'bankNumber' => self::DEFAULT_BANK_NUMBER,
        ]);

        if ($response->failed()) {
            throw new CreatePaymentSlipException($response);
        }

        return new RechargePaymentSlip($response->object());
    }

    /**
     * Get the payment slip PDF.
     *
     * @param  string  $uniqueId
     * @return string
     *
     * @throws PaymentSlipExpiredException
     * @throws GetPaymentSlipPdfException
     */
    public function getPdf(string $uniqueId): string
    {
        $response = $this->apiClient(false)->get('/v1/pdf', [
            'uniqueId' => $uniqueId,
        ]);

        if ($response->failed()) {
            $message = $response->json('message', '');
            if (Str::contains($message, self::PAYMENT_SLIP_EXPIRED_ERROR)) {
                throw new PaymentSlipExpiredException();
            }

            throw new GetPaymentSlipPdfException($response);
        }

        return $response->body();
    }

    /**
     * Find a payment slip.
     *
     * @param  int  $accountId
     * @param  string  $paymentSlipUniqueId
     * @return RechargePaymentSlip|InvoicePaymentSlip
     *
     * @throws \Idez\Caradhras\Exceptions\PaymentSlip\PaymentSlipNotFoundException
     */
    public function find(int $accountId, string $paymentSlipUniqueId): InvoicePaymentSlip|RechargePaymentSlip
    {
        $response = $this->apiClient()->get('/v1', [
            'idAccount' => $accountId,
            'uniqueId' => $paymentSlipUniqueId,
        ]);

        $result = head($response->object()->items ?? []);

        if (! $result) {
            throw new PaymentSlipNotFoundException();
        }

        return match (PaymentSlipType::from($result->type)) {
            PaymentSlipType::Invoice => new InvoicePaymentSlip($result),
            PaymentSlipType::Recharge => new RechargePaymentSlip($result),
        };
    }

    /**
     * Get a payment slip using their adjustment id.
     *
     * @param  string  $adjustmentId
     * @return RechargePaymentSlip|InvoicePaymentSlip
     *
     * @throws PaymentSlipNotFoundException
     */
    public function getByAdjustmentId(string $adjustmentId): InvoicePaymentSlip|RechargePaymentSlip
    {
        $response = $this->apiClient()->get('/v1', [
            'idAdjustment' => $adjustmentId,
        ]);

        $result = head($response->object()->items ?? []);

        if (! $result) {
            throw new PaymentSlipNotFoundException();
        }

        return match (PaymentSlipType::from($result->type)) {
            PaymentSlipType::Invoice => new InvoicePaymentSlip($result),
            PaymentSlipType::Recharge => new RechargePaymentSlip($result),
        };
    }

    /**
     * Create a payment slip of type invoice.
     *
     * @param  int  $accountId
     * @param  float  $amount
     * @param  CarbonInterface  $dueDate
     * @param  string  $invoiceTypeCode
     * @param  \Idez\Caradhras\Data\InvoicePaymentSlipPayer  $payer
     * @param  int  $deadline  (optional). Default 120.
     *
     * @param  null|InvoicePaymentSlipFine  $fine
     * @param  null|InvoicePaymentSlipDiscount  $discount
     * @param  null|InvoicePaymentSlipInterest  $interest
     *
     * @return InvoicePaymentSlip
     *
     * @throws \Idez\Caradhras\Exceptions\CaradhrasException
     * @throws \Idez\Caradhras\Exceptions\PaymentSlip\CreatePaymentSlipException
     */
    public function createInvoice(
        int $accountId,
        float $amount,
        CarbonInterface $dueDate,
        string $invoiceTypeCode,
        InvoicePaymentSlipPayer $payer,
        int $deadline = 120,
        InvoicePaymentSlipFine $fine = null,
        InvoicePaymentSlipDiscount $discount = null,
        InvoicePaymentSlipInterest $interest = null,
    ): InvoicePaymentSlip {
        if (! PaymentSlipInvoiceType::tryFrom($invoiceTypeCode)) {
            throw new CaradhrasException('Invalid invoice type code.', 400);
        }

        $requestData = [
            'idAccount' => $accountId,
            'bankNumber' => self::DEFAULT_BANK_NUMBER,
            'paymentslip' => [
                'type' => $invoiceTypeCode,
                'amount' => $amount,
                'dueDate' => $dueDate->toDateString(),
            ],
            'payer' => $payer->toArray(),
            'others' => [
                'acceptance' => 'A',
                'deadlineAutomaticCancellation' => $deadline,
            ],
        ];

        if ($fine) {
            $requestData['fine'] = $fine->jsonSerialize();
        }

        if ($discount) {
            $requestData['discount'] = $discount->jsonSerialize();
        }

        if ($interest) {
            $requestData['interest'] = $interest->jsonSerialize();
        }

        $response = $this->apiClient(false)->post('/v1/invoice', $requestData);

        if ($response->failed()) {
            throw new CreatePaymentSlipException($response);
        }

        return new InvoicePaymentSlip($response->object());
    }

    public function cancel(string $documentNumber): void
    {
        $this->apiClient()
            ->post("/v1/$documentNumber/write-off")
            ->throw();
    }
}
