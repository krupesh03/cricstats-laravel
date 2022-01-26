<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Helpers\ApicallHelper;
use App\Helpers\FunctionHelper;
use Config;

class SeasonsController extends Controller
{
    public function __construct() {

        $this->apicallHelper = new ApicallHelper;
        $this->functionHelper = new FunctionHelper;
    }

    public function index( $leagueid ) {

        $apiEndpoint = Config::get('constants.API_ENDPOINTS.SEASONS');

        $queryStrs = [
            'filter[league_id]'   => $leagueid,
            'sort'                => 'name'
        ];

        $seasons = $this->apicallHelper->getDataFromAPI( $apiEndpoint, $queryStrs );
        
        return view('seasons/seasons', compact('seasons'));
    }

    public function getTeams( $leagueid, $seasonid ) {

        $apiEndpoint = Config::get('constants.API_ENDPOINTS.SEASONS');
        $apiEndpoint = $apiEndpoint . '/' . $seasonid;

        $queryStrs = [
            'include'   => 'league,teams'
        ];

        $seasonTeams = $this->apicallHelper->getDataFromAPI( $apiEndpoint, $queryStrs );

        $helper = $this->functionHelper;

        return view('seasons/teams', compact('seasonTeams', 'helper'));
    }
}
