<?php

use App\Http\Controllers\APIConsumerController;
use App\Http\Controllers\RestaurantController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\DB;

Route::get('/', function () {
    $dateTime = DB::select('SELECT CURRENT_TIMESTAMP AS current_timestamp');
    return "Hello world, today is " . $dateTime[0]->current_timestamp;
});

Route::group([ 'prefix' => 'consumer' ], function () {
    Route::post('/', [ APIConsumerController::class, 'createAPIConsumer' ])->name('createAPIConsumer');
});

Route::group([ 'prefix' => 'reservation', 'middleware' => [ 'trimHeaders', 'jwtAuth', 'basicAuth' ] ], function () {
    Route::get('/jwt', function () {
        return "Hey with JWT Auth Middleware";
    })->withoutMiddleware('basicAuth');

    Route::get('/basic', function () {
        return "Hey with Basic Auth Middleware";
    })->withoutMiddleware('jwtAuth');

    Route::group([ 'prefix' => 'restaurant' ], function () {
        Route::post('/', [ RestaurantController::class, 'createRestaurant' ])->name('createRestaurant')->withoutMiddleware([ 'jwtAuth', 'basicAuth' ]);
    });
});
