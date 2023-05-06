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
/* ====== Sign =======*/
Route::post('login', 'AuthController@login')->name('login');

Route::middleware(['auth:sanctum'])->group(function () {
    /* ====== Logout =======*/
    Route::post('logout', 'AuthController@logout');
    /* ====== Messages =======*/
    Route::apiResource('messages', 'MessagesController', ['only' => ['index', 'store']]);
});
