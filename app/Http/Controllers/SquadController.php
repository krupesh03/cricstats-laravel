<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Helpers\ApicallHelper;
use App\Helpers\FunctionHelper;
use Config;
use Session;

class SquadController extends Controller
{
    public function __construct() {

        $this->apicallHelper = new ApicallHelper;
        $this->functionHelper = new FunctionHelper;
    }

    public function index( $id, $seasonId ) {

        $teamApiEndpoint = Config::get('constants.API_ENDPOINTS.TEAMS');

        $squadApiEndpoint = Config::get('constants.API_ENDPOINTS.SQUAD');

        $seasonApiEndpoint = Config::get('constants.API_ENDPOINTS.SEASONS');

        $apiEndpoint = $teamApiEndpoint . '/' . $id . '/' . $squadApiEndpoint . '/' .$seasonId; 

        $seasonApiEndpoint = $seasonApiEndpoint . '/' .$seasonId; 

        $squads = $this->apicallHelper->getDataFromAPI( $apiEndpoint );

        $helper = $this->functionHelper;

        $seasonsData = Session::get('seasons');
        
        $seasons = $season = [];
        if( isset($seasonsData['data']) && is_array($seasonsData['data']) ) {
            foreach( $seasonsData['data'] as $s ) {
                $seasons[$s['id']] = $s['name'];
            }
        }
        
        $season['success'] = false;
        if( count($seasons) && isset($seasons[$seasonId]) ) {
            $season['success'] = true;
            $season['data']['id'] = $seasonId;
            $season['data']['name'] = $seasons[$seasonId];
        } else {
            $season = $this->apicallHelper->getDataFromAPI( $seasonApiEndpoint );
        }

        return view('seasons/squads', compact('squads', 'season', 'helper'));
    }

    public function getHomeFixtures( $id, $seasonId, $fixtureType, Request $request ) {

        $apiEndpoint = Config::get('constants.API_ENDPOINTS.FIXTURES');

        $queryStrs = [
            'filter[season_id]' => $seasonId,     
        ];

        if( $request->query('page') ) {
            $queryStrs['page'] = $request->query('page');
        }

        if( $fixtureType == 'home' ) {
            $queryStrs['filter[localteam_id]'] = $id;
            $queryStrs['include'] = 'visitorteam,venue,manofmatch,season,localteam';
        } elseif( $fixtureType == 'away' ) {
            $queryStrs['filter[visitorteam_id]'] = $id;
            $queryStrs['include'] = 'localteam,venue,manofmatch,season,visitorteam';
        }

        $fixtures = $this->apicallHelper->getDataFromAPI( $apiEndpoint, $queryStrs );

        $pagination = [];
        if( $fixtures['success'] ) {
            $pagination = $fixtures['meta'];
        }

        $helper = $this->functionHelper;

        return view('seasons/fixtures', compact('fixtures', 'pagination', 'fixtureType', 'helper'));
    }
}
