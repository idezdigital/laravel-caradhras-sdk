<?php

namespace Idez\Caradhras\Enums\Pix;
enum PixTransferStatus: string
{
    case Executed = 'EXECUTED';
    case NotExecuted = 'NOT_EXECUTED';
    case Pending = 'PENDING';
}
