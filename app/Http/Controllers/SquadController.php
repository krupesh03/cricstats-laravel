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
}
