<?php

namespace Idez\Caradhras\Enums\BankAccount;

enum BankAccountType: string
{
    case Checking = 'CC';
    case Payment = 'PA';
    case Savings = 'SA';

    public function label()
    {
        return match ($this) {
            self::Checking => 'Conta Corrente',
            self::Payment => 'Conta de Pagamento',
            self::Savings => 'Poupança',
        };
    }
}
