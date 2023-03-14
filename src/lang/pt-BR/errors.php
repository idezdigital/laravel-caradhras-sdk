<?php

return [
    'services' => [
        'caradhras' => [
            'update_address_failed' => 'Falha ao tentar atualizar o documento.',
            'generic_error' => 'Ocorreu um erro no nosso provedor externo, caso o erro permaneça contate nosso suporte.',
            'card' => [
                'lock_failed' => 'Falha ao bloquear este cartão, verifique seus dados e tente novamente.',
            ],
        ],
    ],

    'phone-recharge' => [
        'order' => 'Falha ao criar a ordem de recarga.',
        'confirm' => 'Falha ao confirmar a recarga telefônica.',
    ],

    'card' => [
        'unlock_failed' => 'Ocorreu um erro ao desbloquear o cartão.',
        'cvv_mismatch' => 'O CVV informado está incorreto.',
        'failed_get_details' => 'Ocorreu um erro ao obter os dados do cartão.',
        'undefined_limit' => 'Não há limite criado para este cartão.',
        'not_issued' => 'Esse cartão não foi emitido.',
        'failed_request_card_batch' => 'Houve uma falha ao solicitar seu lote de cartões.',
        'failed_find_cards' => 'Houve uma falha ao buscar os cartões.',
        'not_printed' => 'Status de cartão inválido para ativação.',
    ],

    'bank-transfer' => [
        'not_found' => 'Não encontramos essa transferência, verifique seus dados e tente novamente.',
        'empty_list' => 'Não existem registros nessa página.',
        'action_not_allowed' => 'Não foi possível realizar sua transferência agora, por favor, tente novamente mais tarde.',
        'default' => 'Houve uma instabilidade no provedor, por favor, tente novamente mais tarde.'
    ],

    'fraud_detection' => 'Transação não autorizada pelo motor anti-fraude.',
    'insufficient_balance_to_transaction' => 'Saldo insuficiente para realizar a transação.',
    'generic' => 'Ocorreu um erro ao realizar a requisição.',
];
