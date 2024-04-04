<?php

namespace Idez\Caradhras\Enums\Pix;

use Idez\Caradhras\Contracts\CrType;

enum DiscountType: string implements CrType
{
    case Fixed = 'fixed';
    case FixedBusinessDay = 'fixed_business_day';
    case Percentual = 'percentual';
    case PercentualBusinessDay = 'percentual_business_day';

    public function crType(): string
    {
        return match ($this) {
            self::Fixed => 'AMOUNT_PER_CALENDAR_DAY_ADVANCE',
            self::FixedBusinessDay => 'AMOUNT_ADVANCE_BUSINESS_DAY',
            self::Percentual => 'PERCENTAGE_ADVANCE_CURRENT_DAY',
            self::PercentualBusinessDay => 'PERCENTAGE_ADVANCE_BUSINESS_DAY',
        };
    }
}
