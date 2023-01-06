<?php


use Idez\Caradhras\Enums\Transaction\TransactionOperation;

return [
    TransactionOperation::CreditBackoffice->value => 'Crédito Backoffice',
    TransactionOperation::CreditCustom->value => 'Crédito',
    TransactionOperation::Deposit->value => 'Depósito em Conta',
    TransactionOperation::ChargeReceived->value => 'Cobrança Recebida',
    TransactionOperation::DonationSent->value => 'Doação Enviada',
    TransactionOperation::DonationReceived->value => 'Doação Recebida',
    TransactionOperation::FeeBankSlip->value => 'Taxa Boleto',
    TransactionOperation::FeeCardReissued->value => 'Taxa Reemissão de Cartão',
    TransactionOperation::FeeCustom->value => 'Taxa Personalizada',
    TransactionOperation::FeeIof->value => 'Tarifa de IOF',
    TransactionOperation::FeeSubscription->value => 'Mensalidade',
    TransactionOperation::FeeBankTransfer->value => 'Taxa TED',
    TransactionOperation::FeeTransfer->value => 'Taxa Transferência',
    TransactionOperation::FeeWithdrawal->value => 'Taxa Saque',
    TransactionOperation::FeeWithdrawalInternational->value => 'Taxa Saque Internacional',
    TransactionOperation::TransferReceived->value => 'P2P Recebida',
    TransactionOperation::TransferSent->value => 'P2P Enviada',
    TransactionOperation::PaymentSent->value => 'Pagamento de Título',
    TransactionOperation::PixReceived->value => 'Pix Recebido',
    TransactionOperation::PixSent->value => 'Pix Enviado',
    TransactionOperation::PixReversalSent->value => 'Reembolso Pix Enviado',
    TransactionOperation::PixReversalReceived->value => 'Reembolso Pix Recebido',
    TransactionOperation::PhoneRecharge->value => 'Recarga de Celular',
    TransactionOperation::PurchaseInternational->value => 'Compra Internacional',
    TransactionOperation::PurchaseNational->value => 'Compra Nacional',
    TransactionOperation::RefundIof->value => 'Estorno de IOF',
    TransactionOperation::RefundPayment->value => 'Estorno de Pagamento',
    TransactionOperation::RefundPhoneRecharge->value => 'Estorno de Recarga de Celular',
    TransactionOperation::RefundPurchaseInternational->value => 'Estorno de Compra Internacional',
    TransactionOperation::RefundBankTransfer->value => 'Estorno de TED',
    TransactionOperation::RefundTransferSent->value => 'Estorno de P2P Enviada',
    TransactionOperation::RefundTransferReceived->value => 'Estorno de P2P Recebida',
    TransactionOperation::RefundPix->value => 'Estorno de Pix',
    TransactionOperation::BankTransferReceived->value => 'TED Recebida',
    TransactionOperation::BankTransferSent->value => 'TED Enviada',
    TransactionOperation::InvoicePaid->value => 'Pagamento de Tarifa',
    TransactionOperation::InvoiceReceived->value => 'Tarifa Recebida',
    TransactionOperation::InstallmentPaid->value => 'Pagamento de parcela de empréstimo',
    TransactionOperation::InstallmentReceived->value => 'Parcela de empréstimo recebida',
    TransactionOperation::CashbackSent->value => 'Cashback Enviado',
    TransactionOperation::CashbackReceived->value => 'Cashback Recebido',
    TransactionOperation::CommissionPaid->value => 'Comissão Paga',
    TransactionOperation::CommissionReceived->value => 'Comissão Recebida',
    TransactionOperation::BucketWithdrawalSent->value => 'Resgate de subconta enviado',
    TransactionOperation::BucketWithdrawalReceived->value => 'Resgate de subconta recebido',
    TransactionOperation::BucketCashbackWithdrawalSent->value => 'Resgate cashback enviado',
    TransactionOperation::BucketCashbackWithdrawalReceived->value => 'Resgate cashback recebido',
    TransactionOperation::QrPaymentSale->value => 'Venda com QR Code',
    TransactionOperation::QrPaymentPurchase->value => 'Compra com QR Code',
    TransactionOperation::Withdrawal->value => 'Saque',
    TransactionOperation::DailyIncome->value => 'Rendimento Diário',
    TransactionOperation::Other->value => 'Outra Transação',
    TransactionOperation::BacenjudLock->value => 'Impedimento por Bacenjud - Conta Pré-Paga',
    TransactionOperation::BacenjudTransfer->value => 'Transferência Bacenjud',
    TransactionOperation::BacenjudUnlock->value => 'Desbloqueio Bacenjud',
];
