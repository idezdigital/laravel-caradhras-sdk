<?php

namespace Idez\Caradhras\Enums\Transaction;

enum TransactionOperation: string
{
    case PixSentTransactionalAccount = 'Pix Enviado-Conta Transacional';
    case PixReceivedTransactionalAccount = 'Pix Recebido-Cta Transacional';
    case VisaPrepaidNationalWithdrawal = 'Saque Nacional Pré-Pago Visa';
    case TransferBetweenAccountsSent = 'Transf entre Contas-Remetente';
    case BankTransferSent = 'Transf Bancaria Enviada';
    case BankTransferReceived = 'Transf Bancaria Recebida';
    case BankslipDepositRecharge = 'Recarga Deposito Boleto';
    case SuccesfulPayment = 'Pagamento Efetuado';
    case BankTransferFeeSent = 'Tarifa Transf Bancaria Enviada';
    case TransferBetweenAccountsFeeReceived = 'Tarifa Transf entre Contas';
    case PrepaidNationalVisaPurchase = 'Compra Pré-Pago Visa Nacional';
    case TransferBetweenAccountsReceived = 'Transf entre Contas-Favorecido';
    case PrepaidInternationalVisaPurchase = 'Compra Pré-Pago Visa Ext';
    case BillPayment = 'Pagamento de Contas';
    case VIsaPrepaidWithdrawalFee = 'Taxa de Saque Pré-Pago Visa';
    case MonthlyFee = 'Tarifa Mensalidade';
    case BackofficeCreditAjustment = 'Ajuste a Credito Backoffice';
    case PhoneRecharge = 'Recarga de Celular';
    case UnprocessedBillPaymentRefund = 'Pgto Contas nao Processado';
    case UnprocessedPhoneRechargeRefund = 'Rec Celular nao Processado';
    case SentBankTransferRefund = 'Est Transf Bancaria Enviada';
    case TransferBetweenAccountsReceiverRefund = 'Estorno Transf Ctas-Remetente';
    case TransferBetweenAccountsSenderRefund = 'Estorno Transf Ctas-Favorecido';
    case UnprocessedPixRefund = 'Pix Env Não Processado';
    case BankslipDepositFeeReceived = 'Tarifa Rec Deposito Boleto';
    case SentPixRefund = 'Devolucao Pix Enviado';
    case ReceivedPixRefund = 'Devolucao Pix Recebido';
    case IofFee = 'IOF Transacoes Exterior R$';
    case BacenjudLock = '655 Bloq Bacenjud-Cta Pre-Paga';
    case BacenjudTransfer = '655 Transferencia Bacenjud';
    case BacenjudUnlock = '655 Desbloqueio Bacenjud';
    case DailyIncome = 'Remuneração-Cta Pgto';
}
