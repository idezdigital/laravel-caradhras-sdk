<?php

namespace Idez\Caradhras\Enums\Pix;

enum DynamicQrTypes: string
{
    case Transfer = 'TRANSFER';
    case Change = 'CHANGE';
    case Withdrawal = 'WITHDRAWAL';
}
