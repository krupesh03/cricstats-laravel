@extends('layouts/app')

@section('content')

<div class="heading"> 
    @if( $fixture['success'] && isset($fixture['data']['league']['name']) && isset($fixture['data']['name']) )
        {{ strtoupper($fixture['data']['league']['name']) }} FIXTURES
        <span> ({{ $fixture['data']['name'] }}) </span>
    @endif
</div>
<hr />

<div class="row main-div">
    <div class="team-fixtures">
        @if( $fixture['success'] )
            <div class="fixtures-team-name">
                <img src="{{ $helper->setImage($fixture['data']['league']['image_path']) }}">
                <div class="fixtures-team-name"> {{ $fixture['data']['league']['name'] }} ({{ $fixture['data']['league']['code'] }}) </div>
            </div>
            <hr />
            <div class="row">
                @foreach( $fixture['data']['fixtures'] as $fixture )
                    <div class="col-md-6 fixture-list">
                        <div class="match-number">
                            {{ $fixture['round'] }}, {{ $stageArray[$fixture['stage_id']] }}, <span> {{ date('d M Y h:i A', strtotime($fixture['starting_at'])) }}</span>
                        </div>
                        <div class="opponent-team">
                            <img src="{{ $helper->setImage( $fixture['localteam']['image_path'] ) }}"> 
                            <span> {{ isset($fixture['localteam']['name']) ? $fixture['localteam']['name'] : '' }} </span> Vs <img src="{{ $helper->setImage( $fixture['visitorteam']['image_path'] ) }}"> 
                            <span> {{ isset($fixture['visitorteam']['name']) ? $fixture['visitorteam']['name'] : '' }} </span>
                        </div>
                        @if( strtotime($fixture['starting_at']) > time() )
                            <div class="match-result">
                                Note: <span> Upcoming match </span>
                            </div>
                        @else
                            <div class="match-result">
                                Result: <span> {{ isset($fixture['note']) ? $fixture['note'] : '' }} </span>
                            </div>
                            @if( !$fixture['draw_noresult'] )
                                <div class="man-ofthe-match">
                                    <a href="javascript:void(0)" class="fixture-list-url" data-pid="{{ $fixture['id'] }}" data-current-url="{{ url('') }}">See full match details here >>></a>
                                </div>
                            @endif
                        @endif
                    </div>
                @endforeach
            </div>
        @else
            <div class="error-msg"> {{ $fixture['data'] }} </div>
        @endif
    </div>
</div>

@endsection