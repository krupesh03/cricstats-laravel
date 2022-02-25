@extends('layouts/app')

@section('content')

<div class="heading"> 
    {{ $livedetails['localteam']['name'] }} vs {{ $livedetails['visitorteam']['name'] }}, {{ $livedetails['details']['round'] }} - Live Cricket Score, Commentary
</div>
<div class="subheading">
    <span> Series : {{ $livedetails['league']['id'] == 3 ? $livedetails['stage']['name'] : $livedetails['league']['name'] }}, {{ $livedetails['season']['name'] }} </span>
    <span> Venue : 
        @if( isset($livedetails['venue']['id']) && !empty($livedetails['venue']['id']) ) 
            <a href="/venues/{{ $livedetails['venue']['id'] }}">{{ isset($livedetails['venue']['name']) ? $livedetails['venue']['name'] : '' }}, {{ isset($livedetails['venue']['city']) ? $livedetails['venue']['city'] : '' }}</a>
        @endif
    </span>
    <span> Date & Time : {{ isset($livedetails['details']['starting_at']) && !empty($livedetails['details']['starting_at']) ? date('M d, Y h:i A', strtotime($livedetails['details']['starting_at'])) : '' }} </span>
</div>
<div class="sub-menu">
    <div class="sub-menu-one active"> 
        <a href="/livescores/{{ $livedetails['fixtureId'] }}/score"> Commentary </a> 
    </div>
    <div class="sub-menu-two">
        <a href="/fixture/{{ $livedetails['fixtureId'] }}"> Scorecard </a>
    </div>
</div>
<hr />

