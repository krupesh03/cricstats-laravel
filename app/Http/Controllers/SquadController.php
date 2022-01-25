<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Helpers\ApicallHelper;
use Config;

class SquadController extends Controller
{
    public function __construct() {

        $this->apicallHelper = new ApicallHelper;
    }

    public function index( $id ) {

        $apiEndpoint = Config::get('constants.API_ENDPOINTS.SEASONS');

        $queryStrs = [
            'sort'   => 'name'
        ];

        $apiData = $this->apicallHelper->getDataFromAPI( $apiEndpoint, $queryStrs );

        return view('seasons/seasons', compact('apiData', 'id'));
    }

    public function getSquads( $id, $seasonId ) {

        $apiEndpointOne = Config::get('constants.API_ENDPOINTS.TEAMS');

        $apiEndpointTwo = Config::get('constants.API_ENDPOINTS.SQUAD');

        $apiEndpoint = $apiEndpointOne . '/' . $id . '/' . $apiEndpointTwo . '/' .$seasonId; 

        $squads = $this->apicallHelper->getDataFromAPI( $apiEndpoint );

        return view('seasons/squads', compact('squads'));
    }
}
