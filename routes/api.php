<?php

use App\Http\Controllers\APIConsumerController;
use App\Http\Controllers\ReservationsController;
use App\Http\Controllers\RestaurantController;
use App\Http\Controllers\TablesController;
use App\Http\Controllers\UsersController;
use App\Http\Middleware\BasicAuthMiddleware;
use App\Http\Middleware\JWTAuthMiddleware;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\DB;

Route::get('/', function () {
    $dateTime = DB::select('SELECT CURRENT_TIMESTAMP AS current_timestamp');
    return "Hello world, today is " . $dateTime[0]->current_timestamp;
});

//TODO: 1. Crear mesas, 2. Gestionar reservas (todo, incluido notificacion de reservas), 3. Finalizar gestion de restaurantes y usuarios, 4. Gestion de roles(ruta y controlador), 5. Simulacion de pagos.

Route::post(uri: 'consumer/', action: [ APIConsumerController::class, 'createAPIConsumer' ]);


Route::group([ 'prefix' => 'restaurant', 'middleware' => [ JWTAuthMiddleware::class, BasicAuthMiddleware::class] ], function () {
    Route::post(uri: 'user/authenticate', action: [ UsersController::class, 'authenticate' ])->withoutMiddleware(middleware: JWTAuthMiddleware::class);
    Route::withoutMiddleware(BasicAuthMiddleware::class)->group(function () {
        Route::get('user/', [ UsersController::class, 'index' ]);
        Route::get('user/{id}', [ UsersController::class, 'show' ]);
        Route::post('user/', action: [ UsersController::class, 'store' ]);
        Route::put('user/{id}', [ UsersController::class, 'update' ]);
        Route::delete('user/{id}', [ UsersController::class, 'destroy' ]);
    });

    Route::post(uri: '/', action: [ RestaurantController::class, 'store' ])->withoutMiddleware(middleware: JWTAuthMiddleware::class);
    Route::withoutMiddleware(BasicAuthMiddleware::class)->group(function () {
        Route::get('/', action: [ RestaurantController::class, 'index' ]);
        Route::get('/{id}', [ RestaurantController::class, 'show' ]);
        Route::put('/{id}', [ RestaurantController::class, 'update' ]);
        Route::delete('/{id}', [ RestaurantController::class, 'destroy' ]);
    });

    Route::withoutMiddleware(BasicAuthMiddleware::class)->group(function () {
        Route::get(uri: 'table/', action: [ TablesController::class, 'index' ]);
        Route::get('table/{id}', [ TablesController::class, 'show' ]);
        Route::post('table/', action: [ TablesController::class, 'store' ]);
        Route::put('table/{id}', [ TablesController::class, 'update' ]);
        Route::delete('table/{id}', [ TablesController::class, 'destroy' ]);
    });
});

Route::group([ 'prefix' => 'reservation' ], function () {
    Route::withoutMiddleware([ JWTAuthMiddleware::class])->group(function () {
        Route::get('/', [ ReservationsController::class, 'index' ]);
        Route::get('/{id}', [ ReservationsController::class, 'show' ]);
        Route::post('/', [ ReservationsController::class, 'store' ]);
        Route::put('/{id}', [ ReservationsController::class, 'update' ]);
        Route::delete('/{id}', [ ReservationsController::class, 'destroy' ]);
    });
});


Route::group([ 'prefix' => 'payment' ], function () {
    Route::post('/', function () {
        return "payment";
    });
});