<?php

return [
    'http_status' => [
        100 => 'Continuar',
        101 => 'Mudando Protocolos',
        102 => 'Processando',
        200 => 'OK',
        201 => 'Criado',
        202 => 'Aceito',
        203 => 'Não Autorizado',
        204 => 'Nenhum Conteúdo',
        205 => 'Resetar Conteúdo',
        206 => 'Conteúdo Parcial',
        207 => 'Multi Status',
        300 => 'Múltipla Escolha',
        301 => 'Movido Permanentemente',
        302 => 'Encontrado',
        303 => 'Veja Outro',
        304 => 'Não Modificado',
        305 => 'Use Proxy',
        306 => 'Mudança De Proxy',
        307 => 'Redirecionamento temporário',
        308 => 'Redirecionamento permanente',
        400 => 'Solicitação Inválida',
        401 => 'Não Autorizado',
        402 => 'Pagamento Necessário',
        403 => 'Proibido',
        404 => 'Não Encontrado',
        405 => 'Método Não Permitido',
        406 => 'Não Aceito',
        407 => 'Autenticação De Proxy Necessária',
        408 => 'Tempo De Solicitação Esgotado',
        409 => 'Conflito',
        410 => 'Perdido',
        411 => 'Comprimento Necessário',
        412 => 'Falha Na Pré-condição',
        413 => 'Carga Muito Grande',
        414 => 'URI Muito Longo',
        415 => 'Tipo De Mídia Não Suportado',
        416 => 'Faixa Não Satisfatória',
        417 => 'Falha Na Expectativa',
        418 => 'Eu Sou Um Bule',
        419 => 'Página Expirada',
        421 => 'Solicitação Mal Direcionada',
        422 => 'Entidade Não Processável',
        423 => 'Recurso indisponível no momento',
        424 => 'Falha De Dependência',
        425 => 'Muito Cedo',
        426 => 'Atualização Necessária',
        428 => 'Pré-requisito Necessário',
        429 => 'Solicitações Em Excesso',
        431 => 'Campos De Cabeçalho De Solicitação Muito Grandes',
        451 => 'Indisponível Por Motivos Legais',
        500 => 'Erro Do Servidor Interno',
        501 => 'Não Implementado',
        502 => 'Gateway Ruim',
        503 => 'Serviço Indisponível',
        504 => 'Tempo Limite Do Gateway',
        505 => 'Versão HTTP Não Suportada',
        506 => 'A variante Também Negocia',
        507 => 'Armazenamento Insuficiente',
        508 => 'Loop Detectado',
        510 => 'Não Estendido',
        511 => 'Autenticação De Rede Necessária',
    ],

    'validation' => [
        'invalid_data' => 'Os dados enviados são inválidos.',
        'invalid_zipcode' => 'Aparentemente, o CEP inserido não corresponde ao endereço enviado. Verifique os dados e tente novamente.',
        'model_not_found' => ':modelName não encontrado(a).',
        'main_account_doesnt_have_credit' => 'A conta mãe não possui saldo suficiente.',
        'user' => [
            'pin' => 'Senha de operações inválida.',
            'password' => 'A senha está incorreta.',
            'not_defined' => 'Usuário não definido.',
        ],
        'account' => [
            'document' => 'O documento está incorreto.',
        ]
    ],

    'services' => [
        'general' => [
            'auth_failed' => 'Falha na autenticação com :serviceName.',
        ],
        'caradhras' => [
            'update_address_failed' => 'Falha ao tentar atualizar o documento.',
            'generic_error' => 'Ocorreu um erro no nosso provedor externo, caso o erro permaneça contate nosso suporte.',
            'card' => [
                'lock_failed' => 'Falha ao bloquear este cartão, verifique seus dados e tente novamente.',
            ],
        ],
        'zoop' => [
            'unknown_webhook_type' => 'O webhook do tipo :type ainda não foi implementado.',
            'unknown_payment_method' => 'Método de pagamento desconhecido (:method).',
        ],
        'bigdatacorp' => [
            'failed_to_find_person_address' => 'Falha ao buscar o endereço da pessoa.',
            'failed_to_find_company_address' => 'Falha ao buscar o endereço da empresa.',
            'failed_to_find_person_basic_data' => 'Falha ao buscar os dados cadastrais da pessoa.',
            'failed_to_find_company_basic_data' => 'Falha ao buscar os dados cadastrais da empresa.',
            'failed_to_find_company_partners' => 'Falha ao buscar os sócios da empresa.',
        ],
        'identity' => [
            'auth_failed' => 'Não foi possível acessar o serviço da IdentityAPI',
        ]
    ],

    'auth' => [
        'invalid_pin' => 'Senha de operações inválida.',
        'unauthorized' => 'Desculpe, você não está autorizado à acessar esse recurso.',
        'unauthenticated' => 'Você precisa estar autenticado para acessar esse recurso.',
        'failed' => 'Falha na Autenticação.',
        'expired_or_invalid_token' => 'Token expirado ou inválido.',
        'invalid_credentials' => 'Usuário ou senha inválidos.',
        'invalid_client_credentials' => 'Credenciais de acesso inválidas.',
        '2fa' => [
            'required_for_cashout' => 'Para sua segurança o segundo fator de autenticação deve estar configurado para realização de transações. Acesse a área "Minha conta" no web banking e siga as orientações para configurar sua conta.',
            'already_has_a_verified_device' => 'Você já possui um dispositivo verificado.',
            'device_disabled_by_invalid_attempts' => 'Dispositivo desativado por tentativas inválidas de OTP.',
            'device_not_have_2fa' => 'Dispositivo não possui uma chave de 2FA.',
            'invalid_otp' => 'A senha de 2FA é invalida.',
            'invalid_pattern' => 'O atributo :name deve ser composto por 6 dígitos.',
            'missing_attribute' => 'O atributo :name é o obrigatório.',
            'not_have_verified_devices' => 'Usuário não possui nenhum dispositivo verificado.',
            'only_one_device_allowed' => 'Só é permitido um dispositivo com 2FA habilitado.',
        ],
        'login_blocked' => 'Conta bloqueada por várias tentativas incorretas de login. Entre em contato com o suporte.',
        'social' => [
            'provider_without_handler' => 'Provider :provider não possui um handler.',
        ],
        'invalid_status' => 'Não foi possível acessar sua conta, pois a conta está com status :status. Em caso de dúvidas, entre em contato com o suporte.',
    ],

    'phone-recharge' => [
        'order' => 'Falha ao criar a ordem de recarga.',
        'confirm' => 'Falha ao confirmar a recarga telefônica.',
    ],

    'support' => [
        'failed_to_create_ticket' => 'Falha ao criar o Ticket.',
        'failed_to_create_customer' => 'Falha ao criar Cliente.',
        'failed_to_get_customer' => 'Falha ao obter Cliente.',
        'failed_to_query_api' => 'Falha ao realizar consulta na API.',
        'inbox_id_is_not_set' => 'Inbox_ID não está definido.',
    ],

    'beneficiary' => [
        'not_editable' => [
            'existing_transfers' => 'O beneficiário selecionado não é editável uma vez que já possui transferências efetivadas.',
            'p2p' => 'Não é possível editar um beneficiário do tipo P2P.',
        ],
        'undeletable' => 'O beneficiário selecionado não pode ser deletado uma vez que já possui transferências.',
        'not_exists' => 'O beneficiário não existe.',
        'not_active' => 'A conta do beneficiário não está ativa ou não existe.',
    ],

    'contact' => [
        'invalid_type' => 'O contato especificado possui um tipo de conta inválido para esta operação',
    ],

    'card' => [
        'attach_mcc_groups_failed' => 'Falha ao tentar associar os grupos de MCCs ao cartão.',
        'delete_mcc_group_failed' => 'Ocorreu um erro ao tentar deletar o grupo MCC.',
        'mcc_group_does_not_belong_to_card' => 'O grupo MCC não pertence ao cartão.',
        'unlock' => [
            'invalid_code' => 'O codigo especificado é inválido.',
            'needs_support_permission' => 'O Cartão está bloqueado por senha incorreta. Por favor entre em contato com o suporte.',
            'canceled' => 'Este cartão foi cancelado e não pode ser desbloqueado.',
            'failed' => 'Ocorreu um erro ao desbloquear o cartão.',
        ],
        'create' => [
            'already_exists' => 'Já existe um cartão físico para este titular.',
        ],
        'update' => [
            'not_issued' => 'Não é possível atualizar um cartão que não foi emitido.',
        ],
        'associate' => [
            'not_available' => 'Associação de cartões não está disponível.',
            'already_associated' => 'Este cartão já está associado para outra conta.',
            'nominal_card' => 'Este cartão é nominal e não pode ser associado a uma conta após a sua emissão.',
        ],
        'cvv_mismatch' => 'O CVV informado está incorreto.',
        'get_details_from_physical_card' => 'Não é possível obter os dados de um cartão físico.',
        'get_details_from_pending_card' => 'Não é possível obter os dados de um cartão pendente.',
        'failed_get_details' => 'Ocorreu um erro ao obter os dados do cartão.',
        'undefined_limit' => 'Não há limite criado para este cartão.',
        'not_issued' => 'Esse cartão não foi emitido.',
        'type_not_available' => 'Tipo de cartão não está disponível.',
        'failed_request_card_batch' => 'Houve uma falha ao solicitar seu lote de cartões.',
        'failed_find_cards' => 'Houve uma falha ao buscar os cartões.',
        'not_found' => 'Não encontramos nenhum cartão que corresponda aos dados informados.',
        'invalid_status' => 'O status desse cartão (:status) não permite essa operação.',
        'request' => [
            'cannot_request_when_has_pending_requests' => 'Não é possível solicitar um novo cartão enquanto houver solicitações pendentes.',
        ]
    ],

    'card_request' => [
        'not_defined' => 'Solitação de cartão não definida.',
    ],

    'card_request' => [
        'not_defined' => 'Solitação de cartão não definida.',
        'invalid_status' => 'O status da solicitação de cartão (:status) não permite essa operação.',
    ],

    'http' => [
        'route_not_found' => 'Rota inválida ou não encontrada.',
        'method_not_allowed' => 'O Método :method não é permitido para essa rota.',
    ],

    'invoices' => [
        'can_not_be_canceled' => 'O status dessa fatura não permite cancelamento.',
        'failed_to_cancel' => 'Houve uma falha no cancelamento dessa fatura, tente novamente ou entre em contato com nosso suporte.',
    ],

    'payments' => [
        'invalid_barcode' => 'Código de barras inválido.',
        'failed' => 'Falha no processamento do pagamento.',
        'not_payable' => 'Não Pagável.',
        'partner_not_accepted' => 'Parceiro de pagamento não aceito.',
        'payment_is_overdue' => 'Pagamento está fora do Vencimento.',
        'payment_out_of_time' => 'Pagamento fora do Horário Permitido. Por Favor Agende para :nextDate.',
        'only_scheduled_can_be_deleted' => 'Apenas pagamentos agendados podem ser excluidos.',
        'insufficient_balance' => 'Saldo insuficiente para efetuar o pagamento.',
        'cant_schedule_to_after_due_date' => 'Não é possível agendar o pagamento para depois da data de vencimento.',
        'cant_schedule_to_after_limit_date' => 'Não é possível agendar o pagamento para depois da data limite.',
        'can_schedule_only_to_business_days' => 'Só é possível agendar o pagamento para dias úteis.',
        'unregistered_barcode_or_already_paid' => 'Boleto não registrado ou já foi pago.',
        'schedule_payment_after_hours' => 'Não é possível agendar o boleto para o mesmo dia após o horário permitido.',
        'failed_to_parse' => 'Ocorreu um erro ao obter os detalhes do boleto. Por favor tente novamente em instantes.',
        'service_unavailable' => 'Serviço de pagamento indisponível.',
        'not_registered_at_cip' => 'Boleto não registrado na CIP. Tente novamente mais tarde ou entre em contato com o banco emissor.',
        'timeout' => 'O tempo limite expirou durante a execução da transação.',
        'unknown' => 'Não foi possível validar o código de barras.',
        'invalid_amount' => 'O valor do pagamento enviado difere do valor registrado para código de barras.',
        'expiration_date' => 'Boleto fora da data de vencimento.',
        'not_allowed_now' => 'Boleto fora do horário de pagamento. Tente novamente mais tarde, ou no próximo dia útil.',
        'validate' => 'Erro de validação do boleto.',
        'not_found' => 'Pagamento não encontrado.',
    ],

    'payrolls' => [
        'schedule_before_hours' => 'Não é possível agendar a folha de pagamento, antes do horário permitido.',
        'schedule_after_hours' => 'Não é possível agendar a folha de pagamento, após o horário permitido.',
        'not_employee' => 'Conta informada não possui vínculo de funcionário com sua conta.',
        'failed_validation' => 'Erro ao processar o arquivo, verifique se você está utilizando a vírgula (,) como separador decimal dos valores.',
    ],

    'transfers' => [
        'unknown_transfer_type' => 'Tipo de transferência desconhecida.',
        'only_scheduled_can_be_deleted' => 'Apenas transferências agendadas podem ser excluidas.',
    ],

    'transfer' => [
        'failed' => 'Ocorreu um erro ao realizar a transferência, favor tentar novamente.',
    ],

    'bank_transfer' => [
        'failed' => 'Ocorreu um erro ao realizar a transferência bancária, favor tentar novamente.',
    ],

    'pix_transfer' => [
        'dynamic_qr' => [
            'overdue' => 'Esse QR code está atrasado.',
            'can_not_change_amount' => 'Esse QR code não permite alteração no seu valor.',
        ],
    ],

    'onboarding' => [
        'invalid_status' => 'Status inválido para enviar o link de onboarding.',
        'invalid_token' => 'Token inválido ou já expirado.',
        'expired' => 'Link de cadastro expirado. Solicite outro pelo suporte.',
    ],

    'webhook' => [
        'undefined' => 'Webhook :name não está definido.',
    ],

    'subscription' => [
        'duplicated_service_subscription' => 'Esta conta já possui este serviço vinculado.',
        'pending_invoices' => 'Esta assinatura possui faturas pendentes.',
        'minimum_period' => 'Esta assinatura possui periodo mínimo.',
    ],

    'account' => [
        'undefined_main' => 'Conta mãe não definida.',
        'failed_to_process' => "Falha ao processar conta.",
        'creation' => [
            'need_analysis' => 'Tudo certo, sua conta está em análise e nossa equipe entrará em contato em breve.',
            'not_allowed' => 'Abertura de contas não permitida no momento. Para mais informações procure nosso suporte.',
        ],
        'not_ready_for_operations' => 'A conta não está pronta para operações.',
        'blocked' => 'Por segurança, esta conta está bloqueada. Consulte o nosso suporte para verificá-la novamente.',
        'suspected_fraud' => 'Sua conta está em análise preventiva. Não se preocupe, entre em contato com o nosso suporte para te auxiliarmos.',
        'waiting_documents' => 'Esta conta ainda está aguardando envio de documentos. Procure o nosso suporte caso precise de ajuda.',
        'under_review' => 'Esta conta está em revisão pelo nosso time. Te informaremos assim que esse processo for concluído. Aguarde.',
        'not_found_any_user_with_this_document_in_this_account' => 'Ops! Não encontramos nenhum usuário com este documento nesta conta.',
        'password_is_already_set' => 'A senha já está definida.',
        'invalid_account_status_to_update' => 'Status da conta inválido para atualização.',
        'failed_to_get_external_account_documents' => 'Falha ao obter documentos externos da conta.',
        'failed_to_upload_external_account_document' => 'Falha ao enviar o documento ":document".',
        'invalid_document_type_for_this_account' => 'Tipo de documento inválido para esta conta.',
        'invalid_status_for_uploading_documents' => 'Status de conta inválido para o envio de documentos.',
        'failed_to_find_holder_address' => 'Falha ao buscar o endereço do responsável.',
        'failed_to_find_holder_data' => 'Falha ao buscar os dados cadastrais do responsável.',
        'cant_create' => [
            'generic_reason' => 'Não foi possível criar esta conta.',
            'person_is_under_age' => 'Não é possível criar conta para pessoas menor de idade.',
            'company_is_inactive' => 'Não é possível criar conta para empresas inativas.',
        ],
        'cancel' => [
            'invalid_status' => 'Status de conta inválido para o cancelamento.',
            'has_balance' => 'Não é possível cancelar contas que ainda possuem saldo, por favor entre em contato com o suporte.',
            'bucket_has_balance' => 'Você ainda tem saldo em alguma subconta.',
            'bachback_bucket_has_balance' => 'Resgate seu cashback antes de cancelar sua conta.',
            'pix_key_required' => 'Você precisa especificar uma chave PIX para transferir o saldo total da conta (:balance).',
            'pix_key_not_owned_by_account_holder' => 'A chave PIX informada não pertence ao titular da conta.',
            'pix_key_not_found' => 'Não foi possível encontrar a chave pix',
            'could_not_complete_pix_transfer' => 'Falha ao fazer a transferência, tente novamente em alguns minutos.',
        ],
        'has_missing_required_fields_for_processing' => 'A conta não possui todos campos obrigatórios para processamento.',
        'only_personal_account_updatable' => 'Só é possível atualizar contas do tipo pessoal.',
        'only_pending_account_is_updatable' => 'Só é possivel atualizar contas com status pendente.',
        'has_active_card' => 'A conta ainda possui um cartão ativo.',
        'already_existing' => 'Conta existente ou o e-mail já foi utilizado em outro cadastro.',
        'main_only' => 'Disponível somente na conta principal.',
        'main_not_defined' => 'A Conta principal ainda não foi definida, por favor entre em contato com o nosso suporte.',
        'exists_employer' => 'Já existe um empregador vinculado a sua conta.',
        'not_exists_with_document' => 'Não há nenhuma conta pertencente a esse documento (:document).|[2,*] Não há contas pertencentes a esses documentos (:document).',
        'failed_update_person' => 'Houve uma falha ao atualizar sua sua conta.',
        'income_reports' => [
            'failed_to_find' => 'Não foi possível encontrar nenhum informativo de imposto de renda disponível.',
            'failed_to_send_to_email' => 'Houve uma falha eu enviar seu informativo de imposto de renda por email, tente novamente em alguns instantes.',
        ],
        'type_unavailable' => 'Tipo de conta não disponível',
        'type_already_exists' => 'Já existe uma subconta do tipo :type.',
    ],

    'bucket' => [
        'cancel' => [
            'invalid_status' => 'O status da subconta é inválido para cancelamento.',
        ],
        'withdrawal' => [
            'invalid_period' => 'A periodicidade escolhida não foi encontrada.',
        ],
    ],

    'company' => [
        'partner_not_found' => 'Sócio não encontrado.',
        'update_partner_failed' => 'Não foi possível atualizar esse sócio, tente novamente em alguns instantes',
    ],

    'this_operation_cannot_be_performed_in_bulk' => 'Essa operação não pode ser executada em massa.',

    'verification_code' => [
        'cooldown' => 'Você precisa aguardar pelo menos 30 segundos antes de solicitar um novo código.',
    ],

    'document' => [
        'failed_send' => 'Falha ao enviar o documento.',
        'failed_to_read_the_file' => 'Falha ao ler o arquivo.',
        'invalid_file_format' => 'Formato de arquivo inválido.',
        'check_only_personal_account_step' => 'Check de etapa apenas para conta pessoal.',
        'selfie' => [
            'invalid' => 'A imagem carregada não é uma selfie.',
            'low_quality' => 'Selfie com baixa qualidade.',
            'face_not_visible' => 'Rosto não visível na foto.',
            'inconsistent' => 'Selfie inconsistente.',
            'duplicated' => 'Por favor, tente novamente enviando uma nova imagem.',
        ],
        'invalid' => 'O documento enviado é inválido.',
    ],

    'credit' => [
        'failed_process' => 'Falha ao processar o crédito.',
        'failed_verification' => 'Falha ao verificar o código.',
        'failed_to_create_external_request' => 'Falha ao criar solicitação de crédito externa.',
    ],

    'bank_slip' => [
        'cant_generate_pdf' => 'Não é possível gerar um PDF para esse boleto.',
        'cant_sent_to_email' => 'Não é possível enviar esse boleto por email.',
        'invalid_charge_type' => 'Tipo de cobrança inválido.',
        'failed_to_create' => 'Falha ao criar o boleto.',
        'invalid_due_date' => 'Vencimento é uma data antes de hoje ou inválida.',
        'not_found' => 'Boleto não encontrado.',
        'failed_to_get_pdf' => 'Falha ao obter o PDF do boleto.',
        'expired' => 'Boleto expirado.',
    ],

    'schedule' => [
        'canceled' => 'Agendamento cancelado.',
        'invalid_type' => 'Tipo de agendamento inválido.',
        'invalid_status' => 'Não é possível agendar transferências com status :status.',
        'invalid_date' => 'Não é possível agendar transferências para uma data anterior à hoje.',
        'processed' => 'Esse agendamento já foi processado.',
        'failed' => 'O agendamento de uma transação falhou ao ser processado.',
    ],

    'schedule' => [
        'processing_unavailable' => 'Essa transação não pode ser processada.',
        'cancel_unavailable' => 'Essa transação não pode ser cancelada.',
    ],

    'fraud_detection' => 'Transação não autorizada pelo motor anti-fraude.',
    'insufficient_balance_to_transaction' => 'Saldo insuficiente para realizar a transação.',
    'login_with_provider_before_proceeding' => 'Faça o login com :provider antes de continuar.',
    'version_constraint' => 'Temos novidades! Mais agilidade e qualidade em um aplicativo novinho para você. Então para continuar com tudo funcionando corretamente é necessário atualizar o seu APP pela loja do seu smartphone.',
    'duplicated_request' => 'Uma solicitação exatamente igual a essa já foi feita há menos de :cooldown segundos. Tente novamente em alguns instantes.',
    'failed_to_generate_pdf' => 'Falha ao gerar PDF.',

    'sql' => [
        'query_error' => 'Ocorreu um erro ao realizar a consulta no banco de dados.',
    ],

    'api' => [
        'generic_error' => 'Ocorreu um erro ao realizar a requisição.',
    ],

    'device' => [
        'invalid_code' => 'Código de verificação inválido.',
        'already_verified' => 'O dispositivo já foi verificado.',
    ],

    'error_occurred' => 'Ocorreu um erro.',
    'generic' => 'Ocorreu um erro ao realizar a requisição.',
    'internal_error' => 'Ocorreu um erro interno.',
    'cash_out.not_allowed' => 'Operação não permitida neste momento.',
    'operation.not_allowed' => 'Operação não permitida neste momento.',
    'untrusted_origin' => 'A requisição foi negada pois veio de uma origem não confiável.',

    'transaction' => [
        'lock' => 'A transação já está em processamento. Tente novamente em 30 segundos.',
        'min_amount' => 'Valor mínimo para esse tipo de transação é de :amount.',
    ],

    'hephaestus' => [
        'url_failed' => 'Ocorreu um erro ao gerar o PDF a partir dessa URL.',
        'html_failed' => 'Ocorreu um erro ao gerar o PDF a partir desse HTML.',
    ],
    'loan' => [
        'failed_disbursement' => 'Falha ao transferir valor referente ao desembolso.'
    ],

    'user' => [
        'unable_update_password' => 'Não é possível atualizar a senha.',
    ]
];
