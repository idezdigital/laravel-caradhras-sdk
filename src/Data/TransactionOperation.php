<?php

namespace App\Enums\Transactions;

use Idez\Caradhras\Traits\NovaSelectEnum;
use Idez\Caradhras\Contracts\NovaSelectEnumInterface;

enum TransactionOperation: string implements NovaSelectEnumInterface
{
    use NovaSelectEnum;

    case CreditBackoffice = 'credit.backoffice';
    case CreditCustom = 'credit.custom';
    case Deposit = 'deposit';
    case ChargeReceived = 'charge.received';
    case DonationSent = 'donation.sent';
    case DonationReceived = 'donation.received';
    case FeeBankSlip = 'fee.bankslip';
    case FeeCardReissued = 'fee.card.reissue';
    case FeeCustom = 'fee.custom';
    case FeeIof = 'fee.iof';
    case FeeSubscription = 'fee.subscription';
    case FeeBankTransfer = 'fee.transfer';
    case FeeTransfer = 'fee.p2p';
    case FeeWithdrawal = 'fee.withdrawal';
    case FeeWithdrawalInternational = 'fee.withdrawal.intl';
    case TransferReceived = 'p2p.received';
    case TransferSent = 'p2p.sent';
    case PaymentSent = 'payment.sent';
    case PixReceived = 'pix.received';
    case PixSent = 'pix.sent';
    case PixReversalSent = 'pix-reversal.sent';
    case PixReversalReceived = 'pix-reversal.received';
    case PhoneRecharge = 'phone_recharge';
    case PurchaseInternational = 'purchase.intl';
    case PurchaseNational = 'purchase.national';
    case RefundIof = 'refund.iof';
    case RefundPayment = 'refund.payment';
    case RefundPhoneRecharge = 'refund.phone_recharge';
    case RefundPurchaseInternational = 'refund.purchase_intl';
    case RefundBankTransfer = 'refund.transfer';
    case RefundTransferReceived = 'refund.p2p-received';
    case RefundTransferSent = 'refund.p2p';
    case RefundPix = 'refund.pix';
    case BankTransferReceived = 'transfer.received';
    case BankTransferSent = 'transfer.sent';
    case InvoicePaid = 'invoice.paid';
    case InvoiceReceived = 'invoice.received';
    case InstallmentPaid = 'installment.paid';
    case InstallmentReceived = 'installment.received';
    case CashbackSent = 'cashback.sent';
    case CashbackReceived = 'cashback.received';
    case CommissionPaid = 'commission.paid';
    case CommissionReceived = 'commission.received';
    case BucketWithdrawalSent = 'bucket_withdrawal.sent';
    case BucketWithdrawalReceived = 'bucket_withdrawal.received';
    case BucketCashbackWithdrawalSent = 'bucket_cashback_withdrawal.sent';
    case BucketCashbackWithdrawalReceived = 'bucket_cashback_withdrawal.received';
    case QrPaymentSale = 'qrcode_payment.sale';
    case QrPaymentPurchase = 'qrcode_payment.purchase';
    case Withdrawal = 'withdrawal';
    case DailyIncome = 'income.daily';
    case Other = 'other';
    case BacenjudLock = 'bacenjud.lock';
    case BacenjudTransfer = 'bacenjud.transfer';
    case BacenjudUnlock = 'bacenjud.unlock';

    public function label(): string
    {
        return trans("transactions.{$this->value}");
    }

