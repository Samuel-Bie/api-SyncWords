<?php

use App\Http\Controllers\AuthorizationController;
use App\Http\Controllers\EventController;
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

Route::post('token', [AuthorizationController::class, 'token']);

Route::middleware('auth:sanctum')->group(function () {
    Route::get('whoami', [AuthorizationController::class, 'whoami']);
});




/* This is how i would leave the */
// Route::middleware('auth:sanctum')->group(function () {
//     Route::apiResource('events', EventController::class);
// });


Route::middleware('auth:sanctum')->group(function () {
    Route::get('', [EventController::class, 'index']);
    Route::post('events', [EventController::class, 'store']);
    Route::get('{event}', [EventController::class, 'show']);
    Route::put('/{event}', [EventController::class, 'update']);
    Route::delete('/{event}', [EventController::class, 'destroy']);
});
