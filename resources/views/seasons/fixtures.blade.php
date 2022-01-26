@extends('layouts/app')

@section('content')

<div class="heading"> {{ strtoupper($fixtureType) }} FIXTURES 
    @if( $season['success'] && !empty($season['data']['name'] ) )
        <span> ({{ $season['data']['name'] }}) </span>
    @endif
</div>
<hr />

<div class="row main-div">
    <div class="team-fixtures">
        @if( $fixtures['success'] )
            @if( $teams['success'] && !empty($teams['data']['name'] ) )
                <div class="fixtures-team-name">
                    <img src="{{ $helper->setImage($teams['data']['image_path']) }}">
                    <div class="fixtures-team-name"> {{ $teams['data']['name'] }} </div>
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
                            {{ $fixture['round'] }}, <span> {{ date('d-m-Y H:i:s A', strtotime($fixture['starting_at'])) }}</span>, <span> {{ $fixture['venue']['name'] }} </span>
                        </div>
                        <div class="opponent-team">
                            @if( isset($fixture['visitorteam']) )
                                Vs <img src="{{ $helper->setImage( $fixture['visitorteam']['image_path']) }}"> 
                                <span> {{ isset($fixture['visitorteam']['name']) ? $fixture['visitorteam']['name'] : '' }} </span>
                            @elseif( isset($fixture['localteam']) )
                                Vs <img src="{{ $helper->setImage( $fixture['localteam']['image_path']) }}"> 
                                <span> {{ isset($fixture['localteam']['name']) ? $fixture['localteam']['name'] : '' }} </span>
                            @endif
                        </div>
                        <div class="match-result">
                            Result: <span> {{ isset($fixture['note']) ? $fixture['note'] : '' }} </span>
                        </div>
                        <div class="man-ofthe-match">
                            Man of the match: <span> {{ isset($fixture['manofmatch']['fullname']) ? $fixture['manofmatch']['fullname'] : 'NA' }} </span>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
</div>

@endsection