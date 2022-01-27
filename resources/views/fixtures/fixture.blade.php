@extends('layouts/app')

@section('content')

@if( $fixture['success'] )
    <div class="heading"> 
        {{ $fixture['data']['localteam']['name'] }} vs {{ $fixture['data']['visitorteam']['name'] }}, {{ $fixture['data']['round'] }} 
        <span>- {{ $fixture['data']['venue']['name'] }}, {{ $fixture['data']['venue']['city'] }}, {{ date('M d, Y H:i A', strtotime($fixture['data']['starting_at'])) }} </span>
    </div>
    <hr />

    <div class="row main-div">
        <div class="fixture-scorecard">
            <div class="scorecard-label">SCORECARD</div>
            <div class="scorecard-result">{{ $fixture['data']['note'] }} <span>({{ $fixture['data']['tosswon']['name'] }} won the toss)</span></div>
            <div class="fixture-mom">
                Man of the match:
                <img src="{{ $helper->setImage($fixture['data']['manofmatch']['image_path']) }}">
                <div class="fixture-mom-name"> {{ $fixture['data']['manofmatch']['fullname'] }} </div>
            </div>
            <hr />
            <div class="fixture-innings-one"> 
                <div class="team-name"> {{ $fixture['data']['runs'][0]['team']['name'] }} Innings </div>
                <div class="team-score"> {{ $fixture['data']['runs'][0]['score'] }}-{{ $fixture['data']['runs'][0]['wickets'] }} ({{ $fixture['data']['runs'][0]['overs'] }} Ov) </div>
            </div>
            <table class="table" width="100%">
                <tr>
                    <th width="20%">Batter</th>
                    <th colspan="2"></th>
                    <th width="5%">R</th>
                    <th width="5%">B</th>
                    <th width="5%">4s</th>
                    <th width="5%">6s</th>
                    <th width="5%">SR</th>
                </tr>
                @foreach( $fixture['data']['batting'] as $score )
                    @if( strtolower($score['scoreboard']) == 's1' )
                        <tr>
                            <td width="20%">{{ $score['batsman']['fullname'] }}</td>
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
                    @endif
                @endforeach
            </table>
            <table class="table" width="100%">
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
            <table class="table" width="100%">
                <tr>
                    <th width="20%">Batter</th>
                    <th colspan="2"></th>
                    <th width="5%">R</th>
                    <th width="5%">B</th>
                    <th width="5%">4s</th>
                    <th width="5%">6s</th>
                    <th width="5%">SR</th>
                </tr>
                @foreach( $fixture['data']['batting'] as $score )
                    @if( strtolower($score['scoreboard']) == 's2' )
                        <tr>
                            <td width="20%">{{ $score['batsman']['fullname'] }}</td>
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
                    @endif
                @endforeach
            </table>
            <table class="table" width="100%">
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
        </div>
    </div>
@endif

@endsection