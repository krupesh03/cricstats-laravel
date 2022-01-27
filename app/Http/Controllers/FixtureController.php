<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Helpers\ApicallHelper;
use App\Helpers\FunctionHelper;
use Config;

class FixtureController extends Controller
{
    public function __construct() {

        $this->apicallHelper = new ApicallHelper;
        $this->functionHelper = new FunctionHelper;
    }

    public function index( $id ) {

        $fixturesApiEndpoint = Config::get('constants.API_ENDPOINTS.FIXTURES');

        $fixturesApiEndpoint = $fixturesApiEndpoint . '/' . $id;

        $fixturesQueryStr = [
            'include' => 'localteam,visitorteam,batting.result,venue,manofmatch,batting.batsman,batting.bowler,tosswon,runs.team,batting.catchstump,batting.runoutby,bowling.bowler'
        ];

        $fixture = $this->apicallHelper->getDataFromAPI( $fixturesApiEndpoint, $fixturesQueryStr );

        $helper = $this->functionHelper;

        return view('fixtures/fixture', compact('fixture', 'helper'));
    }
}
