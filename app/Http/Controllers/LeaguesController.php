<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Helpers\ApicallHelper;
use Config;

class LeaguesController extends Controller
{
    public function __construct() {

        $this->apicallHelper = new ApicallHelper;
    }

    public function index() {

        $apiEndpoint = Config::get('constants.API_ENDPOINTS.LEAGUES');

        $leagues = $this->apicallHelper->getDataFromAPI( $apiEndpoint );
        
        return view('leagues', compact('leagues'));
    }
}
