@extends('layouts/app')

@section('content')

<div class="heading"> Venues </div>
<hr />

<div class="row main-div">
    <div class="venue-filter-list">
        <form method="GET" class="search-form">
            <div class="form-group row">
                <label class="col-md-2 col-form-label">Country : </label>
                <div class="col-md-2">
                    <select name="country" class="form-control">
                        <option value="">--Select--</option>
                        @foreach( $dropdowns['countries'] as $id => $name )
                            <option value="{{ $id }}" {{ (isset($_GET['country']) && $id == $_GET['country']) ? 'selected' : '' }}> {{ ucfirst($name) }} </option>
                        @endforeach
                    </select> 
                </div>
                <label class="col-md-1 col-form-label">City : </label>
                <div class="col-md-2">
                    <input type="text" class="form-control" placeholder="Search by city name" name="city" autocomplete="off" value="{{ (isset($_GET['city'])) ? $_GET['city'] : '' }}">
                </div>
            </div>
            <div class="form-group row">
                <div class="col-md-12 search-button">
                    <input type="submit" name="find_venues" value="search">
                </div>
            </div>
        </form>
        <hr />

        <div class="row all-venues">
            @foreach( $applicableVenues as $venue )
                <div class="col-md-3 venue-info">
                    <a href="/venues/{{ $venue['id'] }}">
                        <img src="{{ $helper->setImage($venue['image_path']) }}">
                        <div class="venue-details">
                            <div class="country-data">
                                <img src="{{ $helper->setImage($venue['country']['image_path']) }}">
                                {{ $venue['country']['name'] }}
                            </div>
                            <span> {{ $venue['name'] }}, {{ $venue['city'] }} </span> 
                        </div>
                    </a>
                </div>
            @endforeach
        </div>
    </div>
</div>

@endsection