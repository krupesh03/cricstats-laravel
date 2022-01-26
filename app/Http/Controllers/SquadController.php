<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Helpers\ApicallHelper;
use App\Helpers\FunctionHelper;
use Config;

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

        $season = $this->apicallHelper->getDataFromAPI( $seasonApiEndpoint );

        return view('seasons/squads', compact('squads', 'season', 'helper'));
    }

    public function getHomeFixtures( $id, $seasonId, $fixtureType ) {

        $apiEndpoint = Config::get('constants.API_ENDPOINTS.FIXTURES');

        $queryStrs = [
            'filter[season_id]' => $seasonId,     
        ];

        if( $fixtureType == 'home' ) {
            $queryStrs['filter[localteam_id]'] = $id;
            $queryStrs['include'] = 'visitorteam,venue,manofmatch';
        } elseif( $fixtureType == 'away' ) {
            $queryStrs['filter[visitorteam_id]'] = $id;
            $queryStrs['include'] = 'localteam,venue,manofmatch';
        }

        $fixtures = $this->apicallHelper->getDataFromAPI( $apiEndpoint, $queryStrs );

        $seasonApiEndpoint = Config::get('constants.API_ENDPOINTS.SEASONS');

        $seasonApiEndpoint = $seasonApiEndpoint . '/' .$seasonId; 

        $season = $this->apicallHelper->getDataFromAPI( $seasonApiEndpoint );

        $teamApiEndpoint = Config::get('constants.API_ENDPOINTS.TEAMS');

        $teamApiEndpoint = $teamApiEndpoint . '/' .$id; 

        $teams = $this->apicallHelper->getDataFromAPI( $teamApiEndpoint );

        $helper = $this->functionHelper;

        return view('seasons/fixtures', compact('fixtures', 'season', 'teams', 'fixtureType', 'helper'));
    }
}
