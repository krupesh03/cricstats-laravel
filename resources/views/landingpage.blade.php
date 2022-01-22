@extends('layouts/app')

@section('content')

@if( $teams['success'] )

    @foreach( $teams['data']['data'] as $team )
        @if( $team['national_team'] )
            Team: {{ $team['name'] }}
            Logo: <img src="{{ $team['image_path'] }}" width="32" height="32">
        @endif
    @endforeach

@endif

@endsection