<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\LeaguesController;
use App\Http\Controllers\SeasonsController;
use App\Http\Controllers\SquadController;
use App\Http\Controllers\TeamsController;
use App\Http\Controllers\IccrankingsController;
use App\Http\Controllers\FixtureController;
use App\Http\Controllers\PlayerController;
use App\Http\Controllers\VenueController;
use App\Http\Controllers\OfficialsController;
use App\Http\Controllers\LivescoreController;
use App\Http\Controllers\NewsController;

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
Route::get('/', [HomeController::class, 'index']);

Route::get('/leagues', [LeaguesController::class, 'index']);

Route::group(['prefix' => 'seasons'], function($route) {
    $route->get('/{leagueid}', [SeasonsController::class, 'index']);
    $route->get('/{leagueid}/teams/{seasonid}', [SeasonsController::class, 'getTeams']);
    $route->get('/{leagueid}/teams/{seasonid}/standings', [LeaguesController::class, 'getStandings']);
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
    $route->get('/{leagueId}/season/{seasonId}', [FixtureController::class, 'seasonLeagueFixtures']);
    $route->get('/stage/{stageId}/{leagueId}/{seasonId}', [FixtureController::class, 'getStageData']);
});

Route::group(['prefix' => 'players'], function($route) {
    $route->get('/', [PlayerController::class, 'index']);
    $route->get('/{id}', [PlayerController::class, 'getPlayer']);
});

Route::group(['prefix' => 'venues'], function($route) {
    $route->get('/', [VenueController::class, 'index']);
    $route->get('/{id}', [VenueController::class, 'details']);
});

Route::group(['prefix' => 'officials'], function($route) {
    $route->get('/', [OfficialsController::class, 'index']);
});

Route::group(['prefix' => 'livescores'], function($route) {
    $route->get('/', [LivescoreController::class, 'index']);
    $route->get('/{fixtureId}/score', [LivescoreController::class, 'getLivescores']);
});

Route::get('/aboutus', [HomeController::class, 'aboutus']);

Route::get('/news', [NewsController::class, 'index']);