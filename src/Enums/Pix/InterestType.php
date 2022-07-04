<?php

namespace Idez\Caradhras\Enums\Pix;

use App\Contracts\Caradhras\CrType;

enum InterestType: string implements CrType
{
    case DailyFixed = 'daily_fixed';
    case DailyPercentual = 'daily_percentual';
    case MonthlyPercentual = 'monthly_percentual';
    case YearlyPercentual = 'yearly_percentual';
    case DailyBusinessFixed = 'fixed_business_day';
    case DailyBusinessPercentual = 'percentual_business_day';
    case MonthlyBusinessPercentual = 'percentual_business_month';
    case YearlyBusinessPercentual = 'percentual_business_year';

    public function crType(): string
    {
        return match ($this) {
            self::DailyFixed => 'VALUE_CALENDAR_DAYS',
            self::DailyPercentual => 'PERCENTAGE_PER_DAY_CALENDAR_DAYS',
            self::MonthlyPercentual => 'PERCENTAGE_PER_MONTH_CALENDAR_DAYS',
            self::YearlyPercentual => 'PERCENTAGE_PER_YEAR_CALENDAR_DAYS',
            self::DailyBusinessFixed => 'VALUE_WORKING_DAYS',
            self::DailyBusinessPercentual => 'PERCENTAGE_PER_DAYWORKING_DAYS',
            self::MonthlyBusinessPercentual => 'PERCENTAGE_PER_MONTH_WORKING_DAYS',
            self::YearlyBusinessPercentual => 'PERCENTAGE_PER_YEAR_WORKING_DAYS',
        };
    }
}
