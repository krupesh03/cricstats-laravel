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
                if( isset($score['status']) && ($score['status'] == '1st Innings' || $score['status'] == 'NS') ) {
                    if( isset($score['tosswon']['name']) && isset($score['elected']) ) { 
                        $matchNote = $score['tosswon']['name'] . ' opt for ' . $score['elected'];
                    } else {
                        $matchNote = $score['status'];
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

            $helper = $this->functionHelper;

            $news = $this->apicallHelper->getNewsDataFromAPI();
            if( $news['success'] )
                $news = collect($news['data'])->take(6);
            else
                $news = collect([]);

            return view('fixtures/livescores', compact('allLiveScores', 'helper', 'news'));
        }
        
        return abort(404);
    }

    public function getLivescores( $fixtureId ) {

        $apiEndpoint = Config::get('constants.API_ENDPOINTS.FIXTURES');

        $apiEndpoint = $apiEndpoint . '/' . $fixtureId;

        $queryStr = [
            'include' => 'localteam,visitorteam,stage,season,venue,balls.score,balls.batsmanone,balls.batsmantwo,runs.team,balls.batsmanout,balls.catchstump,balls.runoutby,batting,bowling,tosswon,manofmatch,lineup,league,manofseries'
        ];

        $livescore = $this->apicallHelper->getDataFromAPI( $apiEndpoint, $queryStr );
          
        /* $json = file_get_contents( public_path( 'dummy.json' ) );
        $livescore = json_decode( $json, true );
        $livescore['success'] = true; */
  
        $liveCommentory = $livedetails = $batsman = $bowler = $keyStats = [];
        if( $livescore['success'] ) {
            $livedetails['localteam'] = $livescore['data']['localteam'];
            $livedetails['visitorteam'] = $livescore['data']['visitorteam'];
            $livedetails['stage'] = $livescore['data']['stage'];
            $livedetails['venue'] = $livescore['data']['venue'];
            $livedetails['season'] = $livescore['data']['season'];
            $livedetails['manofmatch'] = $livescore['data']['manofmatch'];
            $livedetails['lineup'] = $livescore['data']['lineup'];
            $livedetails['league'] = $livescore['data']['league'];
            $livedetails['fixtureId'] = $livescore['data']['id'];
            $livedetails['tosswon'] = $livescore['data']['tosswon'];
            $livedetails['manofseries'] = $livescore['data']['manofseries'];
            $runs = [];
            $k = $current_innings = 0;
            $crr = $rr = $req = $rem_o = $total_1 = $total_2 = $overs_2 = 0;
            foreach( $livescore['data']['runs'] as $run ) {
                if( ($run['inning']%2) != 0 ) {
                    $current_innings++;
                    $total_1 = (int)$run['score'] ;
                } elseif( ($run['inning']%2) == 0 ) {
                    $current_innings++;
                    $total_2 = (int)$run['score'];
                    $overs_2 = $this->functionHelper->calculateBallsFromOvers( $run['overs'] );
                    $total_overs = $this->functionHelper->calculateBallsFromOvers( $livescore['data']['total_overs_played'] );
                    $rem_o = $total_overs - $overs_2;
                    $maxNumBall = $total_overs;
                }
                $c_overs = $this->functionHelper->calculateBallsFromOvers( $run['overs'] );
                if( $c_overs > 0 ) {
                    $crr =  ( (int)$run['score'] / $c_overs ) * 6;
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
            $mainInnings = (int)preg_replace('/[^0-9]/', '', $livescore['data']['status']);
            if( $mainInnings > 0 && $mainInnings%2 == 0 ) {
                $total_1 = (int)preg_replace('/[^0-9]/', '', $livescore['data']['note']);
            }
            $req = $total_1 - $total_2;
            $runs['required_total'] = $req;
            if( $rem_o > 0 ) {
                $rr = ( (int)$req / $rem_o ) * 6;
            }
            if( $rr < 0 ) $rr = 0;
            $runs['rr'] = round($rr, 2);
        
            $livedetails['runs'] = $runs;
            $livedetails['details']['round'] = $livescore['data']['round'];
            $livedetails['details']['starting_at'] = $livescore['data']['starting_at'];
            $livedetails['details']['status'] = $livescore['data']['status'];
            $livedetails['details']['note'] = $livescore['data']['note'];
            $livedetails['details']['elected'] = $livescore['data']['elected'];

            //check if match is live
            $livedetails['details']['liveMatch'] = false;
            if( isset($livescore['data']['status']) && !in_array( $livescore['data']['status'], Config::get('constants.MATCH_NOT_LIVE_STATUSES') ) ) {
                $livedetails['details']['liveMatch'] = true;
            }

            $batsmanData = $bowlerData = [];
            foreach($livescore['data']['batting'] as $batting ) {
                $batsmanData[$batting['player_id']] = $batting;
            }
            foreach($livescore['data']['bowling'] as $bowling ) {
                $bowlerData[$bowling['player_id']] = $bowling;
            }

            $scoreboard = $innings_score = '';
            $lastBall = 0;
            $perOverScore = $perOverBallScore = [];
            foreach( $livescore['data']['balls'] as $ball ) {
                $liveCommentory[$ball['id']] = $ball;
                $inningNumber = (int)preg_replace('/[^0-9]/', '', $ball['scoreboard']);

                $batsman['batsmanone'] = $ball['batsmanone'];
                if( isset($batsman['batsmanone']) && !empty($batsman['batsmanone']) ) {
                    $batsman['batsmanone']['scores'] = isset($batsmanData[$ball['batsmanone']['id']]) ? $batsmanData[$ball['batsmanone']['id']] : [];
                    $batsman['batsmanone']['on_strike'] = $this->functionHelper->batterOnStrike( $ball, $ball['batsmanone']['id'] );
                }

                $batsman['batsmantwo'] = $ball['batsmantwo'];
                if( isset($batsman['batsmantwo']) && !empty($batsman['batsmantwo']) ) {
                    $batsman['batsmantwo']['scores'] = isset($batsmanData[$ball['batsmantwo']['id']]) ? $batsmanData[$ball['batsmantwo']['id']] : [];
                    $batsman['batsmantwo']['on_strike'] = $this->functionHelper->batterOnStrike( $ball, $ball['batsmantwo']['id'] );
                }

                $bowler['bowlerone'] = $ball['bowler'];
                if( isset($bowler['bowlerone']) && !empty($bowler['bowlerone']) ) {
                    $bowler['bowlerone']['figures'] = isset($bowlerData[$ball['bowler']['id']]) ? $bowlerData[$ball['bowler']['id']] : [];
                    $bowler['bowlerone']['on_strike'] = true;
                }

                if( $scoreboard != $ball['scoreboard'] ) { //separate innings
                    $total_score = $total_overs = $fow_score = $fow_overs = $total_wkts = $bats_score = $bats_deli = $fow_balls = 0;
                    $fow_batsman = $fow_bowler = $fow_type = '';
                    $iAlreadyStarted = false;
                }
                foreach( $runs['data'] as $run ) {
                    if( $ball['team']['id'] == $run['team']['id'] && $inningNumber == $run['inning'] ) {
                        $total_score = $run['score'];
                        $total_overs = $this->functionHelper->calculateBallsFromOvers( $run['overs'] );
                        $total_wkts = $run['wickets'];
                        $scoreboard = $ball['scoreboard'];
                        if( ($run['inning']%2) != 0 ) {
                            $innings_score = $run['team']['code'] . ' ' . $total_score . '-' . $total_wkts . ' (' . $run['overs'] . ')  [Target ' . ((int)$total_score + 1) . ' runs]';
                        }
                        if( $ball['score']['is_wicket'] ) {
                            
                            if( isset($ball['batsmanout']['id']) && !empty($ball['batsmanout']['id']) ) {
                                $batsmanoutId = $ball['batsmanout']['id'];
                            } else {
                                $batsmanoutId = $ball['batsman']['id'];
                            }
                            $fow_score = isset($batsmanData[$batsmanoutId]['fow_score']) ? $batsmanData[$batsmanoutId]['fow_score'] : 0; 
                            $fow_balls = isset($batsmanData[$batsmanoutId]['fow_balls']) ? $this->functionHelper->calculateBallsFromOvers( $batsmanData[$batsmanoutId]['fow_balls'] ) : 0;
                            $fow_overs = isset($batsmanData[$batsmanoutId]['fow_balls']) ? $batsmanData[$batsmanoutId]['fow_balls'] : 0;
                            $fow_batsman = isset($ball['batsmanout']['fullname']) ? $ball['batsmanout']['fullname'] : $ball['batsman']['fullname'];
                            $bats_score = isset($batsmanData[$batsmanoutId]['score']) ? $batsmanData[$batsmanoutId]['score'] : 0;
                            $bats_deli = isset($batsmanData[$batsmanoutId]['ball']) ? $batsmanData[$batsmanoutId]['ball'] : 0;

                            if( strpos($ball['score']['name'], 'Catch') !== false && $ball['catchstump'] && $ball['bowler']['id'] == $ball['catchstump']['id'] ) {
                                $fow_type = "c & ";
                            } elseif( strpos($ball['score']['name'], 'Catch') !== false && $ball['catchstump'] ) {
                                $fow_type = "c " . $ball['catchstump']['fullname'];
                            }elseif( strpos($ball['score']['name'], 'Run') !== false ) {
                                if( $ball['runoutby'] ) {
                                    $fow_type = "run out " . "(" . $ball['runoutby']['fullname'] . ")";
                                } elseif( $ball['catchstump'] ) {
                                    $fow_type = "run out " . "(" . $ball['catchstump']['fullname'] . ")";
                                }
                            }elseif( strpos($ball['score']['name'], 'LBW') !== false ) {
                                $fow_type = "lbw";
                            }elseif( strpos($ball['score']['name'], 'Stump') !== false && $ball['catchstump']) {
                                $fow_type = "st " . $ball['catchstump']['fullname'];
                            }elseif( strpos($ball['score']['name'], 'Hit') !== false ) {
                                $fow_type = "Hit Wicket";
                            } else {
                                $fow_type = "";
                            }

                            if( $ball['bowler'] && !$ball['runoutby'] && strpos($ball['score']['name'], 'Run') === false ) {
                                $fow_bowler = "b " . $ball['bowler']['fullname'];
                            } else {
                                $fow_bowler = "";
                            }
                        }
                        break;
                    }
                }
                if( $inningNumber > 1 && $ball['ball'] == '0.1' && !$iAlreadyStarted ) {
                    $iAlreadyStarted = true;
                    $liveCommentory[$ball['id']]['innings_score'] = ($inningNumber%2) == 0 ? $innings_score : '';
                }
                $keyStats['partnership'] = ($total_score - $fow_score) . '(' . ($total_overs - $fow_balls) . ')';
                $keyStats['last_wkt'] = $fow_batsman ? ($fow_batsman . ' '. $fow_type .' '.$fow_bowler. ' '. $bats_score . '(' .$bats_deli. ') - ' . $fow_score . '/' . $total_wkts . ' in '. $fow_overs . ' ov.') : '';
                $keyStats['toss'] = $livescore['data']['tosswon'] ? $livescore['data']['tosswon']['name'] . ' (' .ucfirst($livescore['data']['elected']). ')' : '';
                $keyStats['innings_score'] = ($inningNumber%2) != 0 ? $innings_score : '';

                $perOverScore[$ball['team_id']][$ball['scoreboard']][ceil($ball['ball'])][] = $ball['score']['runs'] + $ball['score']['leg_bye'] + $ball['score']['bye'] + $ball['score']['noball_runs'];

                $ballRun = 0;
                if( $ball['score']['is_wicket'] ) {
                    $ballRun = 'W';
                } else {
                    if( $ball['score']['leg_bye'] ) {
                        $ballRun = $ball['score']['leg_bye'] . 'lb';
                    } elseif( $ball['score']['bye'] ) {
                        $ballRun = $ball['score']['bye'] . 'b'; 
                    } elseif( $ball['score']['noball'] ) {
                        $ballRun = $ball['score']['noball_runs'] . 'nb'; 
                    } elseif( !$ball['score']['ball'] ) {
                        $ballRun = $ball['score']['runs'] . 'wd'; 
                    } else {
                        $ballRun = $ball['score']['runs'];
                    }
                }
                $perOverBallScore[$ball['team_id']][$ball['scoreboard']][ceil($ball['ball'])][] = $ballRun;
                $lastBall = $ball['ball'];
            }
            krsort($liveCommentory);

            $balls_remaining = false;
            if( isset($maxNumBall) && !empty($maxNumBall) ) {
                $balls_remaining = $maxNumBall - $this->functionHelper->calculateBallsFromOvers( $lastBall );
                if( $balls_remaining <= 0 ) {
                    $balls_remaining = false;
                } elseif( $balls_remaining > 99 ) {
                    $balls_remaining = ' in ' . $this->functionHelper->calculateOversFromBalls( $balls_remaining ) . ' overs';
                } else {
                    $balls_remaining = ' in ' . $balls_remaining . ' balls';
                }
            }
            $livedetails['details']['balls_remaining'] = $balls_remaining;
        
            $helper = $this->functionHelper;

            return view('fixtures/livesummary', compact('livedetails', 'batsman', 'bowler', 'liveCommentory', 'keyStats', 'batsmanData', 'perOverScore', 'perOverBallScore', 'helper'));
        }
        
        return abort(404);
    }
}
