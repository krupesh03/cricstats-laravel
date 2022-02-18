@extends('layouts/app')

@section('content')

<div class="heading"> Venue Details </div>
<hr />

<div class="row main-div">
    <div class="venue-information">
        @if( $venue['success'] )
            <div class="row venueSing-info">
                <div class="col-md-3 venue-pic">
                    <img src="{{ $helper->setImage($venue['data']['image_path']) }}">
                </div>
                <div class="col-md-9 venue-other-details">
                    <div class="name"> 
                        Name: <span> {{ $venue['data']['name'] }}, {{ $venue['data']['city'] }} </span> 
                    </div>
                    <div class="country"> 
                        Nationality: <img src="{{ $helper->setImage($venue['data']['country']['image_path']) }}">
                        <span> {{ $venue['data']['country']['name'] }} </span>
                    </div>
                    <div class="capacity"> 
                        Capacity: <span> {{ $venue['data']['capacity'] ? $venue['data']['capacity'] : 'NA' }} </span>
                    </div>
                    <div class="floodlight"> 
                        FloodLight: <span> {{ $venue['data']['floodlight'] ? 'Yes' : 'No' }} </span>
                    </div>
                </div>
            </div>
            <hr />
            <div class="matches-scheduled"> 
                Matches scheduled at this venue for the year {{ date('Y') }}
            </div>
            <table class="table venue-matches-table" width="100%">
                <tr>
                    <th width="20%"> Date & Time </th> 
                    <th width="30%"> Series </th> 
                    <th width="30%"> Match </th> 
                    <th width="20%"> Result/Note </th> 
                </tr>
                @foreach( $venue['data']['fixtures'] as $fixture )
                    @if( !empty($fixture['starting_at']) && strtotime( date('Y', strtotime($fixture['starting_at'])) ) == strtotime(date('Y')) )
                        <tr>
                            <td> {{  date('M d, Y h:i A', strtotime($fixture['starting_at'])) }} </td>
                            <td> {{  isset($stagesArray[$fixture['stage_id']]) ? $stagesArray[$fixture['stage_id']] : '' }} </td>
                            <td>
                                <a href="/livescores/{{ $fixture['id'] }}/score">
                                    <div class="match-teams">
                                        <img src="{{ $helper->setImage( $fixture['localteam']['image_path'] ) }}"> 
                                        <span> {{ isset($fixture['localteam']['code']) ? $fixture['localteam']['code'] : '' }} </span> vs <img src="{{ $helper->setImage( $fixture['visitorteam']['image_path'] ) }}"> 
                                        <span> {{ isset($fixture['visitorteam']['code']) ? $fixture['visitorteam']['code'] : '' }} </span>,
                                        <span> {{ isset($fixture['round']) ? $fixture['round'] : '' }} </span>
                                    </div>
                                </a>
                            </td>
                            <td class="result-note"> 
                                @if( isset($fixture['status']) && $fixture['status'] == '1st Innings' )
                                    @if( isset($fixture['visitorteam']['id']) && $fixture['visitorteam']['id'] == $fixture['toss_won_team_id'] && isset($fixture['elected']) )
                                        <span class="ongoing-match"> {{ $fixture['visitorteam']['name'] . ' opt for ' . $fixture['elected'] }} </span>
                                    @elseif( isset($fixture['localteam']['id']) && $fixture['localteam']['id'] == $fixture['toss_won_team_id'] && isset($fixture['elected']) )
                                        <span class="ongoing-match"> {{ $fixture['localteam']['name'] . ' opt for ' . $fixture['elected'] }} </span>
                                    @endif
                                @elseif( !$fixture['winner_team_id'] ) 
                                    @if( isset($fixture['status']) && strtolower($fixture['status']) == 'innings break' )
                                        <span class="ongoing-match"> {{ $fixture['status'] }} </span>
                                    @elseif( isset($fixture['status']) && $fixture['status'] == 'NS' )
                                        <span class="not-started"> Not Started </span>
                                    @elseif( isset($fixture['note']) && !empty($fixture['note']) )
                                        <span class="ongoing-match"> {{ $fixture['note'] }} </span>
                                    @else
                                        {{ $fixture['status'] }}
                                    @endif
                                @else
                                    @if( isset($fixture['note']) && !empty($fixture['note']) )
                                        {{ $fixture['note'] }}
                                    @elseif ( isset($fixture['status']) && !empty($fixture['status']) )
                                        {{ $fixture['status'] }}
                                    @endif
                                @endif
                            </td>
                        </tr>
                    @endif
                @endforeach
            </table>
        @else
            <div class="error-msg"> {{ $venue['data'] }} </div>
        @endif
    </div>
</div>

@endsection