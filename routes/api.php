<?php

use Illuminate\Support\Facades\Route;

Route::middleware(['userJwt', 'appOTC'])->group(function () {
    Route::get('/', function () {
        return "Hey with middleware";
    });

    Route::get('/hey', function () {
        return "Hey without middleware";
    })->withoutMiddleware(['userJwt', 'appOTC']);
});
