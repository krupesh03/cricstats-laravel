@extends('layouts/app')

@section('content')

<div class="heading"> {{ strtoupper($fixtureType) }} FIXTURES 
    @if( $fixtures['success'] && isset($fixtures['data'][0]['season']['name']) )
        <span> ({{ $fixtures['data'][0]['season']['name'] }}) </span>
    @endif
</div>
<hr />

<div class="row main-div">
    <div class="team-fixtures">
        @if( $fixtures['success'] )
            @if( isset($fixtures['data'][0]['localteam']['name']) && $fixtureType == 'home' )
                <div class="fixtures-team-name">
                    <img src="{{ $helper->setImage($fixtures['data'][0]['localteam']['image_path']) }}">
                    <div class="fixtures-team-name"> {{ $fixtures['data'][0]['localteam']['name'] }} </div>
                </div>
            @endif
            @if( isset($fixtures['data'][0]['visitorteam']['name']) && $fixtureType == 'away' )
                <div class="fixtures-team-name">
                    <img src="{{ $helper->setImage($fixtures['data'][0]['visitorteam']['image_path']) }}">
                    <div class="fixtures-team-name"> {{ $fixtures['data'][0]['visitorteam']['name'] }} </div>
                </div>
            @endif
            <hr />
            @php 
                $newFtype = ($fixtureType == 'home' ? 'away' : 'home');
            @endphp
            <a href="{{ str_replace($fixtureType, $newFtype, url()->current()) }}" class="home-away-fixtures"> Switch to {{ ucfirst($newFtype) }} Fixtures </a>
            <div class="row">
                @foreach( $fixtures['data'] as $fixture )
                    <div class="col-md-6 fixture-list">
                        <div class="match-number">
                            {{ $fixture['round'] }}, <span> {{ date('d-m-Y H:i:s A', strtotime($fixture['starting_at'])) }}</span>, <span class="match-venue"> {{ $fixture['venue']['name'] }}, {{ $fixture['venue']['city'] }} </span>
                        </div>
                        <div class="opponent-team">
                            @if( isset($fixture['visitorteam']['name']) && $fixtureType == 'home' )
                                Vs <img src="{{ $helper->setImage( $fixture['visitorteam']['image_path']) }}"> 
                                <span> {{ isset($fixture['visitorteam']['name']) ? $fixture['visitorteam']['name'] : '' }} </span>
                            @elseif( isset($fixture['localteam']['name']) && $fixtureType == 'away' )
                                Vs <img src="{{ $helper->setImage( $fixture['localteam']['image_path']) }}"> 
                                <span> {{ isset($fixture['localteam']['name']) ? $fixture['localteam']['name'] : '' }} </span>
                            @endif
                        </div>
                        @if( strtotime($fixture['starting_at']) > time() )
                            <div class="match-result">
                                Note: <span> Upcoming match </span>
                            </div>
                        @else
                            <div class="match-result">
                                Result: <span> {{ isset($fixture['note']) ? $fixture['note'] : '' }} </span>
                            </div>
                            <div class="man-ofthe-match">
                                Man of the match: <span> {{ isset($fixture['manofmatch']['fullname']) ? $fixture['manofmatch']['fullname'] : 'NA' }} </span> 
                                @if( isset($fixture['manofmatch']['fullname']) )
                                    (<a href="javascript:void(0)" class="fixture-list-url" data-pid="{{ $fixture['id'] }}" data-current-url="{{ url('') }}">scoreboard</a>)
                                @endif
                            </div>
                        @endif
                    </div>
                @endforeach
            </div>
        @endif
    </div>
</div>

@endsection