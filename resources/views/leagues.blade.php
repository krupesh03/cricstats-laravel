@extends('layouts/app')

@section('content')

<div class="row">
    @if( $leagues['success'] )
        @foreach( $leagues['data'] as $league )
            <div class="col-md-4 league-info">
                <a href="javascript:void(0)" data-pid="{{ $league['id'] }}" data-id="{{ $league['season_id'] }}">
                    <div class="league-logo">
                        @if( $league['image_path'] )
                            <img src="{{ $league['image_path'] }}">
                        @else
                            <img src="{{  url('assets/images/dummy/dummy.jpg') }}">
                        @endif
                    </div>
                    <div class="league-name"> {{ $league['name'] }} 
                        @if( $league['code'] )
                            ({{ $league['code'] }})
                        @endif
                    </div>
                </a>
            </div>
        @endforeach
    @else
        <div class="error-msg"> {{ $leagues['data'] }} </div>
    @endif
</div>

@endsection