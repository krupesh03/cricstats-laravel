@extends('layouts/app')

@section('content')

<div class="heading"> SQUADS </div>

<div class="row main-div">
    <div class="team-squads">
        @if( $squads['success'] )
            @if( $season['success'] && !empty($season['data']['name'] ) )
                <div class="squad-season-name"> Season: <span>{{ $season['data']['name'] }}</span> </div>
            @endif
            <a href="javascript:void(0)" class="single-team-info">
                <div class="single-team-logo">
                    <img src="{{ $helper->setImage($squads['data']['image_path']) }}">
                </div>
                <div class="single-team-name"> {{ $squads['data']['name'] }} </div>
            </a>
            <hr />
            <div class="row">
                @foreach( $squads['data']['squad'] as $squad )
                    <div class="col-md-3 squad-info">
                        <a href="javascript:void(0)">
                            <div class="squad-logo">
                                <img src="{{ $helper->setImage($squad['image_path']) }}">
                            </div>
                            <div class="squad-name"> {{ $squad['fullname'] }} </div>
                        </a>
                    </div>
                @endforeach
                @if( count($squads['data']['squad']) == 0 )
                    <div class="error-msg"> No squads found </div>
                @endif
            </div>
        @else
            <div class="error-msg"> {{ $squads['data'] }} </div>
        @endif
    </div>
</div>

@endsection