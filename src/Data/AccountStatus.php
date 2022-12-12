<?php

namespace Idez\Caradhras\Data;

use Idez\Caradhras\Enums\SPDStatusCode;
use Idez\Caradhras\Traits\NovaSelectEnum;
use Idez\Caradhras\Contracts\NovaSelectEnumInterface;

enum AccountStatus: string implements NovaSelectEnumInterface
{
    use NovaSelectEnum;

    case Queued = 'queued';
    case Pending = 'pending';
    case UnderReview = 'under_review';
    case WaitingDocuments = 'waiting_documents';
    case Denied = 'denied';
    case New = 'new';
    case Active = 'active';
    case Blocked = 'blocked';
    case Canceled = 'canceled';
    case Failed = 'failed';
    case WaitingAnalysis = 'waiting_analysis';

    public function label(): string
    {
        return match ($this) {
            self::Queued => 'Processando',
            self::Pending => 'Pendente',
            self::UnderReview => 'Em Revisão',
            self::WaitingDocuments => 'Aguardando Documentos',
            self::Denied => 'Recusada',
            self::New => 'Nova',
            self::Active => 'Ativa',
            self::Blocked => 'Bloqueada',
            self::Canceled => 'Cancelada',
            self::Failed => 'Falha na Criação',
            self::WaitingAnalysis => 'Aguardando Análise',
        };
    }

    public static function novaColors(): array
    {
        return [
            self::Queued->value => 'rgb(157, 157, 157)',
            self::Pending->value => 'rgb(71, 193, 191)',
            self::New->value => 'rgb(22, 147, 235)',
            self::WaitingDocuments->value => 'rgb(242, 203, 34)',
            self::WaitingAnalysis->value => 'rgb(71, 193, 191)',
            self::UnderReview->value => 'rgb(249, 144, 55)',
            self::Active->value => 'rgb(9, 143, 86)',
            self::Blocked->value => 'rgb(245, 87, 59)',
            self::Canceled->value => 'rgb(242, 0, 0)',
            self::Denied->value => 'rgb(249, 144, 55)',
            self::Failed->value => 'rgb(242, 0, 0)',

        ];
    }

    public static function novaMap(): array
    {
        return [
            self::Queued->value => 'info',
            self::Pending->value => 'info',
            self::UnderReview->value => 'info',
            self::WaitingDocuments->value => 'warning',
            self::Denied->value => 'danger',
            self::New->value => 'info',
            self::Active->value => 'success',
            self::Blocked->value => 'danger',
            self::Canceled->value => 'danger',
            self::Failed->value => 'danger',
            self::WaitingAnalysis->value => 'warning',
        ];
    }

    public static function mapFromCaradhras(AccountStatusCode $code): self
    {
        return match ($code) {
            AccountStatusCode::Active => self::Active,
            AccountStatusCode::Blocked,
            AccountStatusCode::BlockedFraud => self::Blocked,
            AccountStatusCode::Canceled => self::Canceled,
        };
    }

    public static function mapFromSPD(SPDStatusCode $code)
    {
        return match ($code) {
            SPDStatusCode::BlockedDueToIncompleteRegistration => AccountStatus::WaitingDocuments,
            SPDStatusCode::BlockedDueToInvalidDocuments,
            SPDStatusCode::BlockingByJudicialOrder,
            SPDStatusCode::SelfPendingBlock,
            SPDStatusCode::BlockingPendingDocuments,
            SPDStatusCode::BlockingByIrregularCpf,
            SPDStatusCode::BlockingByCourtNotification,
            SPDStatusCode::WaitingDocumentsCashInAllowed,
            SPDStatusCode::BlockedDueInactivity,
            SPDStatusCode::CommercialDisinterest,
            SPDStatusCode::BlockedByRiskyPoliceResendDocuments,
            SPDStatusCode::BlockedByRiskyPolice,
            SPDStatusCode::BlockedFraudConfirmed,
            SPDStatusCode::BlockedBySanitization,
            SPDStatusCode::BlockedBySanitizationResendDocuments,
            SPDStatusCode::BlockedBySanitizationDocumentaryAdjustment,
            SPDStatusCode::BlockedIncompleteDocumentation,
            SPDStatusCode::BlockedClosingRegistration,
            SPDStatusCode::BlockedViolationAlert,
            SPDStatusCode::BlockedViolationSuspected,
            SPDStatusCode::BlockingDueToOutdatedRegistration,
            SPDStatusCode::PreventFraudBlocking,
            SPDStatusCode::BlockingDueToDataPending => AccountStatus::Blocked,
            SPDStatusCode::BlockedByDocumentAnalysisCashInAllowed,
            SPDStatusCode::BlockedByDocumentAnalysisCashFullBlock => AccountStatus::UnderReview,
        };
    }

    public function canAuthenticate(): bool
    {
        return ! in_array($this, [
            self::Denied,
            self::Canceled,
            self::Failed,
            self::Blocked
        ]);
    }

    public function is(self $status): bool
    {
        return $this === $status;
    }
}
