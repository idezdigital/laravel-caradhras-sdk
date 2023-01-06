<?php

namespace Idez\Caradhras\Enums\Transaction;

use Idez\Caradhras\Contracts\NovaSelectEnumInterface;
use Idez\Caradhras\Traits\NovaSelectEnum;

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
            TransactionOperationDescription::PixSentTransactionalAccount->value => self::PixSent,
            TransactionOperationDescription::PixReceivedTransactionalAccount->value => self::PixReceived,
            TransactionOperationDescription::VisaPrepaidNationalWithdrawal->value => self::Withdrawal,
            TransactionOperationDescription::TransferBetweenAccountsSent->value => self::TransferSent,
            TransactionOperationDescription::BankTransferSent->value => self::BankTransferSent,
            TransactionOperationDescription::BankTransferReceived->value => self::BankTransferReceived,
            TransactionOperationDescription::BankslipDepositRecharge->value, TransactionOperationDescription::SuccesfulPayment->value => self::Deposit,
            TransactionOperationDescription::BankTransferFeeSent->value => self::FeeBankTransfer,
            TransactionOperationDescription::TransferBetweenAccountsFeeReceived->value => self::FeeTransfer,
            TransactionOperationDescription::PrepaidNationalVisaPurchase->value => self::PurchaseNational,
            TransactionOperationDescription::TransferBetweenAccountsReceived->value => self::TransferReceived,
            TransactionOperationDescription::PrepaidInternationalVisaPurchase->value => self::PurchaseInternational,
            TransactionOperationDescription::BillPayment->value => self::PaymentSent,
            TransactionOperationDescription::VIsaPrepaidWithdrawalFee->value => self::FeeWithdrawal,
            TransactionOperationDescription::MonthlyFee->value => self::FeeSubscription,
            TransactionOperationDescription::BackofficeCreditAjustment->value => self::CreditBackoffice,
            TransactionOperationDescription::PhoneRecharge->value => self::PhoneRecharge,
            TransactionOperationDescription::UnprocessedBillPaymentRefund->value => self::RefundPayment,
            TransactionOperationDescription::UnprocessedPhoneRechargeRefund->value => self::RefundPhoneRecharge,
            TransactionOperationDescription::SentBankTransferRefund->value => self::RefundBankTransfer,
            TransactionOperationDescription::TransferBetweenAccountsReceiverRefund->value => self::RefundTransferSent,
            TransactionOperationDescription::TransferBetweenAccountsSenderRefund->value => self::RefundTransferReceived,
            TransactionOperationDescription::UnprocessedPixRefund->value => self::RefundPix,
            TransactionOperationDescription::BankslipDepositFeeReceived->value => self::FeeBankSlip,
            TransactionOperationDescription::SentPixRefund->value => self::PixReversalReceived,
            TransactionOperationDescription::ReceivedPixRefund->value => self::PixReversalSent,
            TransactionOperationDescription::IofFee->value => self::FeeIof,
            TransactionOperationDescription::BacenjudLock->value => self::BacenjudLock,
            TransactionOperationDescription::BacenjudTransfer->value => self::BacenjudTransfer,
            TransactionOperationDescription::BacenjudUnlock->value => self::BacenjudUnlock,
            TransactionOperationDescription::DailyIncome->value => self::DailyIncome,
            default => self::Other,
        };
    }

    public function getDescription(self $operation): string
    {
        return match ($operation) {
            self::PixSent => TransactionOperationDescription::PixSentTransactionalAccount->value,
            self::PixReceived => TransactionOperationDescription::PixReceivedTransactionalAccount->value,
            self::Withdrawal => TransactionOperationDescription::VisaPrepaidNationalWithdrawal->value,
            self::TransferSent => TransactionOperationDescription::TransferBetweenAccountsSent->value,
            self::BankTransferSent => TransactionOperationDescription::BankTransferSent->value,
            self::BankTransferReceived => TransactionOperationDescription::BankTransferReceived->value,
            self::Deposit => TransactionOperationDescription::SuccesfulPayment->value,
            self::FeeBankTransfer => TransactionOperationDescription::BankTransferFeeSent->value,
            self::FeeTransfer => TransactionOperationDescription::TransferBetweenAccountsFeeReceived->value,
            self::PurchaseNational => TransactionOperationDescription::PrepaidNationalVisaPurchase->value,
            self::TransferReceived => TransactionOperationDescription::TransferBetweenAccountsReceived->value,
            self::PurchaseInternational => TransactionOperationDescription::PrepaidInternationalVisaPurchase->value,
            self::PaymentSent => TransactionOperationDescription::BillPayment->value,
            self::FeeWithdrawal => TransactionOperationDescription::VIsaPrepaidWithdrawalFee->value,
            self::FeeSubscription => TransactionOperationDescription::MonthlyFee->value,
            self::CreditBackoffice => TransactionOperationDescription::BackofficeCreditAjustment->value,
            self::PhoneRecharge => TransactionOperationDescription::PhoneRecharge->value,
            self::RefundPayment => TransactionOperationDescription::UnprocessedBillPaymentRefund->value,
            self::RefundPhoneRecharge => TransactionOperationDescription::UnprocessedPhoneRechargeRefund->value,
            self::RefundBankTransfer => TransactionOperationDescription::SentBankTransferRefund->value,
            self::RefundTransferSent => TransactionOperationDescription::TransferBetweenAccountsReceiverRefund->value,
            self::RefundTransferReceived => TransactionOperationDescription::TransferBetweenAccountsSenderRefund->value,
            self::RefundPix => TransactionOperationDescription::UnprocessedPixRefund->value,
            self::FeeBankSlip => TransactionOperationDescription::BankslipDepositFeeReceived->value,
            self::PixReversalReceived => TransactionOperationDescription::SentPixRefund->value,
            self::PixReversalSent => TransactionOperationDescription::ReceivedPixRefund->value,
            self::FeeIof => TransactionOperationDescription::IofFee->value,
            self::BacenjudLock => TransactionOperationDescription::BacenjudLock->value,
            self::BacenjudTransfer => TransactionOperationDescription::BacenjudTransfer->value,
            self::BacenjudUnlock => TransactionOperationDescription::BacenjudUnlock->value,
            self::DailyIncome => TransactionOperationDescription::DailyIncome->value,
            default => self::Other,
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
        return in_array($this, self::cardOperations());
    }

    public static function cardOperations(): array
    {
        return [self::PurchaseNational, self::PurchaseInternational, self::Withdrawal];
    }

    public function isFeeOperation(): bool
    {
        return in_array($this, [self::FeeWithdrawal, self::FeeIof]);
    }
}
