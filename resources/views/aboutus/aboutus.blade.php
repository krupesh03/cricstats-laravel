@extends('layouts/app')

@section('content')

<div class="heading"> About Us </div>
<hr />

<div class="row main-div">
    <div class="col-md-3 about-us-site-logo">
        <img src="{{url('assets/logo/logo.png') }}" alt="">
    </div>
    <div class="col-md-2"></div>
    <div class="col-md-7 about-us-site-description">
        This application uses <span>free plan</span>. The free plan only has <span>Twenty20 International (T20I)</span>, <span>CSA T20 Challenge (T20C)</span> and the <span>Big Bash League (BBL)</span> included. <br /><br />
        {{ env('APP_NAME') }} is a dedicated for Cricket fans, it has all the stats of cricketers. {{ env('APP_NAME') }} is an amazing application which has all the professional information about your favourite cricketers at your finger tips. {{ env('APP_NAME') }} features include - Batting and Bowling statistics of the players. <br /><br />
        To your surprise, we also include <span>Live Scores :)</span>.
    </div>
</div>

@endsection