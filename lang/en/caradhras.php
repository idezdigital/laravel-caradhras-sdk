<?php

return [
    'account' => [
        'not_found' => 'Could not find the desired account',
        'invalid_document' => 'The document entered is invalid.',
        'irregular_document' => 'The reported document is irregular.',
        'incorrect_name' => 'The name is incorrect.',
        'incorrect_mother_name' => 'The mother\'s name is incorrect.',
        'incorrect_birth_date' => 'The date of birth is incorrect.',
        'sanctioned_document' => 'The informed document is not allowed (sanctions).',
        'black_list_document' => 'The informed document is blocked.',
        'something_went_wrong' => 'Something went wrong when creating your account. Please check the data provided.',
        'validation' => [
            // PJ
            'invalid_request' => 'Something went wrong when creating your account. Please check the data provided.',
            'there_is_already_an_attempt_in_progress_to_register_this_company' => 'There is already an attempt in progress to register this company.',
            // PF
            'areacode_is_invalid_must_be_0xx' => 'Area code is invalid. Must be 0xx.',
            'birthdate_is_invalid_must_be_in_the_format_yyyy-mm-dd' => 'Birth date is invalid. Must be in the format yyyy-mm-dd.',
            'birthdate_is_invalid_must_not_be_a_future_date' => 'Birth date is invalid. Must not be a future date.',
            'birthdate_must_have_a_length_of_10_in_the_format_yyyy-mm-dd' => 'Birth date must have a length of 10 in the format yyyy-mm-dd.',
            'document_is_invalid_must_be_only_numbers_and_length_11' => 'Document is invalid. Must be only numbers and length 11.',
            'document_must_have_a_length_of_11' => 'Document is invalid. Must be only numbers and length 11.',
            'duedate_must_be_of_type_number' => 'Due date must be of type number.',
            'fingerprint_in_deviceidentification_is_required' => 'Fingerprint in device identification is required.',
            'idaddresstype_is_invalid' => 'ID address type is invalid.',
            'idphonetype_is_invalid' => 'ID phone type is invalid.',
            'issuingdateidentity_is_invalid_must_be_in_the_format_yyyy-mm-dd' => 'Issuing date identity is invalid. Must be in the format yyyy-mm-dd.',
            'issuingdateidentity_is_invalid_must_not_be_a_future_date' => 'Issuing date identity is invalid. Must not be a future date.',
            'issuingdateidentity_must_have_a_length_of_10_in_the_format_yyyy-mm-dd' => 'Issuing date identity must have a length of 10 in the format yyyy-mm-dd.',
            'mothername_must_have_only_letters' => 'Mother name must have only letters.',
            'neighborhood_must_have_a_max_length_of_40_characters' => 'Neighborhood must have a max length of 40 characters.',
            'number_is_invalid_cell_phones_must_start_with_number_9' => 'Number is invalid. Cell phones must start with number 9.',
            'number_must_have_a_length_between_8_and_9_numbers' => 'Number must have a length between 8 and 9 numbers.',
            'street_must_have_a_max_length_of_40_characters' => 'Street must have a max length of 40 characters.',
            'zipcode_must_have_a_length_of_8_numbers' => 'Zipcode must have a length of 8 numbers.',
            'name_must_have_only_letters' => 'Name must have only letters.',
        ],
    ],
    'alias_bank' => [
        'created' => 'Alias Bank successfully created for provider :provider',
        'something_went_wrong' => 'Something went wrong. Please check the data provided.',
    ],
    'card_not_printed' => 'Invalid card status for activation.',
    'bank_transfer' => [
        'not_found' => "We didn't find this transfer, please check your details and try again.",
        'empty_list' => "There are no records on this page.",
        'action_not_allowed' => "We were unable to complete your transfer now, please try again later.",
        'default' => "There was an instability in the provider, please try again later.",
    ],
];
