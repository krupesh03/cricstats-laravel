<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Helpers\ApicallHelper;
use App\Helpers\FunctionHelper;
use Config;

class HomeController extends Controller
{
    public function __construct() {

        $this->apicallHelper = new ApicallHelper;
        $this->functionHelper = new FunctionHelper;
    }

    public function index( Request $request ) {

        $fixturesApiEndpoint = Config::get('constants.API_ENDPOINTS.FIXTURES');

        $dayBeforeYest = date('Y-m-d 00:00:00', strtotime('-1 days'));
        $tomorrow = date('Y-m-d 23:59:59', strtotime('+1 days'));

        $fixturesQueryStr = [
            'include'                   => 'localteam,visitorteam,league,tosswon,runs',
            'filter[starts_between]'    => (string)$dayBeforeYest.','.$tomorrow,
            'sort'                      => 'starting_at'
        ];

        if( $request->query('page') ) {
            $fixturesQueryStr['page'] = $request->query('page');
        }

        $fixtures = $this->apicallHelper->getDataFromAPI( $fixturesApiEndpoint, $fixturesQueryStr );

        $pagination = $featuredFixtures = [];
        if( $fixtures['success'] ) { 

            $pagination = $fixtures['meta'];
            $i=0;
            foreach( $fixtures['data'] as $fixture ) {

                $featuredFixtures[$i] = $fixture;
                $matchRuns = [];
                foreach( $fixture['runs'] as $run ) {
                    $matchRuns[$run['team_id']] = $run['score'] . '-' . $run['wickets'] . ' (' . $run['overs'] . ' Ov)';
                }
                $featuredFixtures[$i]['matchRuns'] = $matchRuns;
                $matchNote = '';
                if( isset($fixture['status']) && ($fixture['status'] == '1st Innings' || $fixture['status'] == 'NS') ) {
                    if( isset($fixture['tosswon']['name']) && isset($fixture['elected']) ) { 
                        $matchNote = $fixture['tosswon']['name'] . ' opt for ' . $fixture['elected'];
                    } else {
                        $matchNote = $fixture['status'];
                    }
                } elseif( isset($fixture['status']) && $fixture['status'] == 'Innings Break' ) {
                    $matchNote = $fixture['status'];
                } else {
                    if( isset($fixture['note']) && !empty($fixture['note']) ) { 
                        $matchNote = $fixture['note'];
                    } elseif ( isset($fixture['status']) && !empty($fixture['status']) ) {
                        $matchNote = $fixture['status'];
                    }
                }
                $featuredFixtures[$i]['matchNote'] = $matchNote;
                $featuredFixtures[$i]['liveMatch'] = false;
                if( isset($fixture['status']) && !in_array( $fixture['status'], Config::get('constants.MATCH_NOT_LIVE_STATUSES') ) ) {
                    $featuredFixtures[$i]['liveMatch'] = true;
                }
                $i++;
            }

            usort( $featuredFixtures, function( $arr1, $arr2 ) {
                return strtotime($arr1['starting_at']) >= strtotime($arr2['starting_at']);
            });

        }

        $helper = $this->functionHelper;

        return view('home', compact('featuredFixtures', 'pagination', 'helper'));
    }

    public function aboutus() {

        return view('aboutus/aboutus');
    }
}
