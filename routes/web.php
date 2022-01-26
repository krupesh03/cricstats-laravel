<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LeaguesController;
use App\Http\Controllers\SeasonsController;
use App\Http\Controllers\LandingpageController;
use App\Http\Controllers\IccrankingsController;
use App\Http\Controllers\SquadController;

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
Route::get('/', [LeaguesController::class, 'index']);

Route::group(['prefix' => 'seasons'], function($route) {
    $route->get('/{leagueid}', [SeasonsController::class, 'index']);
    $route->get('/{leagueid}/teams/{seasonid}', [SeasonsController::class, 'getTeams']);
});

Route::group(['prefix' => 'squads'], function($route) {
    $route->get('/{id}/season/{seasonId}', [SquadController::class, 'index']);
});

Route::group(['prefix' => 'icc-rankings'], function($route) {
    $route->get('/{slug}/{type}', [IccrankingsController::class, 'getRankings']);
});

Route::group(['prefix' => 'teams'], function($route) {
    $route->get('/', [LandingpageController::class, 'index']);
});


