@extends('layouts/app')

@section('content')

<div class="heading"> SEASONS </div>

<div class="row main-div">
    @if( $seasons['success'] )
        @foreach( $seasons['data'] as $season )
            <div class="col-md-3 season-list">
                <a href="javascript:void(0)" class="season-name" data-pid="{{ $season['id'] }}" data-current-url="{{ url()->current() }}"> {{ $season['name'] }} </a>
            </div>
        @endforeach
    @else
        <div class="error-msg"> {{ $seasons['data'] }} </div>
    @endif
</div>

@endsection