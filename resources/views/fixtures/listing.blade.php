@extends('layouts/app')

@section('content')

<div class="heading"> RECENT SCHEDULE/RESULTS </div>
<hr />

<div class="row main-div">
    <div class="team-allfixtures">
        @foreach( $allFixtures as $fixture )
            <div class="col-md-12 league-stage-name"> {{ $fixture['league_name']}}, {{ $fixture['stage_name'] }} {{ $fixture['season_name'] }} </div>
            <div class="row">
                @foreach( $fixture['fixtures'] as $matches )
                    <div class="col-md-6 fixture-listing">
                        <div class="match-number">
                            {{ isset($matches['facts']['round']) ? $matches['facts']['round'] : '' }}, <span> {{ date('d-m-Y h:i A', strtotime($matches['facts']['starting_at'])) }}</span>, <span class="match-venue"> {{ isset($matches['venue']['name']) ? $matches['venue']['name'] : '' }}, {{ isset($matches['venue']['city']) ? $matches['venue']['city'] : '' }}  </span>
                        </div>
                        <div class="opponent-team">
                            <img src="{{ $helper->setImage( $matches['localteam']['image_path'] ) }}"> 
                            <span> {{ isset($matches['localteam']['name']) ? $matches['localteam']['name'] : '' }} </span> Vs <img src="{{ $helper->setImage( $matches['visitorteam']['image_path'] ) }}"> 
                            <span> {{ isset($matches['visitorteam']['name']) ? $matches['visitorteam']['name'] : '' }} </span>
                        </div>
                        @if( strtotime($matches['facts']['starting_at']) > time() )
                            <div class="match-result">
                                Note: <span> Upcoming match </span>
                            </div>
                        @else
                            <div class="match-result">
                                Result: <span> {{ isset($matches['facts']['note']) ? $matches['facts']['note'] : '' }} </span>
                            </div>
                            <div class="man-ofthe-match">
                                Man of the match: <span> {{ isset($matches['manofmatch']['fullname']) ? $matches['manofmatch']['fullname'] : 'NA' }} </span> 
                                @if( !$matches['facts']['draw_noresult'] )
                                    (<a href="javascript:void(0)" class="fixture-list-url" data-pid="{{ $matches['facts']['id'] }}" data-current-url="{{ url('') }}">scoreboard</a>)
                                @endif
                            </div>
                        @endif
                    </div>
                @endforeach
            </div>
        @endforeach

        @include('pagination.pagination')

    </div>
</div>

@endsection