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
        Route::resource(name: 'user', controller: UsersController::class)->only(methods: [ 'index', 'store', 'show', 'update', 'destroy' ])
            ->withoutMiddleware([ BasicAuthMiddleware::class]);
    });

    Route::post(uri: '/', action: [ RestaurantController::class, 'store' ])->withoutMiddleware(middleware: JWTAuthMiddleware::class);
    Route::resource('/', RestaurantController::class)->only(methods: [ 'index', 'show', 'update', 'destroy' ])
        ->withoutMiddleware([ BasicAuthMiddleware::class]);

    Route::resource('table', TablesController::class)->only([ 'index', 'show', 'store', 'update', 'destroy' ])
        ->withoutMiddleware([ BasicAuthMiddleware::class]);
});

Route::resource('reservation', ReservationsController::class)->only([ 'index', 'show', 'store', 'update', 'destroy' ])
    ->withoutMiddleware([ JWTAuthMiddleware::class]);

Route::group([ 'prefix' => 'payment' ], function () {
    Route::post('/', function () {
        return "payment";
    });
});