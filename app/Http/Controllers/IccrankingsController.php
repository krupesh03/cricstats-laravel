<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Helpers\ApicallHelper;
use App\Helpers\FunctionHelper;
use Config;

class IccrankingsController extends Controller
{
    public function __construct() {

        $this->apicallHelper = new ApicallHelper;
        $this->functionHelper = new FunctionHelper;
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

        $formats = $rankingData = [];
        $i = 0;
        if( $apiData['success'] ) {
            foreach( $apiData['data'] as $format ) {
                if ( count($format['team']) > 0 ) {
                    $formats[$format['gender']][$i] = $format['type'];
                    $rankingData[$i] = $format;
                    $i++;
                }
            }
        }

        $helper = $this->functionHelper;

        return view('rankings/iccrankings', compact('rankingData', 'formats', 'slug', 'helper'));
    }
}
