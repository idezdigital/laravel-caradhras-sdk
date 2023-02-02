<?php

namespace Idez\Caradhras\Data;

enum AccountStatusCode: int
{
    case Active = 0;
    case Blocked = 1;
    case Canceled = 2;
    case BlockedFraud = 33;
}
