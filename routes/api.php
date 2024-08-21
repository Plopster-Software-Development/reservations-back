<?php

use App\Http\Controllers\APIConsumerController;
use App\Http\Controllers\RestaurantController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\DB;

Route::group([ 'middleware' => [ 'trimHeaders', 'jwtAuth', 'basicAuth' ] ], function () {
    Route::get('/', function () {
        $dateTime = DB::select('SELECT CURRENT_TIMESTAMP AS current_timestamp');
        return "Hello world, today is " . $dateTime[0]->current_timestamp;
    })->withoutMiddleware([ 'jwtAuth', 'basicAuth' ]);

    Route::group([ 'prefix' => 'consumer' ], function () {
        Route::post('/', [ APIConsumerController::class, 'createAPIConsumer' ])->name('createAPIConsumer');
    });

    Route::group([ 'prefix' => 'restaurant' ], function () {
        Route::post('/', [ RestaurantController::class, 'createRestaurant' ])->name('createRestaurant')->withoutMiddleware([ 'jwtAuth', 'basicAuth' ]);

        Route::group([ 'prefix' => 'user' ], function () {
            Route::post('/', function () {
                return "Create User";
            });

            Route::get('/{id}', function () {
                return "Find User";
            });

            Route::get('/', function () {
                return "Fetch Users";
            });

            Route::put('/{id}', function () {
                return "Update User";
            });

            Route::delete('/{id}', function () {
                return "Delete User";
            });
        });
    })->withoutMiddleware([ 'jwtAuth', 'basicAuth' ]);

    Route::group([ 'prefix' => 'payment' ], function () {
        Route::post('/', function () {
            return "payment";
        });
    });

    Route::group([ 'prefix' => 'reservation' ], function () {
        Route::get('/jwt', function () {
            return "Hey with JWT Auth Middleware";
        })->withoutMiddleware('basicAuth');

        Route::get('/basic', function () {
            return "Hey with Basic Auth Middleware";
        })->withoutMiddleware('jwtAuth');
    });
});
