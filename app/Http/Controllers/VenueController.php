<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Helpers\ApicallHelper;
use App\Helpers\FunctionHelper;
use Config;

class VenueController extends Controller
{
    public function __construct() {

        $this->apicallHelper = new ApicallHelper;
        $this->functionHelper = new FunctionHelper;
    }

    public function index() {

        $apiEndpoint = Config::get('constants.API_ENDPOINTS.VENUES');

        $queryStrs = [
            'include'   => 'country'
        ];

        $venues = $this->apicallHelper->getDataFromAPI( $apiEndpoint, $queryStrs );

        $applicableVenues = [];
        if( $venues['success'] ) {
            foreach( $venues['data'] as $venue ) {
                if( in_array( $venue['country']['name'], Config::get('constants.APPLICABLE_COUNTRIES') ) ) {
                    $applicableVenues[] = $venue;
                }
            }
        }

        $helper = $this->functionHelper;

        return view('venues/listing', compact('applicableVenues', 'helper'));
    }

    public function details( $id ) {

        $apiEndpoint = Config::get('constants.API_ENDPOINTS.VENUES');

        $apiEndpoint = $apiEndpoint . '/' . $id;

        $queryStrs = [
            'include'   => 'country,fixtures.localteam,fixtures.visitorteam'
        ];

        $venue = $this->apicallHelper->getDataFromAPI( $apiEndpoint, $queryStrs );

        $helper = $this->functionHelper;

        return view('venues/details', compact('venue', 'helper'));
    }
}
