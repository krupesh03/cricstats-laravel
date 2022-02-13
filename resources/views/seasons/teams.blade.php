@extends('layouts/app')

@section('content')

<div class="heading"> Teams </div>
<hr />

<div class="row main-div">
    <div class="season-teams">
        @if( $seasonTeams['success'] )
            @if( !empty($seasonTeams['data']['name'] ) )
                <div class="season-name"> Season: <span>{{ $seasonTeams['data']['name'] }}</span> </div>
            @endif
            @if( !empty( $seasonTeams['data']['league'] ) ) 
                <a href="javascript:void(0)" class="league-info-url-fixture" data-pid="{{ $seasonTeams['data']['league']['id'] }}" data-id="{{ $seasonTeams['data']['id'] }}">
                    <div class="league-logo">
                        <img src="{{ $helper->setImage($seasonTeams['data']['league']['image_path']) }}">
                        <div class="league-name"> {{ $seasonTeams['data']['league']['name'] }} 
                            @if( $seasonTeams['data']['league']['code'] )
                                ({{ $seasonTeams['data']['league']['code'] }})
                            @endif
                        </div>
                    </div>
                </a>
            @endif
            <hr />
            @foreach( $seasonTeams['data']['stages'] as $stage )
                @if( $stage['standings'] )
                    <a href="{{ url()->current() }}/standings" class="league-standings"> Points Table >>> </a>
                @endif
            @endforeach
            <div class="row">
                @foreach( $seasonTeams['data']['teams'] as $team )
                    <div class="col-md-3 season-team-info">
                        <a href="javascript:void(0)" class="teams-list" data-pid="{{ $team['id'] }}" data-id="{{ $seasonTeams['data']['id'] }}" data-current-url="{{ url('') }}">
                            <div class="team-logo">
                                <img src="{{ $helper->setImage($team['image_path']) }}">
                            </div>
                            <div class="season-team-name"> {{ $team['name'] }} </div>
                        </a>
                    </div>
                @endforeach
            </div>
        @else
            <div class="error-msg"> {{ $seasonTeams['data'] }} </div>
        @endif
    </div>
</div>

@endsection