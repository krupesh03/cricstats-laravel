@extends('layouts/app')

@section('content')

<div class="heading"> Schedule/Results </div>
<hr />

<div class="row main-div">
    <div class="team-allfixtures">
        @if( count($recentMatches) )
            <div class="col-md-12 league-stage-name"> Recent Matches </div>
            <div class="row">
                @foreach( $recentMatches as $recent )
                    <div class="col-md-6 fixture-listing">
                        <div class="match-number">
                            {{ isset($recent['round']) ? $recent['round'] : '' }}, <span> {{ date('d M Y h:i A', strtotime($recent['starting_at'])) }}</span>, <span class="match-venue"> {{ isset($recent['venue']['name']) ? $recent['venue']['name'] : '' }}, {{ isset($recent['venue']['city']) ? $recent['venue']['city'] : '' }}</span>,
                            <span>{{ $recent['league_id'] == 3 ? $recent['stage']['name'] : $recent['league']['name'] }}, {{ $recent['season']['name'] }}</span>
                        </div>
                        <div class="opponent-team">
                            <img src="{{ $helper->setImage( $recent['localteam']['image_path'] ) }}"> 
                            <span> {{ isset($recent['localteam']['name']) ? $recent['localteam']['name'] : '' }} </span> Vs <img src="{{ $helper->setImage( $recent['visitorteam']['image_path'] ) }}"> 
                            <span> {{ isset($recent['visitorteam']['name']) ? $recent['visitorteam']['name'] : '' }} </span>
                        </div>
                        @if( strtotime($recent['starting_at']) > time() )
                            <div class="match-result">
                                Note: <span> Upcoming match </span>
                            </div>
                        @else
                            <div class="match-result">
                                Result: <span> {{ !empty($recent['note']) ? $recent['note'] : $recent['status'] }} </span>
                            </div>
                            <div class="man-ofthe-match">
                                Player of the match: <span> {{ isset($recent['manofmatch']['fullname']) ? $recent['manofmatch']['fullname'] : 'NA' }} </span> 
                                @if( !$recent['draw_noresult'] && !empty($recent['note']) )
                                    <div class="commentary-score">
                                        <a href="javascript:void(0)" class="live-score-url" data-pid="{{ $recent['id'] }}" data-current-url="{{ url('') }}">Commentary</a> |
                                        <a href="javascript:void(0)" class="fixture-list-url" data-pid="{{ $recent['id'] }}" data-current-url="{{ url('') }}">Scorecard</a>
                                    </div>
                                @endif
                            </div>
                        @endif
                    </div>
                @endforeach
            </div>
        @endif

        @foreach( $allFixtures as $stageid => $fixture )
            <div class="col-md-12 league-stage-name"> {{ $fixture['league_name']}}, {{ $fixture['stage_name'] }} {{ $fixture['season_name'] }} </div>
            <div class="row">
                @foreach( $fixture['fixtures'] as $matches )
                    <div class="col-md-6 fixture-listing">
                        <div class="match-number">
                            {{ isset($matches['facts']['round']) ? $matches['facts']['round'] : '' }}, <span> {{ date('d M Y h:i A', strtotime($matches['facts']['starting_at'])) }}</span>, <span class="match-venue"> {{ isset($matches['venue']['name']) ? $matches['venue']['name'] : '' }}, {{ isset($matches['venue']['city']) ? $matches['venue']['city'] : '' }}  </span>
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
                                Result: <span> {{ !empty($matches['facts']['note']) ? $matches['facts']['note'] : $matches['facts']['status'] }} </span>
                            </div>
                            <div class="man-ofthe-match">
                                Player of the match: <span> {{ isset($matches['manofmatch']['fullname']) ? $matches['manofmatch']['fullname'] : 'NA' }} </span> 
                                @if( !$matches['facts']['draw_noresult'] && !empty($matches['facts']['note']) )
                                    <div class="commentary-score">
                                        <a href="javascript:void(0)" class="live-score-url" data-pid="{{ $matches['facts']['id'] }}" data-current-url="{{ url('') }}">Commentary</a> |
                                        <a href="javascript:void(0)" class="fixture-list-url" data-pid="{{ $matches['facts']['id'] }}" data-current-url="{{ url('') }}">Scorecard</a>
                                    </div>
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