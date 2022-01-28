@extends('layouts/app')

@section('content')

<div class="heading"> ICC TEAM RANKINGS <span> - {{ ucfirst($slug) }} </span> </div>
<hr />

<div class="row main-div">
    @if( $apiData['success'] )
        @foreach( $apiData['data'] as $a )
            <div class="col-md-3 format-rankings">
                @php 
                $typeStyle = '';
                $typeStatus = 'color-switch-active';
                if( $a['type'] != 'TEST' ) {
                    $typeStyle = 'display:none';
                    $typeStatus = 'color-switch-inactive';
                }
                @endphp
                <div class="team-format {{ $typeStatus }}" id="{{ strtolower($a['type']) }}"> {{ $a['type'] }} </div> 
                <table class="table table-icc-rankings" style='{{ $typeStyle }}'>
                    <tr>
                        <th scope="col">Position</th>
                        <th scope="col">Team</th>
                        <th scope="col">Matches</th>
                        <th scope="col">Points</th>
                        <th scope="col">Rating</th>
                    </tr>
                    @foreach( $a['team'] as $t )
                        <tr>
                            <td>{{ $t['ranking']['position'] }}</td>
                            <td>
                                <div class="team-name-ranking">
                                    <p> 
                                        <img src="{{ $helper->setImage($t['image_path']) }}">
                                        {{ $t['name'] }} 
                                    </p>
                                </div>
                            </td>
                            <td>{{ $t['ranking']['matches'] }}</td>
                            <td>{{ $t['ranking']['points'] }}</td>
                            <td>{{ $t['ranking']['rating'] }}</td>
                        </tr>
                    @endforeach
                </table>
            </div>
        @endforeach
    @else
        <div class="error-msg"> {{ $apiData['data'] }} </div>
    @endif
</div>

@endsection