<?php

use App\Http\Controllers\AddressController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\UserController;
use App\Http\Middleware\ApiAuthMiddleware;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::post('/users', [UserController::class, 'register']);
Route::post('/users/login', [UserController::class, 'login']);
Route::middleware(ApiAuthMiddleware::class)->group(function () {
    Route::get('/users/current', [UserController::class, 'get']);
    Route::patch('/users/current', [UserController::class, 'update']);
    Route::delete('/users/logout', [UserController::class, 'logout']);

    Route::post('/contact', [ContactController::class, 'create']);
    Route::get('/contacts', [ContactController::class, 'search']);
    Route::get('/contacts/{id}', [ContactController::class, 'get']);
    Route::put('/contacts/{id}', [ContactController::class, 'update']);
    Route::delete('/contacts/{id}', [ContactController::class, 'delete']);

    Route::get('/contacts/{id}/addresses', [AddressController::class, 'index']);
    Route::post('/contacts/{id}/addresses', [AddressController::class, 'create']);
    Route::get('/contacts/{contactid}/addresses/{addressid}', [AddressController::class, 'get']);
    Route::put('/contacts/{contactid}/addresses/{addressid}', [AddressController::class, 'update']);
    Route::delete('/contacts/{contactid}/addresses/{addressid}', [AddressController::class, 'delete']);
});
