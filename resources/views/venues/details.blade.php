@extends('layouts/app')

@section('content')

<div class="heading"> Venue Details </div>
<hr />

<div class="row main-div">
    <div class="venue-information">
        @if( $venue['success'] )
            <div class="venueSing-info">
                <div class="venue-pic">
                    <img src="{{ $helper->setImage($venue['data']['image_path']) }}">
                    <div class="name"> {{ $venue['data']['name'] }}, {{ $venue['data']['city'] }} </div>
                </div>
                <div class="venue-other-details">
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
                Upcoming matches scheduled at this venue 
            </div>
            @foreach( $venue['data']['fixtures'] as $fixture )
                @if( !empty($fixture['starting_at']) && strtotime( date('Ymd', strtotime($fixture['starting_at'])) ) >= strtotime(date('Ymd')) )
                    <table class="table venue-matches-table" width="100%">
                        <tr>
                            <td> {{  date('M d, Y h:i A', strtotime($fixture['starting_at'])) }} </td>
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
                        </tr>
                    </table>
                @endif
            @endforeach
        @else
            <div class="error-msg"> {{ $venue['data'] }} </div>
        @endif
    </div>
</div>

@endsection