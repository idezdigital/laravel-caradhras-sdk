<?php

return [
    'api' => [
        'generic_error' => 'An error occurred while executing the request.',
    ],

    'services' => [
        'caradhras' => [
            'update_address_failed' => 'Failed to try to update the document.',
            'generic_error' => 'An error occurred in our external provider, if the error remains contact our support.',
            'card' => [
                'lock_failed' => 'Failed to lock this card, please check your data and try again.',
            ],
        ],
    ],

    'phone-recharge' => [
        'order' => 'Failed to Order a phone request.',
        'confirm' => 'Failed to confirm a phone request.',
    ],

    'card' => [
        'unlock_failed' => 'There was an error unlocking the card.',
        'cvv_mismatch' => 'The informed CVV is not correct.',
        'failed_get_details' => 'An error occurred while getting the card details.',
        'undefined_limit' => 'There is no limit defined for this card.',
        'not_issued' => 'This card has not been issued.',
        'failed_request_card_batch' => 'Failed to request this card batch.',
        'failed_find_cards' => 'Failed to find these cards.',
    ],

    'fraud_detection' => 'Transaction not approved on fraud process.',
    'insufficient_balance_to_transaction' => 'Insufficient balance to make the transaction.',
    'generic' => 'An error occurred while executing the request.',
];
