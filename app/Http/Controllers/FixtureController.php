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
            'include' => 'localteam,visitorteam,batting.result,venue.country,manofmatch,batting.batsman,batting.bowler,tosswon,runs.team,batting.catchstump,batting.runoutby,bowling.bowler,manofseries,lineup,firstumpire,secondumpire,tvumpire,referee,stage,scoreboards,season,league'
        ];

        $fixture = $this->apicallHelper->getDataFromAPI( $fixturesApiEndpoint, $fixturesQueryStr );

        $lineupArray = $fowArray = $scorecardArray = $localTeamSquad = $visitorTeamSquad = [];
        if( $fixture['success'] ) {
            foreach( $fixture['data']['lineup'] as $lineup ) {
                $lineupArray[$lineup['id']]['captain'] = $lineup['lineup']['captain']; 
                $lineupArray[$lineup['id']]['wicketkeeper'] = $lineup['lineup']['wicketkeeper']; 
            }

            foreach( $fixture['data']['batting'] as $batsman ) {
                if( $batsman['catch_stump_player_id'] || $batsman['runout_by_id'] || $batsman['bowling_player_id'] ) {
                    $fowArray[$batsman['team_id']][(string)$batsman['fow_balls']]['fow_score'] = $batsman['fow_score'];
                    $fowArray[$batsman['team_id']][(string)$batsman['fow_balls']]['player'] = $batsman['batsman']['fullname'];
                }
            }

            if( isset($fixture['data']['runs']) && !empty($fixture['data']['runs']) ) {
                foreach( $fixture['data']['runs'] as $run ) {
                    $scorecardArray[$run['inning']]['runs'] = $run;

                    $scorecardArray[$run['inning']]['batting'] = [];
                    $scorecardArray[$run['inning']]['bowling'] = [];
                    $scorecardArray[$run['inning']]['scoreboards'] = [];
                    $scorecardArray[$run['inning']]['fallOfWkts'] = [];
                    $scorecardArray[$run['inning']]['dnb'] = [];
                    $fowktArray = $playedId = [];

                    foreach( $fixture['data']['batting'] as $score ) {
                        $inningNumber = (int)preg_replace('/[^0-9]/', '', $score['scoreboard']);
                        if( $inningNumber == $run['inning'] ) {
                            $scorecardArray[$run['inning']]['batting'][] = $score; 

                            if( $score['catch_stump_player_id'] || $score['runout_by_id'] || $score['bowling_player_id'] ) {
                                $fowktArray[(string)$score['fow_balls']]['fow_score'] = $score['fow_score'];
                                $fowktArray[(string)$score['fow_balls']]['player'] = $score['batsman']['fullname'];
                                $fowktArray[(string)$score['fow_balls']]['playerId'] = $score['batsman']['id'];
                            }

                            $playedId[] = $score['batsman']['id'];
                        }
                    }

                    foreach( $fixture['data']['bowling'] as $score ) {
                        $inningNumber = (int)preg_replace('/[^0-9]/', '', $score['scoreboard']);
                        if( $inningNumber == $run['inning'] ) {
                            $scorecardArray[$run['inning']]['bowling'][] = $score;
                        }
                    }

                    foreach( $fixture['data']['scoreboards'] as $scoreTotal ) {
                        $inningNumber = (int)preg_replace('/[^0-9]/', '', $scoreTotal['scoreboard']);
                        if( $inningNumber == $run['inning'] ) {
                            $scorecardArray[$run['inning']]['scoreboards'][$scoreTotal['type']] = $scoreTotal;
                        }
                    }

                    ksort($fowktArray);
                    $wkt = 1;
                    foreach( $fowktArray as $over => $score ) {
                        $scorecardArray[$run['inning']]['fallOfWkts'][] = $score['fow_score'] . '-' . $wkt . ' (<a href="/players/'.$score['playerId'].'">' . $score['player'] . '</a>, ' . $over .')';
                        $wkt++;
                    }

                    $playerName = '';
                    foreach( $fixture['data']['lineup'] as $lineup ) {
                        if( $lineup['lineup']['team_id'] == $run['team_id'] && !in_array( $lineup['id'], $playedId ) ) {
                            $playerName = '<a href="/players/'.$lineup['id'].'">' . $lineup['fullname'];
                            if( $lineup['lineup']['captain'] ) {
                                $playerName .= ' (c)';
                            }
                            if( $lineup['lineup']['wicketkeeper'] ) {
                                $playerName .= ' (wk)';
                            }
                            $playerName .= '</a>';
                            $scorecardArray[$run['inning']]['dnb'][] = $playerName;
                        }  
                    }
                }
            }

            foreach( $fixture['data']['lineup'] as $lineup ) {
                $lineupName = $lineup['fullname'];
                if( $lineup['lineup']['captain'] ) {
                    $lineupName .= ' (c)';
                }
                if( $lineup['lineup']['wicketkeeper'] ) {
                    $lineupName .= ' (wk)';
                }
                if( $lineup['lineup']['team_id'] == $fixture['data']['localteam_id'] ) {
                    $localTeamSquad[] = $lineupName;
                } elseif( $lineup['lineup']['team_id'] == $fixture['data']['visitorteam_id'] ) {
                    $visitorTeamSquad[] = $lineupName;
                }
            }
            
        }

        $helper = $this->functionHelper;

        return view('fixtures/scorecard', compact('fixture', 'lineupArray', 'scorecardArray', 'localTeamSquad', 'visitorTeamSquad', 'helper'));
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
                        'status'        => $fixture['status'],
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

    public function getStageData( $stageId, $leagueId, $seasonId ) {

        $stageFixtures = [];
        if( $stageId && $leagueId && $seasonId ) {

            $seasonApiEndpoint = Config::get('constants.API_ENDPOINTS.SEASONS');

            $seasonApiEndpoint = $seasonApiEndpoint . '/' . $seasonId;

            $season = $this->apicallHelper->getDataFromAPI( $seasonApiEndpoint );

            $seasonName = '';
            if( $season['success'] ) {
                $seasonName = $season['data']['name'];
            }

            $stagesApiEndpoint = Config::get('constants.API_ENDPOINTS.STAGES');

            $stagesApiEndpoint = $stagesApiEndpoint . '/' . $stageId;

            $stagesQueryStr = [
                'filter[season_id]' => $seasonId,
                'include'           => 'league,fixtures.localteam,fixtures.visitorteam'
            ];

            $fixtures = $this->apicallHelper->getDataFromAPI( $stagesApiEndpoint, $stagesQueryStr );

            if( $fixtures['success'] ) {
                $stageName = $fixtures['data']['name'] . ', ' . $fixtures['data']['league']['name'];
                $stageName .= (!empty($stageName) && !empty($seasonName)) ? ', '.$seasonName : ''; 
                $stageFixtures['stageName'] = $stageName;
                $stageFixtures['standings'] = $fixtures['data']['standings'];
                $stageFixtures['league_id'] = $fixtures['data']['league_id'];
                $stageFixtures['season_id'] = $fixtures['data']['season_id'];
                $stageFixtures['fixtures'] = [];
                $stageDates = [];
                foreach( $fixtures['data']['fixtures'] as $fixture ) {
                    $stageFixtures['fixtures'][] = $fixture;
                    $stageDates[] = !empty($fixture['starting_at']) ? strtotime($fixture['starting_at']) : '';
                }
                sort($stageDates);
                $startDate = reset($stageDates);
                $endDate = end($stageDates);
                $timeSpan = !empty($startDate) ? date('M d', $startDate) : '';
                $timeSpan .= (!empty($endDate) && !empty($timeSpan)) ? ' - ' . date('M d', $endDate) : '';
                $stageFixtures['stageDates'] = $timeSpan;
            }

        }

        $helper = $this->functionHelper;

        return view('stages/fixtures', compact('stageFixtures', 'helper'));
    }
}