<div class="row main-div">
    <div class="live-scorecard {{ !$livedetails['details']['liveMatch'] ? '' : 'match-in-progress' }}">
        @if( isset($livedetails['runs']['data']) && !empty($livedetails['runs']['data']) )
            @foreach( $livedetails['runs']['data'] as $run )
                <div class="{{ $run['inning'] == count($livedetails['runs']['data']) ? 'innings-progress-score' : 'innings-completed-score' }}">
                    <img src="{{ $helper->setImage($run['team']['image_path']) }}">
                    {{ $run['team']['code'] }} {{ $run['score'] }}-{{ $run['wickets'] }} ({{ $run['overs'] }})
                    <span>
                        @if( $run['inning'] == count($livedetails['runs']['data']) && strtolower($livedetails['details']['status']) != "finished" )
                            CRR: {{ $run['crr'] }}
                        @endif
                        @if( $run['inning'] == count($livedetails['runs']['data']) && $livedetails['runs']['rr'] && strtolower($livedetails['details']['status']) != "finished" )
                            REQ: {{ $livedetails['runs']['rr'] }}
                        @endif
                    </span>
                </div>
                <div class="match-note">
                    @if( strtolower($livedetails['details']['status']) != "finished" && ($run['inning']%2) == 0 )
                        @if( $run['inning'] == count($livedetails['runs']['data']) && (int)$livedetails['runs']['required_total'] > 0 )
                            {{  $run['team']['name'] . ' need ' . $livedetails['runs']['required_total'] .' runs' }}
                            @if( isset($livedetails['details']['balls_remaining']) && $livedetails['details']['balls_remaining'] )
                                {{ $livedetails['details']['balls_remaining'] }}
                            @endif
                        @endif
                    @elseif( $run['inning'] == count($livedetails['runs']['data']) )
                        @if( isset($livedetails['details']['status']) && ($livedetails['details']['status'] == '1st Innings' || $livedetails['details']['status'] == 'NS') )
                            @if( isset($livedetails['tosswon']['name']) && isset($livedetails['details']['elected']) )
                                {{ $livedetails['tosswon']['name'] . ' opt for ' . $livedetails['details']['elected'] }}
                            @else
                                {{ $livedetails['details']['status'] }}
                            @endif
                        @elseif( isset($livedetails['details']['status']) && $livedetails['details']['status'] == 'Innings Break' )
                            {{ $livedetails['details']['status'] }}
                        @else
                            {{ $livedetails['details']['note'] ? $livedetails['details']['note'] : $livedetails['details']['status'] }}
                        @endif
                    @endif
                </div>
            @endforeach
        @endif

        @if( isset($livedetails['details']['status']) && $livedetails['details']['status'] == 'NS' )
            @if( isset($livedetails['tosswon']['name']) && isset($livedetails['details']['elected']) )
                <div class="match-note">
                    {{ $livedetails['tosswon']['name'] . ' opt for ' . $livedetails['details']['elected'] }}
                </div>              
            @endif  
        @endif

        @if( $livedetails['details']['liveMatch'] && $livedetails['details']['status'] != 'NS' && strpos($livedetails['details']['note'], 'Super Over') === false )
            <div class="row progress-summary">
                <div class="col-md-8">
                    <table class="table batting-table" width="100%">
                        <tr>
                            <th width="40%">Batter</th>
                            <th width="10%">R</th>
                            <th width="10%">B</th>
                            <th width="10%">4s</th>
                            <th width="10%">6s</th>
                            <th width="10%">SR</th>
                        </tr>
                        @foreach( $batsman as $key => $value )
                            @if( isset($value['fullname']) && !empty($value['fullname']) )
                                <tr>
                                    <td> 
                                        <a href="{{ '/players/' . $value['id'] }}"> {{ $value['fullname'] }} </a> 
                                        @if( $value['on_strike'] ) <span>*</span> @endif 
                                    </td>
                                    <td> {{ isset($value['scores']['score']) ? $value['scores']['score'] : '0' }} </td>
                                    <td> {{ isset($value['scores']['ball']) ? $value['scores']['ball'] : '0' }} </td>
                                    <td> {{ isset($value['scores']['four_x']) ? $value['scores']['four_x'] : '0' }} </td>
                                    <td> {{ isset($value['scores']['six_x']) ? $value['scores']['six_x'] : '0' }} </td>
                                    <td> {{ isset($value['scores']['rate']) ? $value['scores']['rate'] : '0' }} </td>
                                </tr>
                            @endif
                        @endforeach
                    </table>
                    <table class="table bowling-table" width="100%">
                        <tr>
                            <th width="40%">Bowler</th>
                            <th width="10%">O</th>
                            <th width="10%">M</th>
                            <th width="10%">R</th>
                            <th width="10%">W</th>
                            <th width="10%">ECO</th>
                        </tr>
                        @foreach( $bowler as $key => $value )
                            @if( isset($value['fullname']) && !empty($value['fullname']) )
                                <tr>
                                    <td> 
                                        <a href="{{ '/players/' . $value['id'] }}"> {{ $value['fullname'] }} </a> 
                                        @if( $value['on_strike'] ) <span>*</span> @endif 
                                    </td>
                                    <td> {{ isset($value['figures']['overs']) ? $value['figures']['overs'] : '0' }} </td>
                                    <td> {{ isset($value['figures']['medians']) ? $value['figures']['medians'] : '0' }} </td>
                                    <td> {{ isset($value['figures']['runs']) ? $value['figures']['runs'] : '0' }} </td>
                                    <td> {{ isset($value['figures']['wickets']) ? $value['figures']['wickets'] : '0' }} </td>
                                    <td> {{ isset($value['figures']['rate']) ? $value['figures']['rate'] : '0' }} </td>
                                </tr>
                            @endif
                        @endforeach
                    </table>
                </div>
                <div class="col-md-4">
                    <table class="table key-stats" width="100%">
                        <tr>
                            <th>Key Stats</th>
                        </tr>
                        @if( isset($keyStats['partnership']) && !empty($keyStats['partnership']) )
                            <tr>
                                <td>
                                    <span class="key-stat-style">Partnership:</span> {{ $keyStats['partnership'] }}
                                </td>
                            </tr>
                        @endif
                        @if( isset($keyStats['last_wkt']) && !empty($keyStats['last_wkt']) )
                            <tr>
                                <td>
                                    <span class="key-stat-style">Last Wkt:</span> {{ $keyStats['last_wkt'] }}
                                </td>
                            </tr>
                        @endif
                        @if( isset($keyStats['toss']) && !empty($keyStats['toss']) )
                            <tr>
                                <td>
                                    <span class="key-stat-style">Toss:</span> {{ $keyStats['toss'] }}
                                </td>
                            </tr>
                        @endif
                    </table>
                </div>
            </div>
        @else
            @if( isset($livedetails['details']['status']) && strtolower($livedetails['details']['status']) != 'finished' )
                <div class="match-status">
                    @if( $livedetails['details']['status'] == 'NS' )
                        <span class="ns"> {{ !empty($livedetails['details']['starting_at']) ? 'Match Start Time : ' . date('l, d M, h:i A ', strtotime($livedetails['details']['starting_at'])) : '' }} </span> 
                    @else
                        <span class="not-ns"> {{ !empty($livedetails['details']['status']) ? 'Match is '. $livedetails['details']['status'] : '' }} </span>
                    @endif
                </div>
            @endif
        @endif
        <hr />

        <div class="live-commentary">
            <div class="match-awards">
                @if( isset($livedetails['manofmatch']['fullname']) )
                    <div class="fixture-mom">
                        Player of the match
                        <img src="{{ isset($livedetails['manofmatch']['image_path']) ? $helper->setImage($livedetails['manofmatch']['image_path']) : '' }}">
                        <div class="fixture-mom-name"> 
                            {{ isset($livedetails['manofmatch']['fullname']) ? $livedetails['manofmatch']['fullname'] : '' }} {{ isset($livedetails['manofmatch']['position']['name']) ? '('.$livedetails['manofmatch']['position']['name'].')' : '' }} 
                        </div>
                    </div>
                @endif
                @if( isset($livedetails['manofseries']['fullname']) )
                    <div class="fixture-pos">
                        Player of the series
                        <img src="{{ $helper->setImage($livedetails['manofseries']['image_path']) }}">
                        <div class="fixture-pos-name"> 
                            {{ $livedetails['manofseries']['fullname'] }} ({{ $livedetails['manofseries']['position']['name'] }})
                        </div>
                    </div>
                @endif
            </div>
            <table class="table" width="100%">
                @if( strtolower($livedetails['details']['status']) == 'innings break' )
                    <tr class="end-of-innings">
                        <td colspan="3"> 
                            <span> {{ strtoupper($livedetails['details']['status']) }} {{ isset($keyStats['innings_score']) && !empty($keyStats['innings_score']) ? ' - ' . $keyStats['innings_score'] : '' }} </span>
                        </td>
                    </tr>
                @elseif( strtolower($livedetails['details']['status']) == 'finished' )
                    <tr class="end-of-innings">
                        <td colspan="3"> 
                            <span> {{ strtoupper($livedetails['details']['note']) }} </span> 
                        </td>
                    </tr>
                @endif
                @foreach( $liveCommentory as $id => $commentory )
                    @if( strpos($commentory['ball'], '.6') !== false && $commentory['score']['ball'] && !$commentory['score']['noball'] )
                        <tr class="end-of-over">
                            <td> 
                                <span> {{ ceil($commentory['ball']) }} </span> Ov </td>
                            <td colspan="2">
                                Runs Scored : <span> {{ isset($perOverScore[$commentory['team_id']][$commentory['scoreboard']][ceil($commentory['ball'])]) ? array_sum($perOverScore[$commentory['team_id']][$commentory['scoreboard']][ceil($commentory['ball'])]) : 0 }} </span>
                                <div> {{ isset($perOverBallScore[$commentory['team_id']][$commentory['scoreboard']][ceil($commentory['ball'])]) ? implode(' ', $perOverBallScore[$commentory['team_id']][$commentory['scoreboard']][ceil($commentory['ball'])]) : '' }} </div>
                            </td>
                        </tr>
                    @endif
                    <tr>
                        <td width="10%">
                            @if( $commentory['score']['is_wicket'] )
                                <div class="wickets"> W </div>
                            @else
                                @if( $commentory['score']['four'] )
                                    <div class="boundary"> {{ $commentory['score']['runs'] }} </div>
                                @elseif( $commentory['score']['six'] )
                                    <div class="huge-one"> {{ $commentory['score']['runs'] }} </div>
                                @else
                                    <div class="runnings">
                                        @if( $commentory['score']['leg_bye'] )
                                            {{ $commentory['score']['leg_bye'] . 'lb' }}
                                        @elseif( $commentory['score']['bye'] )
                                            {{ $commentory['score']['bye'] . 'b' }}
                                        @elseif( $commentory['score']['noball'] )
                                            {{ $commentory['score']['noball_runs'] . 'nb' }}
                                        @elseif( !$commentory['score']['ball'] )
                                            {{ $commentory['score']['runs'] . 'wd' }}
                                        @else 
                                            {{ $commentory['score']['runs'] }}
                                        @endif
                                    </div>
                                @endif
                            @endif
                        </td>
                        <td width="5%"> {{ $commentory['ball'] }} </td>
                        <td width="85%"> 
                            {{ $commentory['bowler']['fullname'] }} to {{ $commentory['batsman']['fullname'] }}, 
                            @if( $commentory['score']['is_wicket'] || $commentory['score']['four'] || $commentory['score']['six'] )
                                <span> {{ strtoupper($commentory['score']['name']) }}. </span>
                            @else 
                                {{ $commentory['score']['name'] }}.
                            @endif
                            @if( strpos($commentory['score']['name'], 'Catch') !== false && !empty($commentory['catchstump']) )
                                caught by {{ $commentory['catchstump']['fullname'] }}.
                            @elseif( strpos($commentory['score']['name'], 'Run') !== false )
                                @if( $commentory['runoutby'] && $commentory['catchstump'] )
                                    throw by {{ $commentory['runoutby']['fullname'] }} and {{ $commentory['catchstump']['fullname'] }} destroys the stumps.
                                @elseif( $commentory['runoutby'] )
                                    direct hit by {{ $commentory['runoutby']['fullname'] }}.
                                @endif
                            @elseif( strpos($commentory['score']['name'], 'Stump') !== false && $commentory['catchstump'])
                                    stumped by {{ $commentory['catchstump']['fullname'] }}.
                            @endif
                            <span>
                                @if( $commentory['score']['is_wicket'] ) <br />
                                    {{ isset($commentory['batsmanout']['fullname']) ? $commentory['batsmanout']['fullname'] : $commentory['batsman']['fullname'] }}
                                @endif
                                @if( $commentory['score']['is_wicket'] && strpos($commentory['score']['name'], 'Catch') !== false && $commentory['catchstump'] && $commentory['bowler']['id'] == $commentory['catchstump']['id'] )
                                    c &
                                @elseif( $commentory['score']['is_wicket'] && strpos($commentory['score']['name'], 'Catch') !== false && $commentory['catchstump'] )
                                    c {{ $commentory['catchstump']['fullname'] }}
                                @elseif( $commentory['score']['is_wicket'] && strpos($commentory['score']['name'], 'Run') !== false )
                                    @if( $commentory['runoutby'] )
                                        run out {{ $commentory['runoutby']['fullname'] }}
                                    @elseif( $commentory['catchstump'] )
                                        run out {{ $commentory['catchstump']['fullname'] }}
                                    @endif
                                @elseif( $commentory['score']['is_wicket'] && strpos($commentory['score']['name'], 'LBW') !== false )
                                    lbw
                                @elseif( $commentory['score']['is_wicket'] && strpos($commentory['score']['name'], 'Stump') !== false && $commentory['catchstump'])
                                    st {{ $commentory['catchstump']['fullname'] }}
                                @endif

                                @if( $commentory['score']['is_wicket'] && $commentory['bowler'] && !$commentory['runoutby'] && strpos($commentory['score']['name'], 'Run') === false )
                                    b {{ $commentory['bowler']['fullname'] }}
                                @endif

                                @if( $commentory['score']['is_wicket'] && $commentory['batsmanout_id'] )
                                    {{ $batsmanData[$commentory['batsmanout_id']]['score'] .'('. $batsmanData[$commentory['batsmanout_id']]['ball'] .')' }}.
                                @elseif( $commentory['score']['is_wicket'] && $commentory['batsman_id'] )
                                    {{ $batsmanData[$commentory['batsman_id']]['score'] .'('. $batsmanData[$commentory['batsman_id']]['ball'] .')' }}.
                                @endif
                            </td>
                    </tr>
                    @if( isset($commentory['innings_score']) )
                        <tr class="end-of-innings">
                            <td colspan="3"> 
                                <span> INNINGS BREAK {{ !empty($commentory['innings_score']) ? ' - ' . $commentory['innings_score'] : '' }} </span>
                            </td>
                        </tr>
                    @endif
                @endforeach
            </table>
        </div>

        <table class="table load-more-commentary" style="display:none">
            <tr><td>Load More Commentary</td></tr>
        </table>
        
        @if( isset($livedetails['lineup']) && !empty($livedetails['lineup']) )
            <div class="team-playing-eleven">
                <table class="table playing-eleven" width="100%">
                    <tr>
                        <th width="20%"> {{ $livedetails['visitorteam']['name'] }} Playing XI</th>
                        <td width="80%">
                        @foreach( $livedetails['lineup'] as $lineup )
                            @if( $lineup['lineup']['team_id'] == $livedetails['visitorteam']['id'] )
                                {{  $lineup['fullname'] }}
                                @if( $lineup['lineup']['captain'] )
                                    (c)
                                @endif
                                @if( $lineup['lineup']['wicketkeeper'] )
                                    (wk)
                                @endif,
                            @endif
                        @endforeach
                        </td>
                    </tr>
                </table>  
                <table class="table playing-eleven" width="100%">
                    <tr>
                        <th width="20%"> {{ $livedetails['localteam']['name'] }} Playing XI</th>
                        <td width="80%">
                        @foreach( $livedetails['lineup'] as $lineup )
                            @if( $lineup['lineup']['team_id'] == $livedetails['localteam']['id'] )
                                {{  $lineup['fullname'] }}
                                @if( $lineup['lineup']['captain'] )
                                    (c)
                                @endif
                                @if( $lineup['lineup']['wicketkeeper'] )
                                    (wk)
                                @endif,
                            @endif
                        @endforeach
                        </td>
                    </tr>
                </table>                 
            </div>
        @endif

    </div>
</div>

@endsection