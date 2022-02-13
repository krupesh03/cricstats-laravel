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

    public function index() {

        $apiEndpoint = Config::get('constants.API_ENDPOINTS.OFFICIALS');

        $queryStrs = [
            'include'   => 'country',
            'sort'      => 'fullname'
        ];

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

        return view('officials/listing', compact('applicableOfficials', 'helper'));
    }
}
