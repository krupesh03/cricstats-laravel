@extends('layouts/app')

@section('content')

<div class="heading"> 
    {{ $livedetails['localteam']['name'] }} vs {{ $livedetails['visitorteam']['name'] }}, {{ $livedetails['details']['round'] }} - Live Cricket Score, Commentary
</div>
<div class="subheading">
    <span>Series : {{ $livedetails['stage']['name'] }}, {{ $livedetails['season']['name'] }} </span>
    <span>Venue :  {{ $livedetails['venue']['name'] }}, {{ $livedetails['venue']['city'] }}</span>
    <span>Date & Time : {{ $livedetails['details']['starting_at'] ? date('M d, h:i A', strtotime($livedetails['details']['starting_at'])) : '' }} </span>
</div>
<hr />

<div class="row main-div">
    <div class="live-scorecard">
        @foreach( $livedetails['runs']['data'] as $run )
            <div class="{{ $run['inning'] == $livedetails['runs']['current_innings'] ? 'innings-progress-score' : 'innings-completed-score' }}">
                <img src="{{ $helper->setImage($run['team']['image_path']) }}">
                {{ $run['team']['code'] }} {{ $run['score'] }}-{{ $run['wickets'] }} ({{ $run['overs'] }})
                <span>
                    @if( $run['inning'] == $livedetails['runs']['current_innings'] )
                        CRR: {{ $run['crr'] }}
                    @endif
                    @if( $run['inning'] == 2 )
                         REQ: {{ $livedetails['runs']['rr'] }}
                    @endif
                </span>
            </div>
            <div class="match-note">
                @if( $livedetails['details']['status'] == "Finished" && $run['inning'] == 2 )
                    {{ $livedetails['details']['note'] }}
                @else
                    {{ $run['inning'] == 2 ? $run['team']['name'] . ' need ' . $livedetails['runs']['required_total'] .' runs' : '' }}
                @endif
            </div>
        @endforeach
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
                        <tr>
                            <td> 
                                <a href="{{ '/players/' . $value['id'] }}"> {{ $value['fullname'] }} </a> 
                                @if( $value['on_strike'] ) <span>*</span> @endif 
                            </td>
                            <td> {{ $value['scores']['score'] }} </td>
                            <td> {{ $value['scores']['ball'] }} </td>
                            <td> {{ $value['scores']['four_x'] }} </td>
                            <td> {{ $value['scores']['six_x'] }} </td>
                            <td> {{ $value['scores']['rate'] }} </td>
                        </tr>
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
                        <tr>
                            <td> 
                                <a href="{{ '/players/' . $value['id'] }}"> {{ $value['fullname'] }} </a> 
                                @if( $value['on_strike'] ) <span>*</span> @endif 
                            </td>
                            <td> {{ $value['figures']['overs'] }} </td>
                            <td> {{ $value['figures']['medians'] }} </td>
                            <td> {{ $value['figures']['runs'] }} </td>
                            <td> {{ $value['figures']['wickets'] }} </td>
                            <td> {{ $value['figures']['rate'] }} </td>
                        </tr>
                    @endforeach
                </table>
            </div>
            <div class="col-md-4">
                <table class="table key-stats" width="100%">
                    <tr>
                        <th>Key Stats</th>
                    </tr>
                    @if( $keyStats['partnership'] )
                        <tr>
                            <td>
                                Partnership: {{ $keyStats['partnership'] }}
                            </td>
                        </tr>
                    @endif
                    @if( $keyStats['last_wkt'] )
                        <tr>
                            <td>
                                Last Wkt: {{ $keyStats['last_wkt'] }}
                            </td>
                        </tr>
                    @endif
                    @if( $keyStats['toss'] )
                        <tr>
                            <td>
                                Toss: {{ $keyStats['toss'] }}
                            </td>
                        </tr>
                    @endif
                </table>
            </div>
        </div>
        <hr />

        <div class="live-commentary">
            <table class="table" width="100%">
                @foreach( $liveCommentory as $id => $commentory )
                    <tr>
                        <td width="10%">
                            @if( $commentory['score']['is_wicket'] )
                                <div class="wickets"> W </div>
                            @else
                                @if( $commentory['score']['runs'] <= 3 || ($commentory['score']['runs'] != 4 && $commentory['score']['runs'] != 6))
                                    <div class="runnings"> {{ $commentory['score']['runs'] }} </div>
                                @elseif( $commentory['score']['runs'] == 4 )
                                    <div class="boundary"> {{ $commentory['score']['runs'] }} </div>
                                @elseif( $commentory['score']['runs'] == 6 )
                                    <div class="huge-one"> {{ $commentory['score']['runs'] }} </div>
                                @endif
                            @endif
                        </td>
                        <td width="5%"> {{ $commentory['ball'] }} </td>
                        <td width="85%"> 
                            {{ $commentory['bowler']['fullname'] }} to {{ $commentory['batsman']['fullname'] }}, 
                            @if( $commentory['score']['is_wicket'] || $commentory['score']['runs'] == 4 || $commentory['score']['runs'] == 6 )
                                <span> {{ $commentory['score']['name'] }} </span>
                            @else 
                                {{ $commentory['score']['name'] }}
                            @endif
                            @if( strpos($commentory['score']['name'], 'Catch') !== false && !empty($commentory['catchstump']) )
                                , caught by {{ $commentory['catchstump']['fullname'] }}.
                            @elseif( strpos($commentory['score']['name'], 'Run') !== false )
                                @if( $commentory['runoutby'] && $commentory['catchstump'] )
                                    , throw by {{ $commentory['runoutby']['fullname'] }} and {{ $commentory['catchstump']['fullname'] }} destroys the stumps.
                                @elseif( $commentory['runoutby'] )
                                    , direct hit by {{ $commentory['runoutby']['fullname'] }}
                                @endif
                            @endif
                        </td>
                    </tr>
                @endforeach
            </table>
        </div>
    </div>
</div>

@endsection