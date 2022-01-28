<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Helpers\ApicallHelper;
use App\Helpers\FunctionHelper;
use Config;

class FixtureController extends Controller
{
    public function __construct() {

        $this->apicallHelper = new ApicallHelper;
        $this->functionHelper = new FunctionHelper;
    }

    public function index( $id ) {

        $fixturesApiEndpoint = Config::get('constants.API_ENDPOINTS.FIXTURES');

        $fixturesApiEndpoint = $fixturesApiEndpoint . '/' . $id;

        $fixturesQueryStr = [
            'include' => 'localteam,visitorteam,batting.result,venue.country,manofmatch,batting.batsman,batting.bowler,tosswon,runs.team,batting.catchstump,batting.runoutby,bowling.bowler,manofseries,lineup,firstumpire,secondumpire,tvumpire,referee,stage,scoreboards'
        ];

        $fixture = $this->apicallHelper->getDataFromAPI( $fixturesApiEndpoint, $fixturesQueryStr );

        $lineupArray = [];
        if( $fixture['success'] ) {
            foreach( $fixture['data']['lineup'] as $lineup ) {
                $lineupArray[$lineup['id']]['captain'] = $lineup['lineup']['captain']; 
                $lineupArray[$lineup['id']]['wicketkeeper'] = $lineup['lineup']['wicketkeeper']; 
            }
        }

        $fowArray = [];
        if( $fixture['success'] ) {
            foreach( $fixture['data']['batting'] as $batsman ) {
                if( $batsman['catch_stump_player_id'] || $batsman['runout_by_id'] || $batsman['bowling_player_id'] ) {
                    $fowArray[$batsman['team_id']][(string)$batsman['fow_balls']]['fow_score'] = $batsman['fow_score'];
                    $fowArray[$batsman['team_id']][(string)$batsman['fow_balls']]['player'] = $batsman['batsman']['fullname'];
                }

            }
        }
        ksort($fowArray[43]);
        echo "<pre>";
        print_r($fixture);
        echo "</pre>";

        $helper = $this->functionHelper;

        return view('fixtures/fixture', compact('fixture', 'lineupArray', 'fowArray', 'helper'));
    }
}
