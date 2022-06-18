@extends('layouts/app')

@section('content')

@if( $fixture['success'] )
    <div class="heading"> 
        {{ $fixture['data']['localteam']['name'] }} vs {{ $fixture['data']['visitorteam']['name'] }}, {{ $fixture['data']['round'] }} - Live Cricket Score, Commentary
    </div>
    <div class="subheading">
        <span> Series : <a href="/fixture/stage/{{ $fixture['data']['stage']['id'] }}/{{ $fixture['data']['league']['id'] }}/{{ $fixture['data']['season']['id'] }}">
            {{ $fixture['data']['league']['id'] == 3 ? $fixture['data']['stage']['name'] : $fixture['data']['league']['name'] }}, {{ $fixture['data']['season']['name'] }} </a> 
        </span>
        <span> Venue : 
            @if( isset($fixture['data']['venue']['id']) && !empty($fixture['data']['venue']['id']) ) 
                <a href="/venues/{{ $fixture['data']['venue']['id'] }}">{{ isset($fixture['data']['venue']['name']) ? $fixture['data']['venue']['name'] : '' }}, {{ isset($fixture['data']['venue']['city']) ? $fixture['data']['venue']['city'] : '' }} </a>
            @endif
        </span>
        <span> Date & Time : {{ isset($fixture['data']['starting_at']) && !empty($fixture['data']['starting_at']) ? date('M d, Y h:i A', strtotime($fixture['data']['starting_at'])) : '' }} </span>
    </div>
    <div class="sub-menu">
        <div class="sub-menu-one"> 
            <a href="/livescores/{{ $fixture['data']['id'] }}/score"> Commentary </a>
        </div>
        <div class="sub-menu-two active">
            <a href="/fixture/{{ $fixture['data']['id'] }}"> Scorecard </a>
        </div>
    </div>
    <hr />

    <div class="row main-div">
        <div class="fixture-scorecard">
            <div class="scorecard-label">
                SCORECARD
                <div class="scorecard-result">
                    @if( isset($fixture['data']['status']) && ($fixture['data']['status'] == '1st Innings' || $fixture['data']['status'] == 'NS') )
                        @if( isset($fixture['data']['tosswon']['name']) && isset($fixture['data']['elected']) ) 
                           ({{ $fixture['data']['tosswon']['name'] . ' opt for ' . $fixture['data']['elected'] }})
                        @else
                            ({{ $fixture['data']['status'] == 'NS' ? 'Match not started' : $fixture['data']['status'] }})
                        @endif
                    @elseif( isset($fixture['data']['status']) && $fixture['data']['status'] == 'Innings Break' )
                            ({{ $fixture['data']['status'] }})
                    @else
                        @if( $fixture['data']['note'] )
                            ({{ $fixture['data']['note'] }})
                        @elseif( $fixture['data']['status'] == 'NS' )
                            (Match not started)
                        @else
                            {{ $fixture['data']['status'] ? '(The match is '.$fixture['data']['status'].')' : '' }}
                        @endif
                    @endif
                </div>
            </div>
            <div class="fixture-awards">
                @if( isset($fixture['data']['manofmatch']['fullname']) )
                    <div class="fixture-mom">
                        Player of the match
                        <img src="{{ isset($fixture['data']['manofmatch']['image_path']) ? $helper->setImage($fixture['data']['manofmatch']['image_path']) : '' }}">
                        <div class="fixture-mom-name"> 
                            {{ isset($fixture['data']['manofmatch']['fullname']) ? $fixture['data']['manofmatch']['fullname'] : '' }} {{ isset($fixture['data']['manofmatch']['position']['name']) ? '('.$fixture['data']['manofmatch']['position']['name'].')' : '' }} 
                        </div>
                    </div>
                @endif
                @if( isset($fixture['data']['manofseries']['fullname']) )
                    <div class="fixture-pos">
                        Player of the series
                        <img src="{{ $helper->setImage($fixture['data']['manofseries']['image_path']) }}">
                        <div class="fixture-pos-name"> 
                            {{ $fixture['data']['manofseries']['fullname'] }} ({{ $fixture['data']['manofseries']['position']['name'] }})
                        </div>
                    </div>
                @endif
            </div>
            <hr />

            @foreach( $scorecardArray as $inning => $data )
                <div class="fixture-innings"> 
                    <div class="team-name"> 
                        {{ isset($data['runs']['team']['name']) ? $data['runs']['team']['name'] : '' }} Innings 
                    </div>
                    <div class="team-score"> 
                        {{ isset($data['runs']['score']) ? $data['runs']['score'] : '' }}-{{ isset($data['runs']['wickets']) ? $data['runs']['wickets'] : '' }} ({{ isset($data['runs']['overs']) ? $data['runs']['overs'] : '' }} Ov) 
                    </div>
                </div>

                <table class="table match-scorecard" width="100%">
                    <tr>
                        <th width="20%">Batter</th>
                        <th colspan="2"></th>
                        <th width="5%">R</th>
                        <th width="5%">B</th>
                        <th width="5%">4s</th>
                        <th width="5%">6s</th>
                        <th width="5%">SR</th>
                    </tr>
                    @foreach( $data['batting'] as $score )
                        <tr>
                            <td width="20%">
                                <a href="/players/{{ $score['player_id'] }}">
                                    {{ $score['batsman']['fullname'] }} 
                                    @if( isset($lineupArray[$score['batsman']['id']]['captain']) && $lineupArray[$score['batsman']['id']]['captain'] )
                                        (c)
                                    @endif
                                    @if( isset($lineupArray[$score['batsman']['id']]['wicketkeeper']) && $lineupArray[$score['batsman']['id']]['wicketkeeper'] )
                                        (wk)
                                    @endif
                                </a>
                            </td>
                            <td width="31%">
                                @if( $score['result']['out'] && strpos($score['result']['name'], 'Catch') !== false && $score['catchstump'] && $score['bowler']['id'] == $score['catchstump']['id'] )
                                    <span class="caught-bowled"> c & </span> 
                                @elseif( $score['result']['out'] && strpos($score['result']['name'], 'Catch') !== false && $score['catchstump'])
                                    c {{ $score['catchstump']['fullname'] }}
                                @elseif( $score['result']['out'] && strpos($score['result']['name'], 'Run') !== false )
                                    @if( $score['runoutby'] )
                                        run out ({{ $score['runoutby']['fullname'] }})
                                    @elseif( $score['catchstump'] )
                                        run out ({{ $score['catchstump']['fullname'] }})
                                    @endif
                                @elseif( $score['result']['out'] && strpos($score['result']['name'], 'LBW') !== false )
                                    lbw
                                @elseif( $score['result']['out'] && strpos($score['result']['name'], 'Stump') !== false && $score['catchstump'])
                                    st {{ $score['catchstump']['fullname'] }}
                                @elseif( $score['result']['out'] && strpos($score['result']['name'], 'Hit') !== false )
                                    Hit Wicket
                                @elseif( strpos($score['result']['name'], 'Retired') !== false )
                                    {{ $score['result']['name'] }}
                                @endif
                            </td>
                            <td width="24%">
                                @if( strpos($score['result']['name'], 'Run') === false && strpos($score['result']['name'], 'Retired') === false )
                                    @if( $score['result']['out'] && $score['bowler'] )
                                        b {{ $score['bowler']['fullname'] }}
                                    @elseif( isset($fixture['data']['status']) && (int)preg_replace('/[^0-9]/', '', $fixture['data']['status']) == $inning )
                                        batting
                                    @elseif(  !$score['result']['out'] )
                                        not out
                                    @endif
                                @endif
                            </td>
                            <td width="5%">{{ $score['score'] }}</td>
                            <td width="5%">{{ $score['ball'] }}</td>
                            <td width="5%">{{ $score['four_x'] }}</td>
                            <td width="5%">{{ $score['six_x'] }}</td>
                            <td width="5%">{{ $score['rate'] }}</td>
                        </tr>
                    @endforeach

                    @foreach( $data['scoreboards'] as $type => $scoreTotal )
                        <tr>
                            <td width="20%">{{ ucfirst($type) }}</td>
                            <td colspan="7" align="right">
                                @if( $type == 'extra' ) 
                                    <b>{{ (int)$scoreTotal['bye'] + (int)$scoreTotal['leg_bye'] + (int)$scoreTotal['wide'] + (int)$scoreTotal['noball_runs'] + (int)$scoreTotal['penalty'] }}</b> ({{ 'b ' . $scoreTotal['bye'] . ', lb ' . $scoreTotal['leg_bye'] . ', w ' . (int)$scoreTotal['wide'] .', nb ' . $scoreTotal['noball_runs'] .', p ' . $scoreTotal['penalty'] }})
                                @endif
                                @if( $type == 'total' )
                                    <b>{{ $scoreTotal['total'] }}</b> ({{ $scoreTotal['wickets'] . ' wkts, ' . $scoreTotal['overs'] . ' Ov' }})
                                @endif
                            </td>
                        </tr>
                    @endforeach

                    @if( !empty($data['dnb']) )
                        <tr>
                            <td width="20%">{{ isset($fixture['data']['status']) && (int)preg_replace('/[^0-9]/', '', $fixture['data']['status']) == $inning ? 'Yet to Bat' : 'Did not Bat' }}</td>
                            <td colspan="7" align="left">
                                {!! implode(', ', $data['dnb']) !!}
                            </td>
                        </tr>
                    @endif
                </table>

                @if( !empty($data['fallOfWkts']) )
                    <table class="table match-scorecard" width="100%">
                        <tr>
                            <th>Fall of Wickets</th>
                        </tr>
                        <tr>
                            <td>
                                {!! implode(', ', $data['fallOfWkts']) !!}
                            </td>
                        </tr>
                    </table>
                @endif

                <table class="table match-scorecard" width="100%">
                    <tr>
                        <th width="65%">Bowler</th>
                        <th width="5%">O</th>
                        <th width="5%">M</th>
                        <th width="5%">R</th>
                        <th width="5%">W</th>
                        <th width="5%">NB</th>
                        <th width="5%">WD</th>
                        <th width="5%">ECO</th>
                    </tr>
                    @foreach( $data['bowling'] as $score )
                        <tr>
                            <td width="65%">
                                <a href="/players/{{ $score['player_id'] }}"> {{ $score['bowler']['fullname'] }} </a>
                            </td>
                            <td width="5%">{{ $score['overs'] }}</td>
                            <td width="5%">{{ $score['medians'] }}</td>
                            <td width="5%">{{ $score['runs'] }}</td>
                            <td width="5%">{{ $score['wickets'] }}</td>
                            <td width="5%">{{ $score['noball'] }}</td>
                            <td width="5%">{{ $score['wide'] }}</td>
                            <td width="5%">{{ $score['rate'] }}</td>
                        </tr>
                    @endforeach
                </table>
            @endforeach

            <div class="fixture-match-info"> 
                <div class="match-info"> Match Info </div>
            </div>
            <table class="table match-info-table" width="100%">
                <tr>
                    <td width="20%">Match</td>
                    <td width="80%">
                        {{ $fixture['data']['localteam']['code'] }} vs {{ $fixture['data']['visitorteam']['code'] }}, {{ $fixture['data']['round'] }}, {{ $fixture['data']['league']['id'] == 3 ? $fixture['data']['stage']['name'] : $fixture['data']['league']['name'] }}, {{ $fixture['data']['season']['name'] }}
                    </td>
                </tr>
                <tr>
                    <td width="20%">Date</td>
                    <td width="80%">
                        {{ isset($fixture['data']['starting_at']) && !empty($fixture['data']['starting_at']) ? date('l, F d, Y', strtotime($fixture['data']['starting_at'])) : '' }}
                    </td>
                </tr>
                <tr>
                    <td width="20%">Toss</td>
                    <td width="80%">
                        @if( isset($fixture['data']['tosswon']['name']) && isset($fixture['data']['elected']) )
                            {{ $fixture['data']['tosswon']['name'] }} won the toss and opt for {{ $fixture['data']['elected'] }}
                        @endif
                    </td>
                </tr>
                <tr>
                    <td width="20%">Time</td>
                    <td width="80%">
                        {{ isset($fixture['data']['starting_at']) && !empty($fixture['data']['starting_at']) ? date('h:i A (M d)', strtotime($fixture['data']['starting_at'])) : '' }} 
                    </td>
                </tr>
                <tr>
                    <td width="20%">Venue</td>
                    <td width="80%">
                        {{ isset($fixture['data']['venue']['name']) ? $fixture['data']['venue']['name'] : '' }}, {{ isset($fixture['data']['venue']['city']) ? $fixture['data']['venue']['city'] : '' }}{{ isset($fixture['data']['venue']['country']['name']) ? ', ' . $fixture['data']['venue']['country']['name'] : '' }} 
                    </td>
                </tr>
                <tr>
                    <td width="20%">Umpires</td>
                    <td width="80%">
                        {{ isset($fixture['data']['firstumpire']['fullname']) ? $fixture['data']['firstumpire']['fullname'] .', ' : '' }}{{ isset($fixture['data']['secondumpire']['fullname']) ? $fixture['data']['secondumpire']['fullname'] : '' }} 
                    </td>
                </tr>
                <tr>
                    <td width="20%">Third Umpire</td>
                    <td width="80%">
                        {{ isset($fixture['data']['tvumpire']['fullname']) ? $fixture['data']['tvumpire']['fullname'] : '' }}
                    </td>
                </tr>
                <tr>
                    <td width="20%">Match Referee</td>
                    <td width="80%">
                        {{ isset($fixture['data']['referee']['fullname']) ? $fixture['data']['referee']['fullname'] : '' }}
                    </td>
                </tr>
                <tr>
                    <td width="20%">Playing {{ $fixture['data']['localteam']['name'] }} Squad</td>
                    <td width="80%">
                        {{ implode(', ', $localTeamSquad) }}
                    </td>
                </tr>
                <tr>
                    <td width="20%">Playing {{ $fixture['data']['visitorteam']['name'] }} Squad</td>
                    <td width="80%">
                        {{ implode(', ', $visitorTeamSquad) }}
                    </td>
                </tr>
            </table>
        </div>
    </div>
@endif

@endsection