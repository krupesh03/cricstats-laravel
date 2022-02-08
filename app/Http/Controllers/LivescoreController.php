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

        $apiEndpoint = Config::get('constants.API_ENDPOINTS.FIXTURES');

        $apiEndpoint = $apiEndpoint . '/' . $fixtureId;

        $queryStr = [
            'include' => 'localteam,visitorteam,stage,season,venue,balls.score,balls.batsmanone,balls.batsmantwo,runs.team,balls.batsmanout,balls.catchstump,balls.runoutby,batting,bowling'
        ];

        $livescore = $this->apicallHelper->getDataFromAPI( $apiEndpoint, $queryStr );
          
        /* $json = file_get_contents(public_path('dummy.json'));
        $livescore = json_decode($json,true);
        $livescore['success'] = true; */
  
        $liveCommentory = $livedetails = $batsman = $bowler = [];
        if( $livescore['success'] ) {
            $livedetails['localteam'] = $livescore['data']['localteam'];
            $livedetails['visitorteam'] = $livescore['data']['visitorteam'];
            $livedetails['stage'] = $livescore['data']['stage'];
            $livedetails['venue'] = $livescore['data']['venue'];
            $livedetails['season'] = $livescore['data']['season'];
            $runs = [];
            $k = $current_innings = 0;
            $crr = $rr = $req = $rem_o = $total_1 = $total_2 = $overs_2 = 0;
            foreach( $livescore['data']['runs'] as $run ) {
                if( $run['inning'] == 1 ) {
                    $current_innings++;
                    $total_1 = (int)$run['score'] ;
                } elseif( $run['inning'] == 2 ) {
                    $current_innings++;
                    $total_2 = (int)$run['score'];
                    $overs_2 = $this->functionHelper->calculateBallsFromOvers( $run['overs'] );
                    $total_overs = $this->functionHelper->calculateBallsFromOvers( $livescore['data']['total_overs_played'] );
                    $rem_o = $total_overs - $overs_2;
                }
                if( (float)$run['overs'] > 0 ) {
                    $crr =  (int)$run['score'] / (float)$run['overs'];
                }
                $runs['data'][$k]['inning'] = $run['inning'];
                $runs['data'][$k]['team'] = $run['team'];
                $runs['data'][$k]['score'] = $run['score'];
                $runs['data'][$k]['wickets'] = $run['wickets'];
                $runs['data'][$k]['overs'] = $run['overs'];
                $runs['data'][$k]['crr'] = round($crr, 2);
                $k++;
            }
            $runs['current_innings'] = $current_innings;
            $req = $total_1 - $total_2;
            $runs['required_total'] = $req;
            if( $rem_o > 0 ) {
                $rr = ( (int)$req / $rem_o ) * 6;
            }
            $runs['rr'] = round($rr, 2);
        
            $livedetails['runs'] = $runs;
            $livedetails['details']['round'] = $livescore['data']['round'];
            $livedetails['details']['starting_at'] = $livescore['data']['starting_at'];
            $livedetails['details']['status'] = $livescore['data']['status'];
            $livedetails['details']['note'] = $livescore['data']['note'];

            $batsmanData = $bowlerData = [];
            foreach($livescore['data']['batting'] as $batting ) {
                $batsmanData[$batting['player_id']] = $batting;
            }
            foreach($livescore['data']['bowling'] as $bowling ) {
                $bowlerData[$bowling['player_id']] = $bowling;
            }

            foreach( $livescore['data']['balls'] as $ball ) {
                $liveCommentory[$ball['id']] = $ball;

                $batsman['batsmanone'] = $ball['batsmanone'];
                $batsman['batsmanone']['scores'] = $batsmanData[$ball['batsmanone']['id']];
                $batsman['batsmanone']['on_strike'] = $ball['batsman']['id'] == $ball['batsmanone']['id'];

                $batsman['batsmantwo'] = $ball['batsmantwo'];
                $batsman['batsmantwo']['scores'] = $batsmanData[$ball['batsmantwo']['id']];
                $batsman['batsmantwo']['on_strike'] = $ball['batsman']['id'] == $ball['batsmantwo']['id'];

                $bowler['bowlerone'] = $ball['bowler'];
                $bowler['bowlerone']['figures'] = $bowlerData[$ball['bowler']['id']];
                $bowler['bowlerone']['on_strike'] = 1;
            }
        }
        krsort($liveCommentory);
        
        $helper = $this->functionHelper;

        return view('fixtures/livesummary', compact('livedetails', 'batsman', 'bowler', 'liveCommentory', 'helper'));
    }
}
