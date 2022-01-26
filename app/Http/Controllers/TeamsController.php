<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Helpers\ApicallHelper;
use App\Helpers\FunctionHelper;
use Config;

class TeamsController extends Controller
{
    public function __construct() {

        $this->apicallHelper = new ApicallHelper;
        $this->functionHelper = new FunctionHelper;
    }

    public function index() {

        $teamApiEndpoint = Config::get('constants.API_ENDPOINTS.TEAMS');

        $teamQueryStrs = [
            'sort'   => 'name'
        ];

        $teams = $this->apicallHelper->getDataFromAPI( $teamApiEndpoint, $teamQueryStrs );

        if( $teams['success'] ) {

            $nationalTeams = [];
            foreach( $teams['data'] as $team ) {
                if($team['national_team']) {
                    $nationalTeams[$team['id']] = $team['id'];
                }
            }

            $seasonsApiEndpoint = Config::get('constants.API_ENDPOINTS.SEASONS');

            $internationalLeagueId = 3;
            $seasonQueryStrs = [
                'filter[league_id]' => $internationalLeagueId,
                'include'           => 'teams',
                'sort'              => 'id'
            ];
        
            $seasonTeams = $this->apicallHelper->getDataFromAPI( $seasonsApiEndpoint, $seasonQueryStrs );

            if( !$seasonTeams['success'] ) {
                return abort(404);
            }

            $latestSeason = end($seasonTeams['data']);
            $latestSeasonId = $latestSeason['id'];

            $latestSeasonTeams = [];
            foreach( $latestSeason['teams'] as $ls ) {
                if( in_array( $ls['id'], $nationalTeams  ) ) {
                    $latestSeasonTeams[] = $ls;
                }
            }
            $latestSeason['teams'] = $latestSeasonTeams;
            
            $helper = $this->functionHelper;
            
            return view('teams/teams', compact('latestSeason', 'helper'));
        }

        return abort(404);
    }
}
