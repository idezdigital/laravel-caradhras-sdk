<?php

namespace Idez\Caradhras\Enums\Transactions;

enum TransactionDescription: string
{
    case PixSent = 'Pix Enviado-Conta Transacional';
    case PixReceived = 'Pix Recebido-Cta Transacional';
    case Withdrawal = 'Saque Nacional Pré-Pago Visa';
    case TransferSent = 'Transf entre Contas-Remetente';
    case BankTransferSent = 'Transf Bancaria Enviada';
    case BankTransferReceived = 'Transf Bancaria Recebida';
    case Deposit = 'Pagamento Efetuado';
    case FeeBankTransfer = 'Tarifa Transf Bancaria Enviada';
    case FeeTransfer = 'Tarifa Transf entre Contas';
    case PurchaseNational = 'Compra Pré-Pago Visa Nacional';
    case TransferReceived = 'Transf entre Contas-Favorecido';
    case PurchaseInternational = 'Compra Pré-Pago Visa Ext';
    case PaymentSent = 'Pagamento de Contas';
    case FeeWithdrawal = 'Taxa de Saque Pré-Pago Visa';
    case FeeSubscription = 'Tarifa Mensalidade';
    case CreditBackoffice = 'Ajuste a Credito Backoffice';
    case PhoneRecharge = 'Recarga de Celular';
    case RefundPayment = 'Pgto Contas nao Processado';
    case RefundPhoneRecharge = 'Rec Celular nao Processado';
    case RefundBankTransfer = 'Est Transf Bancaria Enviada';
    case RefundTransferSent = 'Estorno Transf Ctas-Remetente';
    case RefundTransferReceived = 'Estorno Transf Ctas-Favorecido';
    case RefundPix = 'Pix Env Não Processado';
    case FeeBankSlip = 'Tarifa Rec Deposito Boleto';
    case PixReversalReceived = 'Devolucao Pix Enviado';
    case PixReversalSent = 'Devolucao Pix Recebido';
    case FeeIof = 'IOF Transacoes Exterior R$';
    case BacenjudLock = '301 Bloq Bacenjud-Cta Pre-Paga';
    case BacenjudTransfer = '301 Transferencia Bacenjud';
    case BacenjudUnlock = '301 Desbloqueio Bacenjud';
    case PaymentTerminalReceived = '301 Recebiveis-Cta Pre-Paga';
    case DailyIncome = 'Remuneração-Cta Pgto';
}
