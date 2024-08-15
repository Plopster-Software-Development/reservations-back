<?php

namespace App\Http\Controllers;

use App\Traits\ResponseHandler;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

abstract class Controller
{
    use ResponseHandler, AuthorizesRequests;
}
