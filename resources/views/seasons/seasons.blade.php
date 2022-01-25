@extends('layouts/app')

@section('content')

<div class="heading"> Seasons </div> <div class="error-msg">(select season to view the squad of the selected team)</div>

<div class="row">
    @if( $apiData['success'] )
        @foreach( $apiData['data'] as $t )
            <div class="col-md-3 season-list">
                <a href="{{ url('/squads') }}/{{ $id }}/season/{{ $t['id'] }}"> {{ $t['name'] }} </a>
            </div>
        @endforeach
    @else
        <div class="error-msg"> {{ $apiData['data'] }} </div>
    @endif
</div>

@endsection