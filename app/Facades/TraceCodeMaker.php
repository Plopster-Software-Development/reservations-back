<?php

namespace App\Facades;

use Illuminate\Support\Facades\Facade;

class TraceCodeMaker extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'tracecodemaker';
    }
}
