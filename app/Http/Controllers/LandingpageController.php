<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Helpers\ApicallHelper;
use Config;

class LandingpageController extends Controller
{
    public function __construct() {

        $this->apicallHelper = new ApicallHelper;
    }

    public function index() {

        $apiEndpoint = Config::get('constants.API_ENDPOINTS.TEAMS');

        $queryStrs = [
            'sort'   => 'name'
        ];

        $teams = $this->apicallHelper->getDataFromAPI( $apiEndpoint, $queryStrs );
        
        return view('landingpage', compact('teams'));
    }
}
