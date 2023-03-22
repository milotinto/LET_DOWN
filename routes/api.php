<?php

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



Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
