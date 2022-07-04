<?php

namespace Idez\Caradhras\Enums;

enum PaymentAmountRule: string
{
    case AnyAmount = 'any_amount';
    case BetweenMinimumAndMaximumAmount = 'between_minimum_and_maximum_amount';
    case FixedAmount = 'fixed_amount';
    case HasMinimumAmount = 'has_minimum_amount';

    public static function mapping(int $crRuleCode): PaymentAmountRule
    {
        return match ($crRuleCode) {
            0, 3 => self::FixedAmount,
            1 => self::AnyAmount,
            2 => self::BetweenMinimumAndMaximumAmount,
            4 => self::HasMinimumAmount,
        };
    }

    public function getCodeDescription(): string
    {
        return $this->value;
    }
}
