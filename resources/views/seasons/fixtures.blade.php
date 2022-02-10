@extends('layouts/app')

@section('content')

<div class="heading"> {{ ucfirst($fixtureType) }} Fixtures 
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
                            {{ isset($fixture['round']) ? $fixture['round'] : '' }}, <span> {{ !empty($fixture['starting_at']) ? date('d M Y h:i A', strtotime($fixture['starting_at'])) : '' }}</span>, <span class="match-venue"> {{ isset($fixture['venue']['name']) ? $fixture['venue']['name'] : '' }}, {{ isset($fixture['venue']['city']) ? $fixture['venue']['city'] : '' }} </span>
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
                                Result: <span> {{ !empty($fixture['note']) ? $fixture['note'] : 'NA' }} </span>
                            </div>
                            <div class="man-ofthe-match">
                                Player of the match: <span> {{ isset($fixture['manofmatch']['fullname']) ? $fixture['manofmatch']['fullname'] : 'NA' }} </span> 
                                @if( !$fixture['draw_noresult'] && !empty($fixture['note']) )
                                    <div class="commentary-score">
                                        <a href="javascript:void(0)" class="live-score-url" data-pid="{{ $fixture['id'] }}" data-current-url="{{ url('') }}">Commentary</a> | 
                                        <a href="javascript:void(0)" class="fixture-list-url" data-pid="{{ $fixture['id'] }}" data-current-url="{{ url('') }}">Scorecard</a>
                                    </div>
                                @endif
                            </div>
                        @endif
                    </div>
                @endforeach
            </div>
        @else
            <div class="error-msg"> {{ $fixtures['data'] }} </div>
        @endif

        @include('pagination.pagination')

    </div>
</div>

@endsection