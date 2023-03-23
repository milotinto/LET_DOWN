<?php

use App\Http\Controllers\AuthController;
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
Route::apiResource('eva01/users', '\App\Http\Controllers\AuthController');
Route::apiResource('eva01/publicaciones', '\App\Http\Controllers\Publicacion_Controller');
Route::apiResource('eva01/item', '\App\Http\Controllers\Item_Controller');

Route::group([

    'middleware' => 'api',
    'prefix' => 'eva02'

], function () {

    Route::post('login', [AuthController::class, 'login']);
    Route::post('logout', [AuthController::class, 'logout']);
    Route::post('refresh', [AuthController::class, 'refresh']);
    Route::post('register', [AuthController::class, 'register']);
    Route::post('me', [AuthController::class, 'me']);
    Route::middleware('jwt.auth')->put('update_user/{id}', [AuthController::class, 'update']);
    Route::middleware('jwt.auth')->delete('delete_user/{id}', [AuthController::class, 'delete']);

});
