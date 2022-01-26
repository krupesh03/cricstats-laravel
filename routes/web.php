<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LeaguesController;
use App\Http\Controllers\SeasonsController;
use App\Http\Controllers\SquadController;
use App\Http\Controllers\TeamsController;
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
Route::get('/', [LeaguesController::class, 'index']);

Route::group(['prefix' => 'seasons'], function($route) {
    $route->get('/{leagueid}', [SeasonsController::class, 'index']);
    $route->get('/{leagueid}/teams/{seasonid}', [SeasonsController::class, 'getTeams']);
});

Route::group(['prefix' => 'squads'], function($route) {
    $route->get('/{id}/season/{seasonId}', [SquadController::class, 'index']);
});

Route::group(['prefix' => 'teams'], function($route) {
    $route->get('/', [TeamsController::class, 'index']);
});

Route::group(['prefix' => 'icc-rankings'], function($route) {
    $route->get('/{slug}/{type}', [IccrankingsController::class, 'getRankings']);
});

