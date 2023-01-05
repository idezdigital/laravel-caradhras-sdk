    <?php

return [
    'http_status' => [
        100 => 'Continue',
        101 => 'Switching Protocols',
        102 => 'Processing',
        200 => 'OK',
        201 => 'Created',
        202 => 'Accepted',
        203 => 'Non-Authoritative Information',
        204 => 'No Content',
        205 => 'Reset Content',
        206 => 'Partial Content',
        207 => 'Multi-Status',
        300 => 'Multiple Choices',
        301 => 'Moved Permanently',
        302 => 'Found',
        303 => 'See Other',
        304 => 'Not Modified',
        305 => 'Use Proxy',
        306 => 'Switch Proxy',
        307 => 'Temporary Redirect',
        308 => 'Permanent Redirect',
        400 => 'Bad Request',
        401 => 'Unauthorized',
        402 => 'Payment Required',
        403 => 'Forbidden',
        404 => 'Not Found',
        405 => 'Method Not Allowed',
        406 => 'Not Acceptable',
        407 => 'Proxy Authentication Required',
        408 => 'Request Timeout',
        409 => 'Conflict',
        410 => 'Gone',
        411 => 'Length Required',
        412 => 'Precondition Failed',
        413 => 'Payload Too Large',
        414 => 'URI Too Long',
        415 => 'Unsupported Media Type',
        416 => 'Range Not Satisfiable',
        417 => 'Expectation Failed',
        418 => 'I\'m a teapot',
        419 => 'Page Expired',
        421 => 'Misdirected Request',
        422 => 'Unprocessable Entity',
        423 => 'Locked',
        424 => 'Failed Dependency',
        425 => 'Too Early',
        426 => 'Upgrade Required',
        428 => 'Precondition Required',
        429 => 'Too Many Requests',
        431 => 'Request Header Fields Too Large',
        451 => 'Unavailable For Legal Reasons',
        500 => 'Internal Server Error',
        501 => 'Not Implemented',
        502 => 'Bad Gateway',
        503 => 'Service Unavailable',
        504 => 'Gateway Timeout',
        505 => 'HTTP Version Not Supported',
        506 => 'Variant Also Negotiates',
        507 => 'Insufficient Storage',
        508 => 'Loop Detected',
        510 => 'Not Extended',
        511 => 'Network Authentication Required',
    ],

    'validation' => [
        'invalid_data' => 'The data sent is invalid.',
        'invalid_zipcode' => 'Apparently the zip code entered does not correspond with the address sent, please check the data and try again.',
        'model_not_found' => 'Register in :modelName not found.',
        'main_account_doesnt_have_credit' => 'The main account doesnt have enough funds',
        'user' => [
            'pin' => 'The PIN entered is invalid.',
            'password' => 'The password entered is invalid.',
            'not_defined' => 'The user is not defined.',
        ],
        'account' => [
            'document' => 'The document entered is invalid.',
        ],
    ],

    'services' => [
        'general' => [
            'auth_failed' => 'Authentication failed with :serviceName.',
        ],
        'caradhras' => [
            'update_address_failed' => 'Failed to try to update the document.',
            'generic_error' => 'An error occurred in our external provider, if the error remains contact our support.',
            'card' => [
                'lock_failed' => 'Failed to lock this card, please check your data and try again.',
            ],
        ],
        'zoop' => [
            'unknown_webhook_type' => 'The :type webhook is not yet implemented.',
            'unknown_payment_method' => 'Unknown payment method (:method).',
        ],
        'bigdatacorp' => [
            'failed_to_find_person_address' => 'Failed to find person\'s address.',
            'failed_to_find_company_address' => 'Failed to find company\'s address.',
            'failed_to_find_person_basic_data' => 'Failed to find person\'s registration data.',
            'failed_to_find_company_basic_data' => 'Failed to find company\'s registration data.',
            'failed_to_find_company_partners' => 'Failed to find company\'s partners.',
        ],
        'identity' => [
            'auth_failed' => 'Could not access the IdentityAPI',
        ],
    ],

    'auth' => [
        'invalid_pin' => 'Invalid PIN.',
        'unauthorized' => 'Sorry, you are not authorized to access this feature.',
        'unauthenticated' => 'You must be authenticated in to access this feature.',
        'failed' => 'Authentication failed.',
        'expired_or_invalid_token' => 'Invalid or expired Token.',
        'invalid_credentials' => 'Wrong username or password.',
        'invalid_client_credentials' => 'Invalid client credentials.',
        '2fa' => [
            'required_for_cashout' => 'For your security, the second authentication factor must be configured to cash out transactions. Head to "My Account" area in web banking and follow the guidelines for configuring the second authentication factor properly.',
            'already_has_a_verified_device' => 'Already has a verified device.',
            'device_disabled_by_invalid_attempts' => 'Device disabled by invalid OTP attempts.',
            'device_not_have_2fa' => 'Device does not have a 2FA key.',
            'invalid_otp' => 'The 2FA password is invalid.',
            'invalid_pattern' => 'The :name attribute must be 6 digits.',
            'missing_attribute' => 'The :name attribute is required.',
            'not_have_verified_devices' => 'User does not have any Verified Devices.',
            'only_one_device_allowed' => 'Only 1 device allowed with 2FA enabled.',
        ],
        'login_blocked' => 'Account blocked by multiple incorrect login attempts. Please contact support.',
        'social' => [
            'provider_without_handler' => 'Provider :provider does not have a handler.',
        ],
        'invalid_status' => "It's not possible to login in your account, your account status is :status. In case you need help, please contact our support.",
    ],

    'support' => [
        'failed_to_create_ticket' => 'Failed to create Ticket.',
        'failed_to_create_customer' => 'Failed to create Customer.',
        'failed_to_get_customer' => 'Failed to get Customer.',
        'failed_to_query_api' => 'Failed to query the API.',
        'inbox_id_is_not_set' => 'Inbox_ID is not set.',
    ],

    'beneficiary' => [
        'not_editable' => [
            'existing_transfers' => 'The selected beneficiary is not editable since it already has successful transfers.',
            'p2p' => 'It is not possible to edit a P2P beneficiary.',
        ],
        'undeletable' => 'The selected beneficiary is undeletable since it has transfers.',
        'not_exists' => 'The beneficiary does not exist.',
        'not_active' => 'Beneficiary account is not active or does not exist.',
    ],

    'contact' => [
        'invalid_type' => 'The specified contact has an invalid account type for this operation',
    ],

    'phone-recharge' => [
        'order' => 'Failed to Order a phone request.',
        'confirm' => 'Failed to confirm a phone request.',
    ],

    'card' => [
        'attach_mcc_groups_failed' => 'Failed to attach MCC groups to card.',
        'delete_mcc_group_failed' => 'Failed to delete MCC group from card.',
        'mcc_group_does_not_belong_to_card' => 'MCC group does not belong to card.',
        'unlock' => [
            'invalid_code' => 'The specified unlock code is invalid.',
            'needs_support_permission' => 'The card is blocked by incorrect password. Please contact support.',
            'canceled' => 'This card is canceled and cannot be unblocked.',
            'failed' => 'There was an error unlocking the card.',
        ],
        'create' => [
            'already_exists' => 'A Physical card already exists for this holder.',
        ],
        'update' => [
            'not_issued' => 'It is not possible to update a card that has not been issued.',
        ],
        'associate' => [
            'not_available' => 'Card associate is not available.',
            'already_associated' => 'The specified card is already associated to another account.',
            'nominal_card' => 'The specified card is nominal, therefore it cannot be associated after issuance.',
        ],
        'cvv_mismatch' => 'The informed CVV is not correct.',
        'get_details_from_physical_card' => 'It is not possible to obtain the actual details from a physical card.',
        'get_details_from_pending_card' => 'It is not possible to obtain the details from a pending card.',
        'failed_get_details' => 'An error occurred while getting the card details.',
        'undefined_limit' => 'There is no limit defined for this card.',
        'not_issued' => 'This card has not been issued.',
        'type_not_available' => 'The card type is not available.',
        'failed_request_card_batch' => 'Failed to request this card batch.',
        'failed_find_cards' => 'Failed to find these cards.',
        'not_found' => "We couldn't find any card that matches the data entered.",
        'invalid_status' => 'Invalid card status (:status) for this operation',
        'request' => [
            'cannot_request_when_has_pending_requests' => 'It is not possible to request a new card while there are pending requests.',
            ]
    ],

    'card_request' => [
        'not_defined' => 'Card request is not defined.',
    ],

    'card_request' => [
        'not_defined' => 'Card request is not defined.',
        'invalid_status' => 'Invalid card request status (:status) for this operation',
    ],

    'http' => [
        'route_not_found' => 'Invalid or missing route.',
        'method_not_allowed' => 'The :method method is not supported for this route.',
    ],

    'invoices' => [
        'can_not_be_canceled' => 'Invoice status does not allow cancellation.',
        'failed_to_cancel' => 'Failed to cancel this invoice, please try again or contact our support.',
    ],

    'payments' => [
        'invalid_barcode' => 'Invalid barcode.',
        'failed' => 'Payment processing failed.',
        'not_payable' => 'Not Payable.',
        'partner_not_accepted' => 'Payment Partner is not accepted.',
        'payment_is_overdue' => 'Payment is overdue.',
        'payment_out_of_time' => 'Payment out of time. Please schedule to :nextDate.',
        'only_scheduled_can_be_deleted' => 'Only scheduled transfers can be deleted.',
        'insufficient_balance' => 'Insufficient balance to make the payment.',
        'cant_schedule_to_after_due_date' => 'It is not possible to schedule payment after the due date.',
        'cant_schedule_to_after_limit_date' => 'It is not possible to schedule payment after the limit date.',
        'can_schedule_only_to_business_days' => 'You can only schedule payment for business days.',
        'unregistered_barcode_or_already_paid' => 'Unregistered barcode or payment already paid.',
        'schedule_payment_after_hours' => 'It is not possible to schedule for the same day after the allowed time.',
        'failed_to_parse' => 'An error occurred while getting payment details. Please, try again soon.',
        'service_unavailable' => 'Service unavailable while executing transaction.',
        'not_registered_at_cip' => 'Bank not registered at CIP.',
        'timeout' => 'Timeout expired while executing transaction.',
        'unknown' => 'Could not validate the provided barcode.',
        'invalid_amount' => 'Payment amount submitted differs from value recorded for barcode.',
        'expiration_date' => 'Barcode expired.',
        'not_allowed_now' => 'Please try again later, or the next business day.',
        'validate' => 'Barcode validate error.',
        'not_found' => 'Payment not found.',
    ],

    'payrolls' => [
        'schedule_before_hours' => 'It is not possible to schedule the payroll before the allowed time.',
        'schedule_after_hours' => 'It is not possible to schedule the payroll after the allowed time.',
        'not_employee' => "It's not the employee",
        'failed_validation' => 'Failed to process file. Check if you are using the comma (,) as decimal separator of values.',
    ],

    'transfers' => [
        'unknown_transfer_type' => 'Unknown transfer type.',
        'only_scheduled_can_be_deleted' => 'Only scheduled transfers can be deleted.',
    ],

    'transfer' => [
        'failed' => 'An error occurred while performing the transfer, please try again.',
    ],

    'bank_transfer' => [
        'failed' => 'An error occurred while performing the bank transfer, please try again.',
    ],

    'pix_transfer' => [
        'dynamic_qr' => [
            'overdue' => 'This QR code is overdue',
            'can_not_change_amount' => 'This QR code does not allow you to change its amount',
        ],
    ],

    'onboarding' => [
        'invalid_status' => 'Invalid status to send onboarding link.',
        'invalid_token' => 'Invalid or expired token.',
        'expired' => 'Expired onboarding. Request a new one.',
    ],

    'webhook' => [
        'undefined' => 'Webhook :name is undefined.',
    ],

    'subscription' => [
        'duplicated_service_subscription' => 'There is already this service subscription linked to this account.',
        'pending_invoices' => 'There is pending invoices.',
        'minimum_period' => 'There is a minimum period.',
    ],

    'account' => [
        'undefined_main' => "Main account is undefined.",
        'failed_to_process' => "Process account failed.",
        'creation' => [
            'need_analysis' => 'Alright, your account is under analysis and our team will get back to you soon.',
            'not_allowed' => 'Account creation is currently not supported. For more information, count on our support.',
        ],
        'not_ready_for_operations' => 'The account is not ready for operations.',
        'blocked' => 'Our background check has blocked this account for security reasons. Contact our help desk team to activate it.',
        'suspected_fraud' => "Your account is under preventive analysis. Don't worry, contact our support to help you.",
        'waiting_documents' => 'This account is still waiting for regulatory documents upload. Contact our help desk team to proceed.',
        'under_review' => 'This account is under review by our team. We will contact you when this process ends. Hold on.',
        'not_found_any_user_with_this_document_in_this_account' => 'Ops! We did not find any user with this document in this account.',
        'password_is_already_set' => 'The user password is already set.',
        'invalid_account_status_to_update' => 'Invalid account status to update.',
        'failed_to_get_external_account_documents' => 'Failed to get external account documents.',
        'failed_to_upload_external_account_document' => 'Failed to upload ":document".',
        'invalid_document_type_for_this_account' => 'Invalid document type for this account.',
        'invalid_status_for_uploading_documents' => 'Invalid account status for uploading documents.',
        'failed_to_find_holder_address' => 'Failed to find holder\'s address.',
        'failed_to_find_holder_data' => 'Failed to find holder\'s registration data.',
        'cant_create' => [
            'generic_reason' => 'This account could not be created.',
            'person_is_under_age' => 'Unable to create account for minors.',
            'company_is_inactive' => 'Unable to create account for inactive companies.',
        ],
        'cancel' => [
            'invalid_status' => 'This account is not in a valid status for cancelation.',
            'has_balance' => 'It is not possible to cancel accounts that still have a balance, please contact support.',
            'bucket_has_balance' => 'You still have a balance in some bucket.',
            'cashback_bucket_has_balance' => 'Check your cashback before canceling your account.',
            'pix_key_required' => 'You need to specify a PIX key so we can wire the remaining account balance (:balance).',
            'pix_key_not_owned_by_account_holder' => 'The provided PIX Key is not owned by account holder.',
            'pix_key_not_found' => 'Unable to find the pix key',
            'could_not_complete_pix_transfer' => 'Failed to complete transfer, please try again in a few minutes.',
        ],
        'has_missing_required_fields_for_processing' => 'Account has missing required fields for processing.',
        'only_personal_account_updatable' => 'It is only possible to update the mother\'s name in personal accounts.',
        'only_pending_account_is_updatable' => 'It is only possible to update accounts with status pending.',
        'has_active_card' => 'Account still has an active card.',
        'already_existing' => 'Existing account or the email has already been used in another registration.',
        'main_only' => 'Available on main account only.',
        'main_not_defined' => 'The main account has not been set yet, please contact our support.',
        'exists_employer' => 'There is already an employer linked to your account.',
        'not_exists_with_document' => 'There is no account belonging to this document (:document).|There are no accounts belonging to these documents (:document).',
        'failed_update_person' => 'Failed to update this account',
        'income_reports' => [
            'failed_to_find' => 'Failed to find available income reports.',
            'failed_to_send_to_email' => 'Failed to sent income reports to email, try again soon.',
        ],
        'type_already_exists' => 'There is already a :type sub account.',
    ],

    'bucket' => [
        'cancel' => [
            'invalid_status' => 'Bucket status is not valid for cancel.',
        ],
        'withdrawal' => [
            'invalid_period' => 'The chosen frequency was not found.',
        ],
    ],

    'company' => [
        'partner_not_found' => 'Partner not found.',
        'update_partner_failed' => 'Failed to update this partner, try again in a few moments',
    ],

    'this_operation_cannot_be_performed_in_bulk' => 'This operation cannot be performed in bulk.',

    'verification_code' => [
        'cooldown' => 'Please, hold on at least 30s to request a new token.',
    ],

    'document' => [
        'failed_send' => 'Failed to send document.',
        'failed_to_read_the_file' => 'Failed to read the file.',
        'invalid_file_format' => 'Invalid file format.',
        'check_only_personal_account_step' => 'Check step only for personal accounts.',
        'selfie' => [
            'invalid' => 'The uploaded image is not a selfie.',
            'low_quality' => 'Low Quality selfie.',
            'face_not_visible' => 'Face is not visible.',
            'inconsistent' => 'Inconsistent selfie.',
            'duplicated' => 'Please try again by uploading a new image.',
        ],
        'invalid' => 'The uploaded document is invalid.',
    ],

    'credit' => [
        'failed_process' => 'Failed to process credit.',
        'failed_verification' => 'Failed to verify credit.',
        'failed_to_create_external_request' => 'Failed to create external credit request.',
    ],

    'bank_slip' => [
        'cant_generate_pdf' => 'It is not possible to generate a PDF for this bank slip.',
        'cant_sent_to_email' => 'It is not possible to send this bank slip by email.',
        'invalid_charge_type' => 'Invalid charge type.',
        'invalid_due_date' => 'Due date is a date before today or invalid.',
        'failed_to_create' => 'Failed to create bank slip.',
        'not_found' => 'Bank slip not found.',
        'failed_to_get_pdf' => 'Failed to get bank slip PDF.',
        'expired' => 'Bank slip expired.',
    ],

    'schedule' => [
        'canceled' => 'Schedule canceled.',
        'invalid_type' => 'Invalid schedule type.',
        'invalid_status' => 'Unable to schedule transfers with status :status.',
        'invalid_date' => 'Unable to schedule transfer with date before today.',
        'processed' => 'This schedule already is processed.',
        'failed' => 'The schedule has failed to process.',
    ],

    'schedule' => [
        'processing_unavailable' => 'This schedule can not be processed.',
        'cancel_unavailable' => 'This transaction can not be canceled',
    ],

    'fraud_detection' => 'Transaction not approved on fraud process.',
    'insufficient_balance_to_transaction' => 'Insufficient balance to make the transaction.',
    'login_with_provider_before_proceeding' => 'Please login with :provider before proceeding.',
    'version_constraint' => 'This API required version constraint :version to work properly. Upgrade necessary.',
    'duplicated_request' => 'An identical request has been executed in less than :cooldown seconds. Please try again later.',
    'failed_to_generate_pdf' => 'Failed to generate the PDF.',

    'sql' => [
        'query_error' => 'An error occurred while querying the database.',
    ],

    'api' => [
        'generic_error' => 'An error occurred while executing the request.',
    ],

    'device' => [
        'invalid_code' => 'Incorrect verification code.',
        'already_verified' => 'The device has already been verified.',
    ],

    'error_occurred' => 'An error has occurred.',
    'generic' => 'An error occurred while executing the request.',
    'internal_error' => 'Occurred a internal error.',
    'cash_out.not_allowed' => 'Operation not allowed at this time.',
    'operation.not_allowed' => 'Operation not allowed at this time.',
    'untrusted_origin' => 'The request was denied because it came from an untrusted origin.',

    'transaction' => [
        'lock' => 'The transaction is already processing. Try again in 30 seconds.',
        'min_amount' => 'Minimum amount to transaction of this type is :amount.',
    ],

    'hephaestus' => [
        'url_failed' => 'Failed to generate PDF from URL.',
        'html_failed' => 'Failed to generate PDF from html.',
    ],
    'loan' => [
        'failed_disbursement' => 'Failed to transfer disbursement amount.',
    ],
    'user' => [
        'unable_update_password' => 'Unable to update password.',
    ]
];
