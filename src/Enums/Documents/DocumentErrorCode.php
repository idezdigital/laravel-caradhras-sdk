<?php

namespace Idez\Caradhras\Enums\Documents;

enum DocumentErrorCode: int
{
    case DuplicatedImage = 4001025;
    case InvalidDocument = 4001024;
    case InvalidSelfie = 4001023;
    case InvalidHeadersImage = 4001022;
    case InvalidHeadersDocument = 4001021;
    case InvalidFormat = 4001020;
}
