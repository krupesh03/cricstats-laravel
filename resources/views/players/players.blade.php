@extends('layouts/app')

@section('content')

<div class="heading"> Players </div>
<hr />

<div class="row main-div">
    <div class="player-filter-list">
        <form method="GET" class="search-form">
            <div class="form-group row">
                <label class="col-md-3 col-form-label">Team : </label>
                <div class="col-md-3">
                    <select name="team" class="form-control">
                        <option value="">Search by team</option>
                        @foreach( $dropdowns['countries'] as $id => $name )
                            <option value="{{ $id }}" {{ $id == request()->query('team') ? 'selected' : '' }}> {{ ucfirst($name) }} </option>
                        @endforeach
                    </select> 
                </div>
                <label class="col-md-3 col-form-label">Role : </label>
                <div class="col-md-3">
                    <select name="position" class="form-control">
                        <option value="">Search by role</option>
                        @foreach( $dropdowns['positions'] as $id => $name )
                            <option value="{{ $id }}" {{ $id == request()->query('position') ? 'selected' : '' }}> {{ ucfirst($name) }} </option>
                        @endforeach
                    </select> 
                </div>
                <label class="col-md-3 col-form-label">Lastname : </label>
                <div class="col-md-3">
                    <input type="text" class="form-control" placeholder="Search by lastname" name="lastname" autocomplete="off" value="{{ request()->query('lastname') }}">
                </div>
            </div>
            <div class="form-group row">
                <div class="col-md-12 search-button">
                    <input type="submit" name="find_players" value="search">
                    <input type="reset" name="reset_search" value="reset">
                </div>
            </div>
        </form>
        <hr />

        <div class="row all-players">
            @foreach( $playerList as $player )
                <div class="col-md-3 player-list">
                    <a href="{{ '/players/' . $player['id'] }}" data-pid="{{ $player['id'] }}">
                        <div class="player-data">
                            <div class="player-image">
                                <img src="{{ $helper->setImage($player['image_path']) }}">
                            </div>
                            <div class="player-name-team"> 
                                {{ $player['fullname'] }} <br />
                                <span class="position">({{ isset($player['position']['name']) ? $player['position']['name'] : '' }})</span> 
                                <span class="teams">{{ implode(', ', $player['teams']) }}</span>
                            </div>
                        </div>
                    </a>
                </div>
            @endforeach
            @if( empty($playerList) )
                @if( request()->query('find_players') == 'search' || request()->query('search_key') )
                    <div class="error-msg"> No Data Found </div>
                @endif
            @endif
        </div>
    </div>
</div>

@endsection