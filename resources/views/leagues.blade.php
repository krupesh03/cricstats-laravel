@extends('layouts/app')

@section('content')

<div class="heading"> LEAGUES </div>
<hr />

<div class="row main-div">
    @if( $leagues['success'] ) 
        @foreach( $leagues['data'] as $league )
            <div class="col-md-4 league-info">
                <a href="javascript:void(0)" class="league-info-url" data-pid="{{ $league['id'] }}">
                    <div class="league-logo">
                        <img src="{{ $helper->setImage($league['image_path']) }}">
                        <div class="league-name"> {{ $league['name'] }} 
                            @if( $league['code'] )
                                ({{ $league['code'] }})
                            @endif
                        </div>
                    </div>
                </a>
            </div>
        @endforeach
    @else
        <div class="error-msg"> {{ $leagues['data'] }} </div>
    @endif
</div>

@endsection