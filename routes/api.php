<?php

use App\Http\Controllers\APIConsumerController;
use App\Http\Controllers\ReservationsController;
use App\Http\Controllers\RestaurantController;
use App\Http\Controllers\UserController;
use App\Http\Middleware\BasicAuthMiddleware;
use App\Http\Middleware\JWTAuthMiddleware;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\DB;

Route::get('/', function () {
    $dateTime = DB::select('SELECT CURRENT_TIMESTAMP AS current_timestamp');
    return "Hello world, today is " . $dateTime[0]->current_timestamp;
});

Route::middleware([ JWTAuthMiddleware::class, BasicAuthMiddleware::class])->group(function () {
    Route::post(uri: 'consumer/', action: [ APIConsumerController::class, 'createAPIConsumer' ]);

    Route::group([ 'prefix' => 'restaurant' ], function () {
        Route::withoutMiddleware([ JWTAuthMiddleware::class])->group(function () {
            Route::post('/', action: [ RestaurantController::class, 'store' ]);
            Route::post('user/authenticate', action: [ UserController::class, 'authenticate' ]);
        });

        Route::withoutMiddleware([ BasicAuthMiddleware::class])->group(function () {
            Route::get('user/', [ UserController::class, 'index' ]);
            Route::get('user/{id}', [ UserController::class, 'show' ]);
            Route::post('user/', [ UserController::class, 'store' ]);
            Route::put('user/{id}', [ UserController::class, 'update' ]);
            Route::delete('user/{id}', [ UserController::class, 'destroy' ]);

            Route::get('/', [ RestaurantController::class, 'index' ]);
            Route::get('/{id}', [ RestaurantController::class, 'show' ]);
            Route::put('/{id}', [ RestaurantController::class, 'update' ]);
            Route::delete('/{id}', [ RestaurantController::class, 'destroy' ]);
            // Route::resource('table/', TableController::class);
        });
    });

    Route::group([ 'prefix' => 'reservation' ], function () {
        Route::withoutMiddleware([ BasicAuthMiddleware::class])->group(function () {
            Route::get('/', [ ReservationsController::class, 'index' ]);
            Route::get('/{id}', [ ReservationsController::class, 'show' ]);
            Route::post('/', [ ReservationsController::class, 'store' ]);
            Route::put('/{id}', [ ReservationsController::class, 'update' ]);
            Route::delete('/{id}', [ ReservationsController::class, 'destroy' ]);
        });

    });
});

Route::group([ 'prefix' => 'payment' ], function () {
    Route::post('/', function () {
        return "payment";
    });
});