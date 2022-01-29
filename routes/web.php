<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LeaguesController;
use App\Http\Controllers\SeasonsController;
use App\Http\Controllers\SquadController;
use App\Http\Controllers\TeamsController;
use App\Http\Controllers\IccrankingsController;
use App\Http\Controllers\FixtureController;

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
    $route->group(['prefix' => '/{id}/season/{seasonId}'], function($r) {
        $r->get('/', [SquadController::class, 'index']);
        $r->get('/{fixtureType}', [SquadController::class, 'getHomeFixtures']);
    });
});

Route::group(['prefix' => 'teams'], function($route) {
    $route->get('/', [TeamsController::class, 'index']);
});

Route::group(['prefix' => 'icc-rankings'], function($route) {
    $route->get('/{slug}/{type}', [IccrankingsController::class, 'getRankings']);
});

Route::group(['prefix' => 'fixture'], function($route) {
    $route->get('/{id}', [FixtureController::class, 'index']);
    $route->get('/', [FixtureController::class, 'listing']);
});