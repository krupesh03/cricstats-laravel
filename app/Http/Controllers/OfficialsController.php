<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Helpers\ApicallHelper;
use App\Helpers\FunctionHelper;
use Config;

class OfficialsController extends Controller
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

        $dropdowns = $countryIds = [];
        if( $countries['success'] ) {
            foreach( $countries['data'] as $country ) {
                if( in_array( $country['name'], Config::get('constants.APPLICABLE_COUNTRIES') ) ) {
                    $countryIds[$country['id']] = $country['name'];
                }
            }
        }

        $dropdowns['countries'] = $countryIds;

        $apiEndpoint = Config::get('constants.API_ENDPOINTS.OFFICIALS');

        $queryStrs = [
            'include'   => 'country',
            'sort'      => 'fullname'
        ];

        if( $request->query('country') ) {
            $queryStrs['filter[country_id]'] = $request->query('country');
        }
        if( $request->query('lastname') ) {
            $queryStrs['filter[lastname]'] = $request->query('lastname');
        }

        $officials = $this->apicallHelper->getDataFromAPI( $apiEndpoint, $queryStrs );

        $applicableOfficials = [];
        if( $officials['success'] ) {
            foreach( $officials['data'] as $official ) {
                if( in_array( $official['country']['name'], Config::get('constants.APPLICABLE_COUNTRIES') ) ) {
                    $applicableOfficials[] = $official;
                }
            }
        }

        $helper = $this->functionHelper;

        return view('officials/listing', compact('applicableOfficials', 'dropdowns', 'helper'));
    }
}
