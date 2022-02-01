<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Helpers\ApicallHelper;
use App\Helpers\FunctionHelper;
use Config;

class PlayerController extends Controller
{
    public function __construct() {

        $this->apicallHelper = new ApicallHelper;
        $this->functionHelper = new FunctionHelper;
    }

    public function index( Request $request ) {
        
        $countryApiEndpoint = Config::get('constants.API_ENDPOINTS.COUNTRIES');

        $countryQueryStr = [
            'sort'      => 'name'
        ];

        $countries = $this->apicallHelper->getDataFromAPI( $countryApiEndpoint, $countryQueryStr );

        $dropdowns = $countryIds = $positionIds = [];
        if( $countries['success'] ) {
            foreach( $countries['data'] as $country ) {
                if( in_array( $country['name'], Config::get('constants.APPLICABLE_COUNTRIES') ) ) {
                    $countryIds[$country['id']] = $country['name'];
                }
            }
        }

        $positionApiEndpoint = Config::get('constants.API_ENDPOINTS.POSITIONS');

        $positions = $this->apicallHelper->getDataFromAPI( $positionApiEndpoint );

        if( $positions['success'] ) {
            foreach( $positions['data'] as $position ) {
                $positionIds[$position['id']] = $position['name'];
            }
        }

        $dropdowns['countries'] = $countryIds;
        $dropdowns['positions'] = $positionIds;

        $helper = $this->functionHelper;

        $playerList = [];
        if( ($request->query('find_players') == 'search' && ($request->query('team') || $request->query('position') ) ) || ($request->query('search_key')) ) {

            $apiEndpoint = Config::get('constants.API_ENDPOINTS.PLAYERS');

            $queryStr = [
                'include'               => 'teams',
                'sort'                  => 'lastname'
            ];

            if( $request->query('team') ) {
                $queryStr['filter[country_id]'] = $request->query('team');
            }
            if( $request->query('position') ) {
                $queryStr['filter[position_id]'] = $request->query('position');
            }
            if( $request->query('search_key') ) {
                $queryStr['filter[lastname]'] = $request->query('search_key');
            }

            $players = $this->apicallHelper->getDataFromAPI( $apiEndpoint, $queryStr );
            if( $players['success'] ) {
                $i=0;
                foreach( $players['data'] as $player ) {
                    if( count($player['teams']) ) {
                        $playerList[$i] = $player;
                        $pTeams = [];
                        foreach( $player['teams'] as $team ) {
                            unset($team['in_squad']);
                            $pTeams[$team['id']] = $team['name'];
                        }
                        $playerList[$i]['teams'] = $pTeams;
                        $i++;
                    }
                }
            }
        }

        return view('players/players', compact('dropdowns', 'playerList', 'helper'));

    }

    public function getPlayer( $id ) {

        $apiEndpoint = Config::get('constants.API_ENDPOINTS.PLAYERS');
        
        $apiEndpoint = $apiEndpoint . '/' . $id;

        $queryStr = [
            'include'   => 'country,career.season,teams'
        ];

        $player = $this->apicallHelper->getDataFromAPI( $apiEndpoint, $queryStr );

        $teams = [];
        if( $player['success'] ) {
            foreach( $player['data']['teams'] as $team ) {
                $teams[$team['id']] = $team['name'];
            }
        }

        $helper = $this->functionHelper;

        return view('players/detail', compact('player', 'teams', 'helper'));
    }
}
