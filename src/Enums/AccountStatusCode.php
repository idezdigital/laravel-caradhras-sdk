<?php

namespace Idez\Caradhras\Enums;

enum AccountStatusCode: int
{
    case Active = 0;
    case Blocked = 1;
    case Canceled = 2;
    case BlockedNonPayment = 3;
    case BlockedExceedingLimit = 4;
    case BlockedIncorrectRegistration = 5;
    case CanceledByClient = 6;
    case ManualCancelation = 7;
    case LatePayment = 8;
    case Loss = 9;
    case Released = 10;
    case CanceledCPFChange = 11;
    case CanceledDeathOfAccountHolder = 14;
    case LatePaymentAgreement = 15;
    case BlockedBadCheck = 20;
    case AgreementWithBillingOffice = 21;
    case LossAgreement = 26;
    case Removal = 27;
    case Dismissal = 28;
    case MarginLoss = 29;
    case LegalAction = 30;
    case MaternityLeave = 31;
    case BlockedFraud = 33;
    case DefinitelyCanceledFraud = 34;
    case BlockedPartner = 35;
    case RecoverableBalancePrepaid = 109;
    case AccountNotActivated = 200;
    case DefinitivelyCanceledAML = 201;

    public static function canceled(): array
    {
        return [
            self::Canceled,
            self::CanceledByClient,
            self::ManualCancelation,
            self::CanceledCPFChange,
            self::DefinitelyCanceledFraud,
            self::CanceledByClient,
            self::DefinitivelyCanceledAML,
            self::CanceledDeathOfAccountHolder,
        ];
    }

    public static function blocked(): array
    {
        return [
            self::Blocked,
            self::BlockedNonPayment,
            self::BlockedExceedingLimit,
            self::BlockedIncorrectRegistration,
            self::BlockedBadCheck,
            self::BlockedFraud,
            self::BlockedPartner,
        ];
    }

    public function canReactivate(): bool
    {
        return in_array($this, [
            self::Blocked,
            self::BlockedNonPayment,
        ]);
    }

    public static function isCanceled(AccountStatusCode $status): bool
    {
        return in_array($status, self::canceled());
    }

    public function isBlocked(): bool
    {
        return in_array($this, self::blocked());
    }

    public function is(AccountStatusCode $status): bool
    {
        return $this === $status;
    }
}
