@extends('layouts/app')

@section('content')

@if( $fixture['success'] )
    <div class="heading"> 
        {{ $fixture['data']['localteam']['name'] }} vs {{ $fixture['data']['visitorteam']['name'] }}, {{ $fixture['data']['round'] }}
    </div>
    <div class="subheading">
        <span>Series : {{ $fixture['data']['stage']['name'] }}, {{ date('Y', strtotime($fixture['data']['starting_at'])) }}</span>
        <span>Venue : {{ $fixture['data']['venue']['name'] }}, {{ $fixture['data']['venue']['city'] }}{{ isset($fixture['data']['venue']['country']['name']) ? ', ' . $fixture['data']['venue']['country']['name'] : '' }}</span>
        <span>Date & Time : {{ date('M d, Y H:i A', strtotime($fixture['data']['starting_at'])) }}</span>
    </div>
    <hr />

    <div class="row main-div">
        <div class="fixture-scorecard">
            <div class="scorecard-label">
                SCORECARD
                <div class="scorecard-result">
                    ({{ $fixture['data']['note'] }})
                </div>
            </div>
            <div class="fixture-awards">
                <div class="fixture-mom">
                    Player of the match
                    <img src="{{ $helper->setImage($fixture['data']['manofmatch']['image_path']) }}">
                    <div class="fixture-mom-name"> 
                        {{ $fixture['data']['manofmatch']['fullname'] }} ({{ $fixture['data']['manofmatch']['position']['name'] }}) 
                    </div>
                </div>
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
            <div class="fixture-innings-one"> 
                <div class="team-name"> {{ $fixture['data']['runs'][0]['team']['name'] }} Innings </div>
                <div class="team-score"> {{ $fixture['data']['runs'][0]['score'] }}-{{ $fixture['data']['runs'][0]['wickets'] }} ({{ $fixture['data']['runs'][0]['overs'] }} Ov) </div>
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
                @php $s1teamId = 0; @endphp
                @foreach( $fixture['data']['batting'] as $score )
                    @if( strtolower($score['scoreboard']) == 's1' )
                        <tr>
                            <td width="20%">
                                {{ $score['batsman']['fullname'] }} 
                                @if( isset($lineupArray[$score['batsman']['id']]['captain']) && $lineupArray[$score['batsman']['id']]['captain'] )
                                    (c)
                                @endif
                                @if( isset($lineupArray[$score['batsman']['id']]['wicketkeeper']) && $lineupArray[$score['batsman']['id']]['wicketkeeper'] )
                                    (wk)
                                @endif
                            </td>
                            <td>
                                @if( $score['result']['out'] && strpos($score['result']['name'], 'Catch') !== false && $score['catchstump'])
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
                                @endif
                            </td>
                            <td>
                                @if( $score['result']['out'] && $score['bowler'] )
                                    b {{ $score['bowler']['fullname'] }}
                                @elseif(  !$score['result']['out'] )
                                    not out
                                @endif
                            </td>
                            <td width="5%">{{ $score['score'] }}</td>
                            <td width="5%">{{ $score['ball'] }}</td>
                            <td width="5%">{{ $score['four_x'] }}</td>
                            <td width="5%">{{ $score['six_x'] }}</td>
                            <td width="5%">{{ $score['rate'] }}</td>
                        </tr>
                        @php $s1teamId = $score['team_id']; @endphp
                    @endif
                @endforeach
                @foreach( $fixture['data']['scoreboards'] as $scoreTotal )
                    @if( $scoreTotal['team_id'] == $s1teamId )
                        <tr>
                            <td width="20%">{{ ucfirst($scoreTotal['type']) }}</td>
                            <td colspan="7" align="right">
                                @if( $scoreTotal['type'] == 'extra' ) 
                                    <b>{{ (int)$scoreTotal['bye'] + (int)$scoreTotal['leg_bye'] + (int)$scoreTotal['wide'] + (int)$scoreTotal['noball_runs'] + (int)$scoreTotal['penalty'] }}</b> ({{ 'b ' . $scoreTotal['bye'] . ', lb ' . $scoreTotal['leg_bye'] . ', w ' . (int)$scoreTotal['wide'] .', nb ' . $scoreTotal['noball_runs'] .', p ' . $scoreTotal['penalty'] }})
                                @endif
                                @if( $scoreTotal['type'] == 'total' )
                                    <b>{{ $scoreTotal['total'] }}</b> ({{ $scoreTotal['wickets'] . ' wkts, ' . $scoreTotal['overs'] . ' Ov' }})
                                @endif
                            </td>
                        </tr>
                    @endif
                @endforeach
            </table>

            @php
                if( isset($fowArray[$s1teamId]) && !empty($fowArray[$s1teamId]) ) {
                    ksort($fowArray[$s1teamId]); @endphp
                    <table class="table match-scorecard" width="100%">
                        <tr>
                            <th>Fall of Wickets</th>
                        </tr>
                        <tr>
                            <td>
                                @php 
                                    $wkt1 = 1;
                                    foreach( $fowArray[$s1teamId] as $over1 => $score1 ) {
                                        echo $score1['fow_score'] . '-' . $wkt1 . ' (' . $score1['player'] . ', ' . $over1 .'), ';
                                        $wkt1++;
                                    }
                                @endphp
                            </td>
                        </tr>
                    </table>
            @php
                }
            @endphp

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
                @foreach( $fixture['data']['bowling'] as $score )
                    @if( strtolower($score['scoreboard']) == 's1' )
                        <tr>
                            <td width="65%">{{ $score['bowler']['fullname'] }}</td>
                            <td width="5%">{{ $score['overs'] }}</td>
                            <td width="5%">{{ $score['medians'] }}</td>
                            <td width="5%">{{ $score['runs'] }}</td>
                            <td width="5%">{{ $score['wickets'] }}</td>
                            <td width="5%">{{ $score['noball'] }}</td>
                            <td width="5%">{{ $score['wide'] }}</td>
                            <td width="5%">{{ $score['rate'] }}</td>
                        </tr>
                    @endif
                @endforeach
            </table>

            <div class="fixture-innings-two"> 
                <div class="team-name"> {{ $fixture['data']['runs'][1]['team']['name'] }} Innings </div>
                <div class="team-score"> {{ $fixture['data']['runs'][1]['score'] }}-{{ $fixture['data']['runs'][1]['wickets'] }} ({{ $fixture['data']['runs'][1]['overs'] }} Ov) </div>
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
                @php $s2teamId = 0; @endphp
                @foreach( $fixture['data']['batting'] as $score )
                    @if( strtolower($score['scoreboard']) == 's2' )
                        <tr>
                            <td width="20%">
                                {{ $score['batsman']['fullname'] }} 
                                @if( isset($lineupArray[$score['batsman']['id']]['captain']) && $lineupArray[$score['batsman']['id']]['captain'] )
                                    (c)
                                @endif
                                @if( isset($lineupArray[$score['batsman']['id']]['wicketkeeper']) && $lineupArray[$score['batsman']['id']]['wicketkeeper'] )
                                    (wk)
                                @endif
                            </td>
                            <td>
                                @if( $score['result']['out'] && strpos($score['result']['name'], 'Catch') !== false && $score['catchstump'])
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
                                @endif
                            </td>
                            <td>
                                @if( $score['result']['out'] && $score['bowler'] )
                                    b {{ $score['bowler']['fullname'] }}
                                @elseif(  !$score['result']['out'] )
                                    not out
                                @endif
                            </td>
                            <td width="5%">{{ $score['score'] }}</td>
                            <td width="5%">{{ $score['ball'] }}</td>
                            <td width="5%">{{ $score['four_x'] }}</td>
                            <td width="5%">{{ $score['six_x'] }}</td>
                            <td width="5%">{{ $score['rate'] }}</td>
                        </tr>
                        @php $s2teamId = $score['team_id']; @endphp
                    @endif
                @endforeach
                @foreach( $fixture['data']['scoreboards'] as $scoreTotal )
                    @if( $scoreTotal['team_id'] == $s2teamId )
                        <tr>
                            <td width="20%">{{ ucfirst($scoreTotal['type']) }}</td>
                            <td colspan="7" align="right">
                                @if( $scoreTotal['type'] == 'extra' ) 
                                    <b>{{ (int)$scoreTotal['bye'] + (int)$scoreTotal['leg_bye'] + (int)$scoreTotal['wide'] + (int)$scoreTotal['noball_runs'] + (int)$scoreTotal['penalty'] }}</b> ({{ 'b ' . $scoreTotal['bye'] . ', lb ' . $scoreTotal['leg_bye'] . ', w ' . (int)$scoreTotal['wide'] .', nb ' . $scoreTotal['noball_runs'] .', p ' . $scoreTotal['penalty'] }})
                                @endif
                                @if( $scoreTotal['type'] == 'total' )
                                    <b>{{ $scoreTotal['total'] }}</b> ({{ $scoreTotal['wickets'] . ' wkts, ' . $scoreTotal['overs'] . ' Ov' }})
                                @endif
                            </td>
                        </tr>
                    @endif
                @endforeach
            </table>

            @php
                if( isset($fowArray[$s2teamId]) && !empty($fowArray[$s2teamId]) ) {
                    ksort($fowArray[$s2teamId]); @endphp
                    <table class="table match-scorecard" width="100%">
                        <tr>
                            <th>Fall of Wickets</th>
                        </tr>
                        <tr>
                            <td>
                                @php 
                                    $wkt2 = 1;
                                    foreach( $fowArray[$s2teamId] as $over2 => $score2 ) {
                                        echo $score2['fow_score'] . '-' . $wkt2 . ' (' . $score2['player'] . ', ' . $over2 .'), ';
                                        $wkt2++;
                                    }
                                @endphp
                            </td>
                        </tr>
                    </table>
            @php
                }
            @endphp

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
                @foreach( $fixture['data']['bowling'] as $score )
                    @if( strtolower($score['scoreboard']) == 's2' )
                        <tr>
                            <td width="65%">{{ $score['bowler']['fullname'] }}</td>
                            <td width="5%">{{ $score['overs'] }}</td>
                            <td width="5%">{{ $score['medians'] }}</td>
                            <td width="5%">{{ $score['runs'] }}</td>
                            <td width="5%">{{ $score['wickets'] }}</td>
                            <td width="5%">{{ $score['noball'] }}</td>
                            <td width="5%">{{ $score['wide'] }}</td>
                            <td width="5%">{{ $score['rate'] }}</td>
                        </tr>
                    @endif
                @endforeach
            </table>

            <div class="fixture-match-info"> 
                <div class="match-info"> Match Info </div>
            </div>
            <table class="table match-info-table" width="100%">
                <tr>
                    <td width="20%">Match</td>
                    <td width="80%">
                        {{ $fixture['data']['localteam']['code'] }} vs {{ $fixture['data']['visitorteam']['code'] }}, {{ $fixture['data']['round'] }}, {{ $fixture['data']['stage']['name'] }}, {{ date('Y', strtotime($fixture['data']['starting_at'])) }}
                    </td>
                </tr>
                <tr>
                    <td width="20%">Date</td>
                    <td width="80%">
                        {{ date('l, F d, Y', strtotime($fixture['data']['starting_at'])) }}
                    </td>
                </tr>
                <tr>
                    <td width="20%">Toss</td>
                    <td width="80%">
                        {{ $fixture['data']['tosswon']['name'] }} won the toss and opt for {{ $fixture['data']['elected'] }}
                    </td>
                </tr>
                <tr>
                    <td width="20%">Time</td>
                    <td width="80%">
                        {{ date('H:i A (M d)', strtotime($fixture['data']['starting_at'])) }} 
                    </td>
                </tr>
                <tr>
                    <td width="20%">Venue</td>
                    <td width="80%">
                        {{ $fixture['data']['venue']['name'] }}, {{ $fixture['data']['venue']['city'] }}{{ isset($fixture['data']['venue']['country']['name']) ? ', ' . $fixture['data']['venue']['country']['name'] : '' }} 
                    </td>
                </tr>
                <tr>
                    <td width="20%">Umpires</td>
                    <td width="80%">
                        {{ $fixture['data']['firstumpire']['fullname'] }}, {{ $fixture['data']['secondumpire']['fullname'] }} 
                    </td>
                </tr>
                <tr>
                    <td width="20%">Third Umpire</td>
                    <td width="80%">
                        {{ $fixture['data']['tvumpire']['fullname'] }}
                    </td>
                </tr>
                <tr>
                    <td width="20%">Match Referee</td>
                    <td width="80%">
                        {{ $fixture['data']['referee']['fullname'] }}
                    </td>
                </tr>
                <tr>
                    <td width="20%">Playing {{ $fixture['data']['localteam']['name'] }} Squad</td>
                    <td width="80%">
                        @foreach( $fixture['data']['lineup'] as $squad1 )
                            @if( $squad1['lineup']['team_id'] == $fixture['data']['localteam_id'] )
                                {{ $squad1['fullname'] }}
                                @if( $squad1['lineup']['captain'] )
                                    (c)
                                @endif
                                @if( $squad1['lineup']['wicketkeeper'] )
                                    (wk)
                                @endif,
                            @endif
                        @endforeach
                    </td>
                </tr>
                <tr>
                    <td width="20%">Playing {{ $fixture['data']['visitorteam']['name'] }} Squad</td>
                    <td width="80%">
                        @foreach( $fixture['data']['lineup'] as $squad2 )
                            @if( $squad2['lineup']['team_id'] == $fixture['data']['visitorteam_id'] )
                                {{ $squad2['fullname'] }}
                                @if( $squad2['lineup']['captain'] )
                                    (c)
                                @endif
                                @if( $squad2['lineup']['wicketkeeper'] )
                                    (wk)
                                @endif,
                            @endif
                        @endforeach
                    </td>
                </tr>
            </table>
        </div>
    </div>
@endif

@endsection