<?php

namespace Idez\Caradhras\Exceptions;

class FailedCreatePersonalAccount extends BaseException
{
    public const INVALID_DOCUMENT = 'invalid_document';
    public const IRREGULAR_DOCUMENT = 'irregular_document';
    public const INCORRECT_NAME = 'incorrect_name';
    public const INCORRECT_MOTHER_NAME = 'incorrect_mother_name';
    public const INCORRECT_BIRTH_DATE = 'incorrect_birth_date';
    public const SANCTIONED_DOCUMENT = 'sanctioned_document';
    public const BLACK_LIST_DOCUMENT = 'black_list_document';

    public const CARADHRAS_MESSAGE_CODES = [
        1000 => self::INVALID_DOCUMENT,
        1001 => self::IRREGULAR_DOCUMENT,
        1002 => self::INCORRECT_NAME,
        1003 => self::INCORRECT_MOTHER_NAME,
        1004 => self::INCORRECT_BIRTH_DATE,
        1005 => self::SANCTIONED_DOCUMENT,
        1006 => self::BLACK_LIST_DOCUMENT,
    ];

    public function __construct(array $caradhrasError, int $statusCode = 400)
    {
        $errorKey = 'caradhras.account.something_went_wrong';

        if (isset($caradhrasError['message'])) {
            $errorKey = 'caradhras.account.validation_error';
            $message = $this->getErrorTranslation($caradhrasError['message']);
        } elseif ($statusCode === 409) {
            $caradhrasErrorCode = $caradhrasError['code'];
            $errorKey = 'caradhras.account.' . self::CARADHRAS_MESSAGE_CODES[$caradhrasErrorCode];
            $statusCode = 412;
        }

        $message = $message ?? trans($errorKey);

        if (isset($caradhrasError['erros'])) {
            $data = $this->formatErrors($caradhrasError['erros']);
        }

        parent::__construct($message, $statusCode, $errorKey, $data ?? []);
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
