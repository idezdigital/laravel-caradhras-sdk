<?php

namespace Idez\Caradhras\Enums\Documents;

enum DocumentSelfieReasonCode: int
{
    case LowQuality = 1;
    case NotSelfie = 2;
    case FaceNotVisible = 3;
    case Inconsistent = 4;
}
