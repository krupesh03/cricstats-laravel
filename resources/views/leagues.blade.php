@extends('layouts/app')

@section('content')

<div class="heading"> LEAGUES </div>

<div class="row main-div">
    @php 
    if( $leagues['success'] ) { 
        foreach( $leagues['data'] as $league ) { @endphp
            <div class="col-md-4 league-info">
                <a href="javascript:void(0)" class="league-info-url" data-pid="{{ $league['id'] }}">
                    <div class="league-logo">
                        <img src="{{ $helper->setImage($league['image_path']) }}">
                    </div>
                    <div class="league-name"> {{ $league['name'] }} 
                        @if( $league['code'] )
                            ({{ $league['code'] }})
                        @endif
                    </div>
                </a>
            </div>
        @php 
        }
    } else { @endphp
        <div class="error-msg"> {{ $leagues['data'] }} </div>
    @php 
    }
    @endphp
</div>

@endsection