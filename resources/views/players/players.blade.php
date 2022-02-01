@extends('layouts/app')

@section('content')

<div class="heading"> PLAYERS LIST </div>
<hr />

<div class="row main-div">
    <div class="player-filter-list">
        <form method="GET" class="search-form">
            <div class="form-group row">
                <label class="col-md-1 col-form-label">Team : </label>
                <div class="col-md-2">
                    <select name="team" class="form-control">
                        <option value="">--Select--</option>
                        @foreach( $dropdowns['countries'] as $id => $name )
                            <option value="{{ $id }}" {{ (isset($_GET['team']) && $id == $_GET['team']) ? 'selected' : '' }}> {{ ucfirst($name) }} </option>
                        @endforeach
                    </select> 
                </div>
                <label class="col-md-1 col-form-label">Role : </label>
                <div class="col-md-2">
                    <select name="position" class="form-control">
                        <option value="">--Select--</option>
                        @foreach( $dropdowns['positions'] as $id => $name )
                            <option value="{{ $id }}" {{ (isset($_GET['position']) && $id == $_GET['position']) ? 'selected' : '' }}> {{ ucfirst($name) }} </option>
                        @endforeach
                    </select> 
                </div>
            </div>
            <div class="form-group row">
                <div class="col-md-12 search-button">
                    <input type="submit" name="find_players" value="search">
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
                                {{ $player['fullname'] }} 
                                <span class="position">({{ isset($player['position']['name']) ? $player['position']['name'] : '' }})</span> 
                                <span class="teams">{{ implode(', ', $player['teams']) }}</span>
                            </div>
                        </div>
                    </a>
                </div>
            @endforeach
            @if( empty($playerList) )
                @if( (isset($_GET['find_players']) && $_GET['find_players'] == 'search' && (isset($_GET['team']) || isset($_GET['position']) ) ) || (isset($_GET['search_key'])))
                    <div class="error-msg"> No Data Found </div>
                @endif
            @endif
        </div>
    </div>
</div>

@endsection