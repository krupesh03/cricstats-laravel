@extends('layouts/app')

@section('content')

<div class="row">
    @if( $teams['success'] )
        @foreach( $teams['data'] as $team )
            @if( $team['national_team'] )
                <div class="col-md-3 team-info">
                    <a href="javascript:void(0)">
                        <div class="team-logo">
                            @if( $team['image_path'] )
                                <img src="{{ $team['image_path'] }}">
                            @else
                                <img src="{{  url('assets/images/dummy/dummy.jpg') }}">
                            @endif
                        </div>
                        <div class="team-name"> {{ $team['name'] }} </div>
                    </a>
                </div>
            @endif
        @endforeach
    @else
        <div class="error-msg"> {{ $teams['data'] }} </div>
    @endif
</div>

@endsection