<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Helpers\ApicallHelper;
use App\Helpers\FunctionHelper;
use Config;

class LeaguesController extends Controller
{
    public function __construct() {

        $this->apicallHelper = new ApicallHelper;
        $this->functionHelper = new FunctionHelper;
    }

    public function index() {

        $apiEndpoint = Config::get('constants.API_ENDPOINTS.LEAGUES');

        $leagues = $this->apicallHelper->getDataFromAPI( $apiEndpoint );

        $helper = $this->functionHelper;
        
        return view('leagues', compact('leagues', 'helper'));
    }

    public function getStandings( $leagueid, $seasonid ) {

        $apiEndpoint = Config::get('constants.API_ENDPOINTS.STANDINGS') . '/' . Config::get('constants.API_ENDPOINTS.SEASON') . '/' . $seasonid;

        $queryStrs = [
            'include'   => 'league,season,team'
        ];

        $standings = $this->apicallHelper->getDataFromAPI( $apiEndpoint, $queryStrs );

        $helper = $this->functionHelper;

        return view('teams/standings', compact('standings', 'helper'));
    }
}
