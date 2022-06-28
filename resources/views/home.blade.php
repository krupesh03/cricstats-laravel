@extends('layouts/app')

@section('content')

<div class="heading"> Featured Matches </div>
<hr />

<div class="row main-div">
    @if( count($featuredFixtures) )
        <div class="row featured-matches">
            @foreach( $featuredFixtures as $key => $match )
                <div class="col-md-4 fixture-listing">
                    <div class="league-stage-name">
                        {{ $match['league']['id'] == 3 ? $match['stage']['name'] : $match['league']['name'] }}, {{ $match['season']['name'] }} 
                    </div>
                    <div class="match-teams">
                        <div class="localteam-score">
                            <div class="team">
                                <img src="{{ $helper->setImage( $match['localteam']['image_path'] ) }}"> 
                                <span> {{ isset($match['localteam']['code']) ? $match['localteam']['code'] : '' }} </span>
                            </div>
                            <div class="score">
                                {{ isset($match['matchRuns'][$match['localteam']['id']]) ? $match['matchRuns'][$match['localteam']['id']] : '' }}
                            </div>
                        </div> 
                        <div class="visitorteam-score">
                            <div class="team">
                                <img src="{{ $helper->setImage( $match['visitorteam']['image_path'] ) }}"> 
                                <span> {{ isset($match['visitorteam']['code']) ? $match['visitorteam']['code'] : '' }} </span>
                            </div>
                            <div class="score">
                                {{ isset($match['matchRuns'][$match['visitorteam']['id']]) ? $match['matchRuns'][$match['visitorteam']['id']] : '' }}
                            </div>
                        </div> 
                    </div>
                    @if( isset($match['matchNote']) && $match['matchNote'] == 'NS' )
                        <div class="match-time">
                            <span> {{ !empty($match['starting_at']) ? date('l, d M, h:i A ', strtotime($match['starting_at'])) : '' }} </span>
                        </div>
                    @else
                        <div class="match-result">
                            <span> {{ $match['matchNote'] }} </span>
                        </div>
                        <div class="featured-match-details">
                            <a href="javascript:void(0)" class="live-score-url" data-pid="{{ $match['id'] }}" data-current-url="{{ url('') }}">{{ $match['liveMatch'] ? 'Live Score' : 'Commentary' }}</a> | 
                            <a href="javascript:void(0)" class="fixture-list-url" data-pid="{{ $match['id'] }}"  data-current-url="{{ url('') }}">Scorecard</a>
                        </div>
                    @endif
                </div>
            @endforeach
        </div>
        
        @include('pagination.pagination')

        <hr />
        <div class="heading"> Latest News </div>
        @include('news.news')
    @else
        <div class="error-msg"> There are no featured matches at the moment. Please check back later! </div>
    @endif
</div>

@endsection