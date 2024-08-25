<?php

use App\Http\Controllers\APIConsumerController;
use App\Http\Controllers\ReservationsController;
use App\Http\Controllers\RestaurantController;
use App\Http\Controllers\TableController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\DB;

Route::group([ 'middleware' => [ 'trimHeaders', 'jwtAuth', 'basicAuth' ] ], function () {
    Route::get('/', function () {
        $dateTime = DB::select('SELECT CURRENT_TIMESTAMP AS current_timestamp');
        return "Hello world, today is " . $dateTime[0]->current_timestamp;
    })->withoutMiddleware([ 'jwtAuth', 'basicAuth' ]);

    Route::group([ 'prefix' => 'consumer' ], function () {
        Route::post('/', [ APIConsumerController::class, 'createAPIConsumer' ]);
    });

    Route::group([ 'prefix' => 'restaurant' ], function () {
        Route::post('/', [ RestaurantController::class, 'createRestaurant' ])->withoutMiddleware([ 'jwtAuth' ]);

        Route::resource('user/', UserController::class)->withoutMiddleware('basicAuth');
        Route::post('user/authenticate', [ UserController::class, 'authenticate' ])->withoutMiddleware([ 'jwtAuth' ]);

        Route::resource('table/', TableController::class)->withoutMiddleware('basicAuth');
    });

    Route::group([ 'prefix' => 'payment' ], function () {
        Route::post('/', function () {
            return "payment";
        });
    });

    Route::resource('reservation/', ReservationsController::class)->withoutMiddleware('basicAuth');

});
