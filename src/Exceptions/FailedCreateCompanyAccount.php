<?php

namespace Idez\Caradhras\Exceptions;

class FailedCreateCompanyAccount extends CaradhrasException
{
    public array $data = [];

    public function __construct(array $rawCaradhrasErrors = [])
    {
        $errorKey = 'caradhras.account.something_went_wrong';

        if (isset($rawCaradhrasErrors['message'])) {
            $errorKey = 'caradhras.account.validation_error';
            $message = $this->getErrorTranslation($rawCaradhrasErrors['message']);
        }

        if (isset($rawCaradhrasErrors['errors'])) {
            $formatedRawErrors = $this->getFormattedRawErrors($rawCaradhrasErrors['errors']);
            $this->data = $this->getValidationErrors($formatedRawErrors);
        }

        parent::__construct($message ?? trans($errorKey), 400);
    }

    /**
     * Format Caradhras Errors Response for company accounts
     */
    private function getFormattedRawErrors(array $errors): array
    {
        $formattedErrors = [];

        foreach ($errors as $error) {
            $level = str_replace('/', '.', $error['level']);

            $problem = $error['problems'];
            $field = $level.'.'.$problem['field'];
            $formattedErrors[$field] = $problem['value'];
        }

        return $formattedErrors;
    }

    /**
     * Get the validation errors.
     */
    private function getValidationErrors(array $formatedRawErrors): array
    {
        $mapedErrorsToLocalFieldName = [
            'legalName' => 'legal_name',
            'legalNature' => 'legal_nature',
            'tradeName' => 'name',
            'dateEstablishment' => 'establishment_date',
            'establishmentFormat' => 'establishment_type',
            'mainCnae' => 'main_cnae',
            'revenue' => 'revenue',

            'nationalRegistration' => 'document',
            'email' => 'email',

            'name' => 'name',
            'cpf' => 'document',
            'dateBirth' => 'birth_date',
            'motherName' => 'mother_name',

            'isPep' => 'is_pep',

            'mainPhone' => [
                'countryCode' => 'phone',
                'area' => 'phone',
                'number' => 'phone',
                'type' => 'phone',
            ],

            'mainAddress' => [
                'zip' => 'address.postal_code',
                'number' => 'address.number',
                'complement' => 'address.extra',
                'street' => 'address.street',
                'neighborhood' => 'address.neighborhood',
                'city' => 'address.city',
                'state' => 'address.state',
            ],
        ];

        $validationErrors = [];

        foreach ($formatedRawErrors as $crErrorField => $value) {
            $localFieldName = '';

            // Default handle for multiple partners.
            if (str_starts_with($crErrorField, 'individuals.')) {
                $totalPartners = count(request()->input('partners', []));

                if ($totalPartners > 1) {
                    if (! isset($validationErrors['partners.0.name'])) {
                        for ($i = 0; $i < $totalPartners; $i++) {
                            $validationErrors["partners.{$i}.name"] = ['Por favor, verifique os dados pessoais deste sócio.'];
                        }
                    }

                    continue;
                }

                $localFieldName .= 'partners.0.';
            }

            // Removes prefix from cr error field.
            $crErrorField = preg_replace('/(company|individuals)\./', '', $crErrorField);

            $localFieldName .= data_get($mapedErrorsToLocalFieldName, $crErrorField);

            if (! $localFieldName) {
                continue;
            }

            // Get translated field name
            $translatedFieldName = trans('validation.attributes.'.last(explode('.', $localFieldName)));

            // Add the field error to validation messages.
            $validationErrors[$localFieldName] = ["O campo {$translatedFieldName} não é válido."];
        }

        return $validationErrors;
    }

    /**
     * Get error translation
     *
     * @var string
     */
    private function getErrorTranslation(string $errorMessage): string
    {
        $originalErrorSanitized = str_replace('.', '', strtolower($errorMessage));
        $originalErrorSanitized = str_replace(' ', '_', $originalErrorSanitized);

        $errorKey = 'caradhras.account.validation.'.$originalErrorSanitized;
        $errorTranslation = trans($errorKey);

        // return the same message if not found translation
        if ($errorKey === $errorTranslation) {
            return $errorMessage;
        }

        return $errorTranslation;
    }
}
