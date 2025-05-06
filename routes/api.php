<?php

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

Route::get('/students', function () {
    return 'Obteniendo la lista de estudiantes';
});

Route::get('/students/{id}', function ($id) {
    return 'Obteniendo el estudiante con id: ' . $id;
});

Route::post('/students', function () {
    return 'Creando un nuevo estudiante';
});

Route::put('/students/{id}', function ($id) {
    return 'Actualizando el estudiante con id: ' . $id;
});

Route::delete('/students/{id}', function ($id) {
    return 'Eliminando el estudiante con id: ' . $id;
});
