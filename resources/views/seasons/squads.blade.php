@extends('layouts/app')

@section('content')

<div class="row">
    @if( $squads['success'] )
        <div class="col-md-12 single-team-info">
            <div class="single-team-logo">
                @if( $squads['data']['image_path'] )
                    <img src="{{ $squads['data']['image_path'] }}">
                @else
                    <img src="{{  url('assets/images/dummy/dummy.jpg') }}">
                @endif
            </div>
            <div class="team-name"> {{ $squads['data']['name'] }} </div>
        </div>
        @foreach( $squads['data']['squad'] as $squad )
            <div class="col-md-3 squad-info">
                <a href="javascript:void(0)">
                    <div class="squad-logo">
                        @if( $squad['image_path'] )
                            <img src="{{  $squad['image_path'] }}">
                        @else
                            <img src="{{  url('assets/images/dummy/dummy.jpg') }}">
                        @endif
                    </div>
                    <div class="squad-name"> {{ $squad['fullname'] }} </div>
                </a>
            </div>
        @endforeach
        @if( count($squads['data']['squad']) == 0 )
            <div class="error-msg"> No squads found </div>
        @endif
    @else
        <div class="error-msg"> {{ $squads['data'] }} </div>
    @endif
</div>

@endsection