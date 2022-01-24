<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Helpers\ApicallHelper;
use Config;

class IccrankingsController extends Controller
{
    public function __construct() {

        $this->apicallHelper = new ApicallHelper;
    }

    public function getRankings( $slug, $type ) {

        if( $type == 'teams' ) {
            $apiEndpoint = Config::get('constants.API_ENDPOINTS.TEAM_RANKINGS');

            $queryStrs = [
                'filter[gender]'   => $slug
            ];
        }

        $apiData = $this->apicallHelper->getDataFromAPI( $apiEndpoint, $queryStrs );
        
        if( is_array($apiData['data']) ) {
            array_splice($apiData['data'], 3);
        }
        
        return view('iccrankings', compact('apiData'));
    }
}
