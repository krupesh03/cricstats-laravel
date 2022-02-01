@extends('layouts/app')

@section('content')

<div class="heading"> PLAYER </div>
<hr />

<div class="row main-div">
    <div class="player-information">
        @if( $player['success'] )
            <div class="personal-info">
                <div class="profile-pic">
                    <img src="{{ $helper->setImage($player['data']['image_path']) }}">
                    <div class="name"> {{ $player['data']['fullname'] }} </div>
                </div>
                <div class="personal-other-details">
                    <div class="country"> 
                        <img src="{{ $helper->setImage($player['data']['country']['image_path']) }}">
                        <span> {{ $player['data']['country']['name'] }} </span>
                    </div>
                    <div class="dateofbirth"> 
                        Date Of Birth: <span> {{ $player['data']['dateofbirth'] ? date('d M Y', strtotime($player['data']['dateofbirth'])) : '' }} </span>
                    </div>
                    <div class="position"> 
                        Position: <span> {{ $player['data']['position']['name'] }} </span>
                    </div>
                    <div class="batting-style"> 
                        Batting Style: <span> {{ ucfirst( str_replace( '-', ' ', $player['data']['battingstyle'] )) }} </span>
                    </div>
                    <div class="bowling-style"> 
                        Bowling Style: <span> {{ ucfirst( str_replace( '-', ' ', $player['data']['bowlingstyle'] )) }} </span>
                    </div>
                </div>
            </div>
            <hr />
            @foreach( $player['data']['career'] as $career ) 
                @if( !empty($career['season']) )
                    <div class="player-career"> 
                        {{ $career['type'] }} - &nbsp;<span> Season: {{ $career['season']['name'] }} </span> 
                    </div>
                    <table class="table player-batting-career" width="100%">
                        <tr>
                            <th colspan="10">Batting Career</th>
                        </tr>
                        <tr>
                            <th width="10%">Matches</th>
                            <th width="10%">Innings</th>
                            <th width="10%">Fours</th>
                            <th width="10%">Sixes</th>
                            <th width="10%">Runs</th>
                            <th width="10%">Average</th>
                            <th width="10%">Fiftees</th>
                            <th width="10%">Hundreds</th>
                            <th width="10%">Best</th>
                            <th width="10%">SR</th>
                        </tr>
                        @if( !empty($career['batting']) )
                        <tr>
                            <td>{{ $career['batting']['matches'] }}</td>
                            <td>{{ $career['batting']['innings'] }}</td>
                            <td>{{ $career['batting']['four_x'] }}</td>
                            <td>{{ $career['batting']['six_x'] }}</td>
                            <td>{{ $career['batting']['runs_scored'] }}</td>
                            <td>{{ $career['batting']['average'] }}</td>
                            <td>{{ $career['batting']['fifties'] }}</td>
                            <td>{{ $career['batting']['hundreds'] }}</td>
                            <td>{{ $career['batting']['highest_inning_score'] }}</td>
                            <td>{{ $career['batting']['strike_rate'] }}</td>
                        </tr>
                        @endif
                    </table>
                    <table class="table player-bowling-career" width="100%">
                        <tr>
                            <th colspan="8">Bowling Career</th>
                        </tr>
                        <tr>
                            <th width="12%">Matches</th>
                            <th width="12%">Innings</th>
                            <th width="14%">Eco. Rate</th>
                            <th width="12%">Wickets</th>
                            <th width="12%">Average</th>
                            <th width="12%">4 Wkts</th>
                            <th width="12%">5 Wkts</th>
                            <th width="14%">SR</th>
                        </tr>
                        @if( !empty($career['bowling']) )
                        <tr>
                            <td>{{ $career['bowling']['matches'] }}</td>
                            <td>{{ $career['bowling']['innings'] }}</td>
                            <td>{{ $career['bowling']['econ_rate'] }}</td>
                            <td>{{ $career['bowling']['wickets'] }}</td>
                            <td>{{ $career['bowling']['average'] }}</td>
                            <td>{{ $career['bowling']['four_wickets'] }}</td>
                            <td>{{ $career['bowling']['five_wickets'] }}</td>
                            <td>{{ $career['bowling']['strike_rate'] }}</td>
                        </tr>
                        @endif
                    </table>
                @endif
            @endforeach
        @else
            <div class="error-msg"> {{ $player['data'] }} </div>
        @endif
    </div>
</div>

@endsection