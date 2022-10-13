<?php

namespace Idez\Caradhras\Enums;

enum AccountStatus: int
{
    case Active = 1;
    case Canceled = 2;
    case Blocked = 3;
}
