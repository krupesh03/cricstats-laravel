<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LandingpageController;
use App\Http\Controllers\IccrankingsController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
Route::get('/', [LandingpageController::class, 'index']);

Route::group(['prefix' => 'icc-rankings'], function() {
    Route::get('/{slug}/{type}', [IccrankingsController::class, 'getRankings']);
});


