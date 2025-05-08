<?php

use App\Http\Controllers\Api\studentController;
use Illuminate\Http\Request;
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

Route::get('/students', studentController::class . '@index');
Route::get('/students/{id}', studentController::class . '@show');
Route::post('/students', studentController::class . '@store');
Route::put('/students/{id}', studentController::class . '@update');
Route::delete('/students/{id}', studentController::class . '@destroy');
