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

Route::group(['prefix' => 'events'], function () {
    Route::get('/', [App\Http\Controllers\EventController::class, 'index'])->name('events.index');
    Route::post('/', [App\Http\Controllers\EventController::class, 'store'])->name('events.store');
});


