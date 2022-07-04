<?php

namespace Idez\Caradhras\Enums\Cards;

enum CardStatus: int
{
    case Active = 1;
    case BlockedTemporary = 2;
    case Canceled = 3;
    case CanceledLost = 4;
    case CanceledStolen = 5;
    case CanceledByClient = 6;
    case CanceledByBank = 7;
    case CanceledMisplaced = 8;
    case CanceledDamaged = 9;
    case CanceledScratched = 10;
    case CanceledEmbossing = 11;
    case CanceledSuspectedFraud = 12;
    case CanceledPostOfficeReturn = 13;
    case CanceledCardholderDeath = 14;
    case CanceledDeactivated = 15;
    case CanceledExpired = 16;
    case CanceledLeftInStore = 17;
    case Migrated = 18;
    case CanceledDefinitive = 20;
    case MigratedBlock = 25;
    case Reissued = 27;
    case ReissuedCancel = 28;
    case BlockedPassword = 29;
    case CanceledEmptyCvv = 30;
    case BlockedSuspectedFraud = 32;
    case BlockedPreventive = 37;
    case MoneyLaunderingPrevention = 171;
}
