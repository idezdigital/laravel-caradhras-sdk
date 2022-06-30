<?php

namespace Idez\Caradhras\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @see \Idez\Caradhras\Caradhras
 */
class Caradhras extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'laravel-caradhras-sdk';
    }
}