    public static function mappingFromDescription(string $description): TransactionOperation
    {
        return match ($description) {
            'Pix Enviado-Conta Transacional' => self::PixSent,
            'Pix Recebido-Cta Transacional' => self::PixReceived,
            'Saque Nacional Pré-Pago Visa' => self::Withdrawal,
            'Transf entre Contas-Remetente' => self::TransferSent,
            'Transf Bancaria Enviada' => self::BankTransferSent,
            'Transf Bancaria Recebida' => self::BankTransferReceived,
            'Recarga Deposito Boleto', 'Pagamento Efetuado' => self::Deposit,
            'Tarifa Transf Bancaria Enviada' => self::FeeBankTransfer,
            'Tarifa Transf entre Contas' => self::FeeTransfer,
            'Compra Pré-Pago Visa Nacional' => self::PurchaseNational,
            'Transf entre Contas-Favorecido' => self::TransferReceived,
            'Compra Pré-Pago Visa Ext' => self::PurchaseInternational,
            'Pagamento de Contas' => self::PaymentSent,
            'Taxa de Saque Pré-Pago Visa' => self::FeeWithdrawal,
            'Tarifa Mensalidade' => self::FeeSubscription,
            'Ajuste a Credito Backoffice' => self::CreditBackoffice,
            'Recarga de Celular' => self::PhoneRecharge,
            'Pgto Contas nao Processado' => self::RefundPayment,
            'Rec Celular nao Processado' => self::RefundPhoneRecharge,
            'Est Transf Bancaria Enviada' => self::RefundBankTransfer,
            'Estorno Transf Ctas-Remetente' => self::RefundTransferSent,
            'Estorno Transf Ctas-Favorecido' => self::RefundTransferReceived,
            'Pix Env Não Processado' => self::RefundPix,
            'Tarifa Rec Deposito Boleto' => self::FeeBankSlip,
            'Devolucao Pix Enviado' => self::PixReversalReceived,
            'Devolucao Pix Recebido' => self::PixReversalSent,
            'IOF Transacoes Exterior R$' => self::FeeIof,
            '655 Bloq Bacenjud-Cta Pre-Paga' => self::BacenjudLock,
            '655 Transferencia Bacenjud' => self::BacenjudTransfer,
            '655 Desbloqueio Bacenjud' => self::BacenjudUnlock,
            'Remuneração-Cta Pgto' => self::DailyIncome,
            default => self::Other,
        };
    }

    public function getDescription(): string
    {
        return match ($this) {
            self::PixSent => 'Pix Enviado-Conta Transacional',
            self::PixReceived => 'Pix Recebido-Cta Transacional',
            self::Withdrawal => 'Saque Nacional Pré-Pago Visa',
            self::TransferSent => 'Transf entre Contas-Remetente',
            self::BankTransferSent => 'Transf Bancaria Enviada',
            self::BankTransferReceived => 'Transf Bancaria Recebida',
            self::Deposit => 'Pagamento Efetuado',
            self::FeeBankTransfer => 'Tarifa Transf Bancaria Enviada',
            self::FeeTransfer => 'Tarifa Transf entre Contas',
            self::PurchaseNational => 'Compra Pré-Pago Visa Nacional',
            self::TransferReceived => 'Transf entre Contas-Favorecido',
            self::PurchaseInternational => 'Compra Pré-Pago Visa Ext',
            self::PaymentSent => 'Pagamento de Contas',
            self::FeeWithdrawal => 'Taxa de Saque Pré-Pago Visa',
            self::FeeSubscription => 'Tarifa Mensalidade',
            self::CreditBackoffice => 'Ajuste a Credito Backoffice',
            self::PhoneRecharge => 'Recarga de Celular',
            self::RefundPayment => 'Pgto Contas nao Processado',
            self::RefundPhoneRecharge => 'Rec Celular nao Processado',
            self::RefundBankTransfer => 'Est Transf Bancaria Enviada',
            self::RefundTransferSent => 'Estorno Transf Ctas-Remetente',
            self::RefundTransferReceived => 'Estorno Transf Ctas-Favorecido',
            self::RefundPix => 'Pix Env Não Processado',
            self::FeeBankSlip => 'Tarifa Rec Deposito Boleto',
            self::PixReversalReceived => 'Devolucao Pix Enviado',
            self::PixReversalSent => 'Devolucao Pix Recebido',
            self::FeeIof => 'IOF Transacoes Exterior R$',
            self::BacenjudLock => '655 Bloq Bacenjud-Cta Pre-Paga',
            self::BacenjudTransfer => '655 Transferencia Bacenjud',
            self::BacenjudUnlock => '655 Desbloqueio Bacenjud',
            self::DailyIncome => 'Remuneração-Cta Pgto',
        };
    }

    public static function novaBadgeMap(): array
    {
        $mappedItems = [];
        foreach (self::cases() as $case) {
            $mappedItems[$case->value] = 'info';
        }

        return $mappedItems;
    }

    public function isCardOperation(): bool
    {
        return in_array($this, [self::PurchaseNational, self::PurchaseInternational, self::Withdrawal]);
    }

    public function isFeeOperation(): bool
    {
        return in_array($this, [self::FeeWithdrawal, self::FeeIof]);
    }
}
