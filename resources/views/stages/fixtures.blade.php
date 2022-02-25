@extends('layouts/app')

@section('content')

@if( count($stageFixtures) )
    <div class="heading"> {{ $stageFixtures['stageName'] }} </div>
    <div class="subheading"> {{ $stageFixtures['stageDates'] }} </div>
    <hr />
    <div class="row main-div">
        <div class="row stage-fixtures">
            @if( $stageFixtures['standings'] )
                <a href="/seasons/{{ $stageFixtures['league_id'] }}/teams/{{ $stageFixtures['season_id'] }}/standings" class="league-standings"> Points Table >>> </a>
            @endif
            @foreach( $stageFixtures['fixtures'] as $matches )
                <div class="col-md-6 fixture-listing">
                    <div class="match-number">
                        {{ isset($matches['round']) ? $matches['round'] : '' }}, <span> {{ !empty($matches['starting_at']) ? date('d M Y h:i A', strtotime($matches['starting_at'])) : '' }} </span>
                    </div>
                    <div class="opponent-team">
                        <img src="{{ $helper->setImage( $matches['localteam']['image_path'] ) }}"> 
                        <span> {{ isset($matches['localteam']['name']) ? $matches['localteam']['name'] : '' }} </span> Vs <img src="{{ $helper->setImage( $matches['visitorteam']['image_path'] ) }}"> 
                        <span> {{ isset($matches['visitorteam']['name']) ? $matches['visitorteam']['name'] : '' }} </span>
                    </div>
                    @if( strtotime($matches['starting_at']) > time() )
                        <div class="match-result-ns">
                            Note: <span> Upcoming match </span>
                        </div>
                    @else
                        <div class="match-result">
                            Result: <span> {{ !empty($matches['note']) ? $matches['note'] : $matches['status'] }} </span>
                        </div>
                        <div class="man-ofthe-match"> 
                            @if( !$matches['draw_noresult'] && !empty($matches['note']) )
                                <div class="commentary-score">
                                    <a href="javascript:void(0)" class="live-score-url" data-pid="{{ $matches['id'] }}" data-current-url="{{ url('') }}">Commentary</a> |
                                    <a href="javascript:void(0)" class="fixture-list-url" data-pid="{{ $matches['id'] }}" data-current-url="{{ url('') }}">Scorecard</a>
                                </div>
                            @endif
                        </div>
                    @endif
                </div>
            @endforeach
        </div>
    </div>
@else
    <div class="error-msg"> No Data Found! </div>
@endif

@endsection