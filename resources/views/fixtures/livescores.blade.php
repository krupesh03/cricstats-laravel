@extends('layouts/app')

@section('content')

<div class="heading"> LIVE CRICKET SCORE </div>
<hr />

<div class="row main-div">
    <div class="team-allLiveScores">
        @foreach( $allLiveScores as $score )
            <div class="col-md-12 league-stage-name"> {{ $score['league_name']}}, {{ $score['stage_name'] }} {{ $score['season_name'] }} </div>
            <div class="row">
                @foreach( $score['fixtures'] as $matches )
                    <div class="col-md-6 fixture-listing">
                        <div class="match-number">
                            {{ isset($matches['facts']['round']) ? $matches['facts']['round'] : '' }}, <span> {{ date('M d . h:i A', strtotime($matches['facts']['starting_at'])) }}</span> <span class="match-venue"> at {{ isset($matches['venue']['name']) ? $matches['venue']['name'] : '' }}, {{ isset($matches['venue']['city']) ? $matches['venue']['city'] : '' }}  </span>
                        </div>
                        <div class="match-teams">
                            <div class="localteam-score">
                                <div class="team">
                                    <img src="{{ $helper->setImage( $matches['localteam']['image_path'] ) }}"> 
                                    <span> {{ isset($matches['localteam']['name']) ? $matches['localteam']['name'] : '' }} </span>
                                </div>
                                <div class="score">123-7 (43.5 Ov)</div>
                            </div> 
                            <div class="visitorteam-score">
                                <div class="team">
                                    <img src="{{ $helper->setImage( $matches['visitorteam']['image_path'] ) }}"> 
                                    <span> {{ isset($matches['visitorteam']['name']) ? $matches['visitorteam']['name'] : '' }} </span>
                                </div>
                                <div class="score">123-7 (43.5 Ov)</div>
                            </div> 
                        </div>
                        <div class="match-result">
                            <span> {{ isset($matches['facts']['note']) ? $matches['facts']['note'] : '' }} </span>
                        </div>
                        <div class="livescore-details">
                            <a href="javascript:void(0)" data-current-url="{{ url('') }}">Live Score</a> | <a href="javascript:void(0)" class="fixture-list-url" data-pid="{{ $matches['facts']['id'] }}"  data-current-url="{{ url('') }}">Scorecard</a>
                        </div>
                    </div>
                @endforeach
            </div>
        @endforeach
    </div>
</div>

@endsection