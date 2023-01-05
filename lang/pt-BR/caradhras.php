<?php

return [
    'account' => [
        'not_found' => 'Não foi possível encontrar a conta correspondente',
        'invalid_document' => 'O documento informado é inválido.',
        'irregular_document' => 'O documento informado está irregular.',
        'incorrect_name' => 'O nome está incorreto.',
        'incorrect_mother_name' => 'O nome da mãe está incorreto.',
        'incorrect_birth_date' => 'A data de nascimento está incorreta.',
        'sanctioned_document' => 'O documento informado não é permitido (sanções).',
        'black_list_document' => 'O documento informado está bloqueado.',
        'something_went_wrong' => 'Algo deu errado ao criar sua conta. Por favor, verifique os dados informados.',
        'validation' => [
            // PJ
            'invalid_request' => 'Algo deu errado ao criar sua conta. Por favor, verifique os dados informados.',
            'there_is_already_an_attempt_in_progress_to_register_this_company' => 'Já existe uma tentativa em andamento de registrar esta empresa.',
            // PF
            'areacode_is_invalid_must_be_0xx' => 'O código de área é inválido. Deve ser 0xx.',
            'birthdate_is_invalid_must_be_in_the_format_yyyy-mm-dd' => 'A data de nascimento é inválida. Deve estar no formato aaaa-mm-dd.',
            'birthdate_is_invalid_must_not_be_a_future_date' => 'A data de nascimento é inválida. Não deve ser uma data futura.',
            'birthdate_must_have_a_length_of_10_in_the_format_yyyy-mm-dd' => 'A data de nascimento deve ter 10 caracteres no formato aaaa-mm-dd.',
            'document_is_invalid_must_be_only_numbers_and_length_11' => 'O documento é inválido. Deve ser apenas números e comprimento 11. ',
            'document_must_have_a_length_of_11' => 'O documento é inválido. Deve ser apenas números e comprimento 11. ',
            'duedate_must_be_of_type_number' => 'A data de vencimento deve ser do tipo número.',
            'fingerprint_in_deviceidentification_is_required' => 'Impressão digital na identificação do dispositivo é necessária.',
            'idaddresstype_is_invalid' => 'Tipo de endereço de identificação inválido.',
            'idphonetype_is_invalid' => 'Tipo de telefone de identificação inválido.',
            'issuingdateidentity_is_invalid_must_be_in_the_format_yyyy-mm-dd' => 'A data de emissão da identidade é inválida. Deve estar no formato aaaa-mm-dd.',
            'issuingdateidentity_is_invalid_must_not_be_a_future_date' => 'A data de emissão da identidade é inválida. Não deve ser uma data futura.',
            'issuingdateidentity_must_have_a_length_of_10_in_the_format_yyyy-mm-dd' => 'A data de emissão da identidade deve ter um comprimento de 10 no formato aaaa-mm-dd.',
            'mothername_must_have_only_letters' => 'O nome da mãe deve ter apenas letras.',
            'neighborhood_must_have_a_max_length_of_40_characters' => 'O nome do bairro deve ter no máximo 40 caracteres.',
            'number_is_invalid_cell_phones_must_start_with_number_9' => 'Número inválido. Os telefones celulares devem começar com o número 9. ',
            'number_must_have_a_length_between_8_and_9_numbers' => 'O número deve ter um comprimento entre 8 e 9 números.',
            'street_must_have_a_max_length_of_40_characters' => "A rua deve ter no máximo 40 caracteres.",
            'zipcode_must_have_a_length_of_8_numbers' => 'O CEP deve ter um comprimento de 8 números.',
            'name_must_have_only_letters' => 'O nome deve ter apenas letras.',
        ],
    ],
    'alias_bank' => [
        'created' => 'Criada conta Alias para o banco código :provider.',
        'something_went_wrong' => 'Algo deu errado ao criar sua conta. Por favor, verifique os dados informados.',
    ],
    'card_not_printed' => 'Status do cartão inválido para ativação.',
    'bank_transfer' => [
        'not_found' => 'Não encontramos essa transferência, verifique seus dados e tente novamente.',
        'empty_list' => 'Não existem registros nessa página.',
        'action_not_allowed' => 'Não foi possível realizar sua transferência agora, por favor, tente novamente mais tarde.',
        'default' => 'Houve uma instabilidade no provedor, por favor, tente novamente mais tarde.'
    ],
];
