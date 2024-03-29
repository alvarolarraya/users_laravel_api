<?php

use App\Infrastructure\Controllers\GetUserController;
use App\Infrastructure\Controllers\IsEarlyAdopterUserController;
use App\Infrastructure\Controllers\GetStatusController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/


Route::get('/status', GetStatusController::class);

Route::get('/user/{email}', GetUserController::class);

Route::get('/users', \App\Infrastructure\Controllers\GetUsersListController::class);

Route::get('/early_adopter/{email}', IsEarlyAdopterUserController::class);
