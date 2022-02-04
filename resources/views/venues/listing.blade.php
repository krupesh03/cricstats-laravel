@extends('layouts/app')

@section('content')

<div class="heading"> VENUES </div>
<hr />

<div class="row main-div">
    @foreach( $applicableVenues as $venue )
        <div class="col-md-3 venue-info">
            <img src="{{ $helper->setImage($venue['image_path']) }}">
            <div class="venue-details">
                <div class="country-data">
                    <img src="{{ $helper->setImage($venue['country']['image_path']) }}">
                    {{ $venue['country']['name'] }}
                </div>
                <span> {{ $venue['name'] }}, {{ $venue['city'] }} </span> <br />
                Capacity : <span> {{ $venue['capacity'] ? $venue['capacity'] : 'NA' }} </span> <br /> 
                FloodLight : <span> {{ $venue['floodlight'] ? 'Yes' : 'No' }} </span>
            </div>
        </div>
    @endforeach
</div>

@endsection