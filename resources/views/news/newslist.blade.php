@extends('layouts/app')

@section('content')

<div class="heading"> News </div>
<hr />

<div class="row main-div">
    @include('news.news')
</div>

@endsection