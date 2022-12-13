<?php

namespace Idez\Caradhras\Enums\BankTransfer;

enum BankAccountType: string
{
    case Checking = 'CC';
    case Payment = 'PA';
    case Saving = 'SA';
}
