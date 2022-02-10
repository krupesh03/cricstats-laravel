@extends('layouts/app')

@section('content')

<div class="heading"> Teams <span> - Season {{ $latestSeason['name'] }} </span> <a href="{{ url('/seasons') }}/{{ $latestSeason['league_id'] }}" class="change-season">Change season</a></div>
<hr />

<div class="row main-div">
    @foreach( $latestSeason['teams'] as $team )
        <div class="col-md-3 team-info">
            <a href="javascript:void(0)" class="teams-list" data-pid="{{ $team['id'] }}" data-id="{{ $latestSeason['id'] }}" data-current-url="{{ url('') }}">
                <div class="team-logo">
                    <img src="{{ $helper->setImage($team['image_path']) }}">
                </div>
                <div class="team-name"> {{ $team['name'] }} </div>
            </a>
        </div>
    @endforeach
</div>

@endsection