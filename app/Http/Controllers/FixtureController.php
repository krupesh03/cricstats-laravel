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

        $helper = $this->functionHelper;

        return view('fixtures/scorecard', compact('fixture', 'lineupArray', 'fowArray', 'helper'));
    }

    public function listing( Request $request ) {

        $leaguesApiEndpoint = Config::get('constants.API_ENDPOINTS.LEAGUES');

        $leaguesQueryStr = [
            'include'           => 'season'
        ];

        $leagues = $this->apicallHelper->getDataFromAPI( $leaguesApiEndpoint, $leaguesQueryStr );

        $seasonsArray = [];
        if( $leagues['success'] ) {
            foreach( $leagues['data'] as $league ) {
                $seasonsArray[$league['id']] = $league['season']['id'];
            }
        }

        if( count($seasonsArray) ) {
            $fixturesApiEndpoint = Config::get('constants.API_ENDPOINTS.FIXTURES');

            $fixturesQueryStr = [
                'include'           => 'localteam,visitorteam,venue,season,stage,league,manofmatch',
                'filter[season_id]' => (string)implode(',', $seasonsArray),
                'sort'              => 'season_id'
            ];

            if( $request->query('page') ) {
                $fixturesQueryStr['page'] = $request->query('page');
            }

            $fixtures = $this->apicallHelper->getDataFromAPI( $fixturesApiEndpoint, $fixturesQueryStr );

            $pagination = $allFixtures = [];
            if( $fixtures['success'] ) {
                $pagination = $fixtures['meta'];
                $i=0;
                foreach( $fixtures['data'] as $fixture ) {
                    $allFixtures[$fixture['stage']['id']]['stage_name'] = $fixture['stage']['name'];
                    $allFixtures[$fixture['stage']['id']]['league_name'] = $fixture['league']['name'];
                    $allFixtures[$fixture['stage']['id']]['season_name'] = $fixture['season']['name'];
                    $allFixtures[$fixture['stage']['id']]['fixtures'][$i]['localteam'] = $fixture['localteam'];
                    $allFixtures[$fixture['stage']['id']]['fixtures'][$i]['visitorteam'] = $fixture['visitorteam'];
                    $allFixtures[$fixture['stage']['id']]['fixtures'][$i]['venue'] = $fixture['venue'];
                    $allFixtures[$fixture['stage']['id']]['fixtures'][$i]['manofmatch'] = $fixture['manofmatch'];
                    $matchFacts = [
                        'id'            => $fixture['id'],
                        'round'         => $fixture['round'],
                        'starting_at'   => $fixture['starting_at'],
                        'note'          => $fixture['note'],
                        'draw_noresult' => $fixture['draw_noresult']
                    ];
                    $allFixtures[$fixture['stage']['id']]['fixtures'][$i]['facts'] = $matchFacts;
                    $i++;
                }
            }

            $helper = $this->functionHelper;

            return view('fixtures/listing', compact('allFixtures', 'pagination', 'helper'));
        }
        return abort(404);
    }

    public function seasonLeagueFixtures( $seasonId, $leagueId ) {

        $fixturesApiEndpoint = Config::get('constants.API_ENDPOINTS.SEASONS');

        $fixturesApiEndpoint = $fixturesApiEndpoint . '/' . $seasonId;

        $fixturesQueryStr = [
            'filter[league_id]' => $leagueId,
            'include'           => 'league,stages,fixtures.visitorteam,fixtures.localteam'
        ];

        $fixture = $this->apicallHelper->getDataFromAPI( $fixturesApiEndpoint, $fixturesQueryStr );

        $stageArray = [];
        if( $fixture['success'] ) {
            foreach( $fixture['data']['stages'] as $stage ) {
                $stageArray[$stage['id']] = $stage['name'];
            }
        }

        $helper = $this->functionHelper;

        return view('fixtures/seasonleaguefixtures', compact('fixture', 'stageArray', 'helper'));
    }
}
