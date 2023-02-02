<?php

namespace Idez\Caradhras\Exceptions;

use Idez\Caradhras\Enums\MessageCode;

class FailedCreatePersonalAccount extends BaseException implements ExceptionLevel
{
    public function __construct(array $caradhrasError, int $statusCode = 400)
    {
        $errorKey = 'caradhras.account.something_went_wrong';

        $this->setsErrorDataifMessageAlreadySet;

        $errorKey = $this->setsErrorDataifMessageAlreadySet['errorKey'] ?? '';
        $message = $this->setsErrorDataifMessageAlreadySet['message'] ?? '';

        $this->setsErrorDataIfMessageIsntSetAndThereIsConflict;

        $errorKey = $errorKey ?? $this->setsErrorDataIfMessageIsntSetAndThereIsConflict['errorKey'];
        $statusCode = $statusCode ?? $this->setsErrorDataIfMessageIsntSetAndThereIsConflict['statusCode'];

        $message = $message ?? trans($errorKey);

        if (isset($caradhrasError['erros'])) {
            $data = $this->formatErrors($caradhrasError['erros']);
        }

        parent::__construct($message, $statusCode, $errorKey, $data ?? []);
    }

    public function setsErrorDataifMessageAlreadySet(array $caradhrasErrorMessage): array
    {
        if (isset($caradhrasErrorMessage)) {
            $errorKey = 'caradhras.account.validation_error';
            $message = $this->getErrorTranslation($caradhrasErrorMessage);

            return ['errorKey' => $errorKey, 'message' => $message];
        }

        return ['errorKey' => '', 'message' => ''];
    }

    public function setsErrorDataIfMessageIsntSetAndThereIsConflict(array $caradhrasError, int $statusCode): array
    {
        if ($statusCode === 409 && ! isset($caradhrasError['message'])) {
            $caradhrasErrorCode = $caradhrasError['code'];
            $errorKey = 'caradhras.account.' . MessageCode::caseByCode($caradhrasErrorCode)->value;
            $statusCode = 412;

            return ['errorKey' => $errorKey, '$statusCode' => $statusCode];
        }

        return ['errorKey' => '', '$statusCode' => ''];
    }

    /**
     * Format Caradhras Errors Response for personal accounts
     *
     * @var array
     * @return array
     */
    private function formatErrors(array $errors)
    {
        $formattedErrors = [];

        foreach ($errors as $error) {
            $formattedErrors[$error['field']] = $error['defaultMessage'];
        }

        return $formattedErrors;
    }

    /**
     * Get error translation
     *
     * @var string
     * @return string
     */
    private function getErrorTranslation(string $errorMessage): string
    {
        $originalErrorSanitized = str_replace('.', '', strtolower($errorMessage));
        $originalErrorSanitized = str_replace(' ', '_', $originalErrorSanitized);

        $errorKey = 'caradhras.account.validation.' . $originalErrorSanitized;
        $errorTranslation = trans($errorKey);

        // return the same message if not found translation
        if ($errorKey === $errorTranslation) {
            return $errorMessage;
        }

        return $errorTranslation;
    }
}
