@extends('layouts/app')

@section('content')

<div class="heading"> Venues </div>
<hr />

<div class="row main-div">
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

@endsection