@extends('layouts/app')

@section('content')

<div class="heading"> Venues </div>
<hr />

<div class="row main-div">
    <div class="venue-filter-list">
        <form method="GET" class="search-form">
            <div class="form-group row">
                <label class="col-md-3 col-form-label">Country : </label>
                <div class="col-md-3">
                    <select name="country" class="form-control">
                        <option value="">Search by country</option>
                        @foreach( $dropdowns['countries'] as $id => $name )
                            <option value="{{ $id }}" {{ $id == request()->query('country') ? 'selected' : '' }}> {{ ucfirst($name) }} </option>
                        @endforeach
                    </select> 
                </div>
                <label class="col-md-3 col-form-label">City : </label>
                <div class="col-md-3">
                    <input type="text" class="form-control" placeholder="Search by cityname" name="city" autocomplete="off" value="{{ request()->query('city') }}">
                </div>
            </div>
            <div class="form-group row">
                <div class="col-md-12 search-button">
                    <input type="submit" name="find_venues" value="search">
                    <input type="reset" name="reset_search" value="reset">
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
            @if( empty($applicableVenues) )
                @if( request()->query('find_venues') == 'search' )
                    <div class="error-msg"> No Data Found </div>
                @endif
            @endif
        </div>
    </div>
</div>

@endsection