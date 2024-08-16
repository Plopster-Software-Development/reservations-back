<?php

use Illuminate\Support\Facades\Route;

Route::middleware(['trimHeaders', 'basicAuth', 'jwtAuth'])->group(function () {
    Route::get('/', function () {
        return "Hey with middleware";
    });

    Route::get('/hey', function () {
        return "Hey without middleware";
    })->withoutMiddleware(['basicAuth', 'jwtAuth']);
});
