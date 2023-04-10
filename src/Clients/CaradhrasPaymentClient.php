<?php

namespace Idez\Caradhras\Clients;

use Exception;
use Idez\Caradhras\Exceptions\Payments\ExpirationDateException;
use Idez\Caradhras\Exceptions\Payments\InvalidAmountException;
use Idez\Caradhras\Exceptions\Payments\InvalidPaymentBarcodeException;
use Idez\Caradhras\Exceptions\Payments\NotAllowedNowException;
use Idez\Caradhras\Exceptions\Payments\NotRegisteredAtCipException;
use Idez\Caradhras\Exceptions\Payments\PaidOrUnregisteredException;
use Idez\Caradhras\Exceptions\Payments\PaymentTimeoutException;
use Idez\Caradhras\Exceptions\Payments\ValidatePaymentException;
use Illuminate\Http\Client\PendingRequest;

class CaradhrasPaymentClient extends BaseApiClient
{
    public const API_PREFIX = 'payments';

    public const DEADLINE_EXCEEDED_ERROR = 'Barcode had its payment deadline exceeded';
    public const FAILED_TO_PROCESS_ERROR = 'Failed to process barcode validation. Unregistered barcode or payment already paid';
    public const BARCODE_UNREGISTERED_ERROR = 'Barcode unregistered, cancelled or payment already paid';
    public const BANK_NOT_REGISTERED_AT_CIP_ERROR = 'Bank not registered at CIP';
    public const BARCODE_NOT_REGISTERED_AT_CIP_ERROR = 'Barcode not registered at CIP';
    public const INVALID_AMOUNT_ERROR = 'Payment amount submitted differs from value recorded for barcode';
    public const EXPIRATION_DATE_EXCEEDED_ERROR = 'Barcode had its payment expiration date exceeded';
    public const NOT_ALLOWED_NOW_ERROR = 'Payment request not allowed for this time';
    public const ALREADY_PROCESSED_ERROR = 'This payment slip was already processed and can no longer be paid';
    public const EXTERNAL_FAILURE_ERROR = 'External failure while executing transaction';
    public const TIMEOUT_ERROR = 'Timeout expired while executing transaction';

    /**
     * Validate a payment barcode.
     *
     * @param  string  $barcode
     * @return object
     * @throws ExpirationDateException
     * @throws InvalidAmountException
     * @throws InvalidPaymentBarcodeException
     * @throws NotAllowedNowException
     * @throws NotRegisteredAtCipException
     * @throws PaidOrUnregisteredException
     * @throws ValidatePaymentException
     * @throws ValidatePaymentException
     * @throws PaymentTimeoutException
     */
    public function validate(string $barcode): object
    {
        $barcode = preg_replace('/[^0-9]/i', '', $barcode);
        $response = $this->apiClient(false)
            ->retry(5, 2000, fn (Exception $exception, PendingRequest $request) => $exception->getCode() === 503, false)
            ->get("/v1/validate/{$barcode}");

        if ($response->failed()) {
            throw match ($response->status()) {
                400 => new InvalidPaymentBarcodeException(),
                409 => match ($response->json('message')) {
                    self::DEADLINE_EXCEEDED_ERROR => new ExpirationDateException(),
                    self::FAILED_TO_PROCESS_ERROR, self::BARCODE_UNREGISTERED_ERROR => new PaidOrUnregisteredException(),
                    default => new ValidatePaymentException(),
                },
                422 => match ($response->json('message')) {
                    self::BARCODE_NOT_REGISTERED_AT_CIP_ERROR,
                    self::BANK_NOT_REGISTERED_AT_CIP_ERROR => new NotRegisteredAtCipException(),
                    self::INVALID_AMOUNT_ERROR => new InvalidAmountException(),
                    self::EXPIRATION_DATE_EXCEEDED_ERROR => new ExpirationDateException(),
                    self::NOT_ALLOWED_NOW_ERROR => new NotAllowedNowException(),
                    self::ALREADY_PROCESSED_ERROR => new PaidOrUnregisteredException(),
                    default => new ValidatePaymentException(),
                },
                504 => new PaymentTimeoutException(),
                default => new ValidatePaymentException(),
            };
        }

        return $response->object();
    }

    /**
     * Create payment.
     *
     * @param  int  $accountId
     * @param  string  $barCodeNumber
     * @param  \Carbon\CarbonInterface  $dueDate
     * @param  string  $assignor
     * @param  float  $discount
     * @param  float  $interest
     * @param  float  $fine
     * @param  float  $amount
     * @param  string  $description
     * @return object
     */
    public function create(
        int $accountId,
        string $barCodeNumber,
        \Carbon\CarbonInterface $dueDate,
        string $assignor,
        float $discount,
        float $interest,
        float $fine,
        float $amount,
        string $description
    ): object {
        $response = $this->apiClient(false)
            ->asJson()
            ->retry(5, 2000, fn (Exception $exception, PendingRequest $request) => in_array($exception->getCode(), [424, 422, 409]), false)
            ->post('/v1', [
                'idAccount' => $accountId,
                'barCodeNumber' => $barCodeNumber,
                'dueDate' => $dueDate->clone()->setTime(0, 0)->toIso8601String(),
                'assignor' => $assignor,
                'discount' => $discount,
                'interest' => $interest,
                'fine' => $fine,
                'amount' => $amount,
                'description' => $description,
            ]);

        return $response->object();
    }

    /**
     * Search payments
     *
     * @param  array  $filters
     * @return object
     */
    public function search(array $filters): object
    {
        $response = $this->apiClient()
            ->asJson()
            ->get('/v1/receipts/', $filters);

        return $response->object();
    }
}
