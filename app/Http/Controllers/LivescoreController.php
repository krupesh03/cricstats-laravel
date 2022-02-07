<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Helpers\ApicallHelper;
use App\Helpers\FunctionHelper;
use Config;

class LivescoreController extends Controller
{
    public function __construct() {

        $this->apicallHelper = new ApicallHelper;
        $this->functionHelper = new FunctionHelper;
    }

    public function index() {

        $apiEndpoint = Config::get('constants.API_ENDPOINTS.LIVESCORES');

        $queryStr = [
            'include' => 'localteam,visitorteam,venue,season,stage,league,tosswon,runs',
            'sort'    => 'league_id'
        ];

        $livescore = $this->apicallHelper->getDataFromAPI( $apiEndpoint, $queryStr );

        $allLiveScores = [];
        if( $livescore['success'] ) {
            $i=0;
            foreach( $livescore['data'] as $score ) {
                $allLiveScores[$score['stage']['id']]['stage_name'] = $score['stage']['name'];
                $allLiveScores[$score['stage']['id']]['league_name'] = $score['league']['name'];
                $allLiveScores[$score['stage']['id']]['season_name'] = $score['season']['name'];
                $allLiveScores[$score['stage']['id']]['fixtures'][$i]['localteam'] = $score['localteam'];
                $allLiveScores[$score['stage']['id']]['fixtures'][$i]['visitorteam'] = $score['visitorteam'];
                $allLiveScores[$score['stage']['id']]['fixtures'][$i]['venue'] = $score['venue'];
                $matchRuns = [];
                foreach( $score['runs'] as $run ) {
                    $matchRuns[$run['team_id']] = $run['score'] . '-' . $run['wickets'] . ' (' . $run['overs'] . ' Ov)';
                }
                $allLiveScores[$score['stage']['id']]['fixtures'][$i]['matchRuns'] = $matchRuns;
                $matchNote = '';
                if( isset($score['status']) && $score['status'] == '1st Innings' ) {
                    if( isset($score['tosswon']['name']) && isset($score['elected']) ) { 
                        $matchNote = $score['tosswon']['name'] . ' opt for ' . $score['elected'];
                    }
                } elseif ( isset($score['status']) && $score['status'] == '2nd Innings' ) {
                    if( isset($score['note']) && !empty($score['note']) ) { 
                        $matchNote = $score['note'];
                    }
                } elseif ( isset($score['status']) && !empty($score['status']) ) {
                    $matchNote = $score['status'];
                }
                $matchFacts = [
                    'id'            => $score['id'],
                    'round'         => $score['round'],
                    'starting_at'   => $score['starting_at'],
                    'note'          => $matchNote
                ];
                $allLiveScores[$score['stage']['id']]['fixtures'][$i]['facts'] = $matchFacts;
                $i++;
            }
        }

        $helper = $this->functionHelper;

        return view('fixtures/livescores', compact('allLiveScores', 'helper'));
    }

    public function getLivescores( $fixtureId ) {

        return view('fixtures/livesummary');
    }
}
