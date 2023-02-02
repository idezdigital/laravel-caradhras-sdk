<?php

namespace Idez\Caradhras\Enums\Cards;

enum PierTransactionStatus: string
{
    case Approved = 'approved';
    case AuthorizedCheck = 'authorized-check';
    case BlockedCard = 'blocked-card';
    case CanceledCard = 'card-was-canceled';
    case CardNotFound = 'card-not-found';
    case Denied = 'denied';
    case DuplicatedPayment = 'duplicated-payment';
    case ExpiredCard = 'expired-card';
    case FormatError = 'format-error';
    case FraudRisk = 'fraud-risk';
    case RetypeTransaction = 'retype-transaction';
    case InsufficientBalance = 'insuficient-balance';
    case InvalidAmount = 'invalid-amount';
    case InvalidCard = 'invalid-card';
    case InvalidCoupon = 'invalid-coupon';
    case InvalidData = 'invalid-data';
    case InvalidDealer = 'invalid-dealer';
    case InvalidSessionKey = 'invalid-session-key';
    case InvalidTerminal = 'invalid-terminal';
    case LimitExceeded = 'limit-exceeded';
    case NotAllowed = 'not-allowed';
    case RepeatedTransaction = 'repeated-transaction';
    case StolenCard = 'stolen-card';
    case SystemError = 'system-error';
    case TransactionNotFound = 'transaction-not-found';
    case RefusedDueCvv2Failure = 'refused-due-cvv2-failure';
    case TransactionCanceled = 'transaction-canceled';
    case TryAgain = 'try-again';
    case UnregisteredEstablishment = 'unregistered-establishment';
    case WrongPassword = 'wrong-password';
    case ExceededWithdrawalLimit = 'exceeded-withdrawal-limit';

    public static function mapFromCaradhrasCode(string $code): self
    {
        return match ($code) {
            '00' => self::Approved, // A transação foi aprovada.
            '01' => self::InvalidCard, // O erro foi mencionado devido a problemas com o cartão.
            '03' => self::UnregisteredEstablishment, // O estabelecimento não está registrado.
            '04' => self::CanceledCard,
            '05' => self::Denied, // A transação foi negada.
            '06' => self::TryAgain, // É necessário refazer a transação.
            '07' => self::CanceledCard, // Este cartão tem uma condição especial (roubado ou cancelado).
            '10' => self::WrongPassword, // Código de desbloqueio inválido.
            '12' => self::NotAllowed, // A transação (débito ou crédito) não corresponde ao produto selecionado (débito ou crédito).
            '13' => self::InvalidAmount, // Montante inválido.
            '14' => self::InvalidCard, // Cartão inválido (ver status).
            '19' => self::RetypeTransaction, // Transação não pode ser processada temporariamente.
            '30' => self::FormatError, // Erro de formato.
            '31' => self::UnregisteredEstablishment, // O estabelecimento não pertence à rede.
            '39' => self::InvalidCard, // O Portador do cartão deve usar um cartão físico com um chip.
            '41' => self::InvalidData, // Data de vencimento invertida ou dados de gravação diferem do registro.
            '43' => self::StolenCard, // Cartão roubado.
            '51' => self::InsufficientBalance, // Limite insuficiente.
            '52' => self::InvalidCoupon, // Cupom inválido.
            '54' => self::ExpiredCard, // Cartão expirado.
            '55' => self::WrongPassword, // A senha não foi registrada ou é inválida.
            '56' => self::CardNotFound, // Cartão inexistente.
            '57' => self::Denied, // O limite da conta controle (Controle de Cartões ) foi ultrapassado.
            '58' => self::InvalidTerminal, // Terminal inválido.
            '59' => self::FraudRisk, // A transação foi negada devido a alegações de fraude.
            '61' => self::LimitExceeded, // A tentativa de transação ocorreu com um valor acima do permitido.
            '62' => self::InvalidSessionKey, // Chave de sessão inválida.
            '65' => self::ExceededWithdrawalLimit, // Overlimit de uso do limite.
            '75' => self::BlockedCard,
            '76' => self::BlockedCard, // Cartão bloqueado.
            '78' => self::TransactionCanceled, // Transação cancelada.
            '80' => self::LimitExceeded, // Total excedido.
            '81' => self::DuplicatedPayment, // Pagamento duplicado.
            '82' => self::LimitExceeded, // Quantidade fora do limite.
            '83' => self::ExpiredCard, // Vencimento.
            '84' => self::InvalidDealer, // Concessionário inválido.
            '85' => self::AuthorizedCheck, // Verificação de conta autorizada.
            '94' => self::RepeatedTransaction, // Transação repetida.
            '96' => self::SystemError, // Erro no sistema.
            '99' => self::TransactionNotFound, // Transação original não encontrada ou erros de parâmetro.
            'N7' => self::RefusedDueCvv2Failure, // Usada para indicar que a autorização não passou na verificação de CVV2
        };
    }
}
