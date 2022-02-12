@extends('layouts/app')

@section('content')

<div class="heading"> ICC Team Rankings <span> - {{ ucfirst($slug) }} </span> </div>
<hr />

<div class="row main-div">
    @if( $apiData['success'] )
        <div class="row format-types">
            @foreach( $apiData['data'] as $a )
                <div class="col-md-3">
                @php 
                $typeStatus = 'color-switch-active';
                if( $a['type'] != 'TEST' ) {
                    $typeStatus = 'color-switch-inactive';
                }
                @endphp
                <div class="team-format {{ $typeStatus }}" id="{{ strtolower($a['type']) }}"> {{ $a['type'] }} </div>
                </div>
            @endforeach
        </div>
        @foreach( $apiData['data'] as $a )
            <div class="format-rankings">
                @php 
                $typeStyle = '';
                if( $a['type'] != 'TEST' ) {
                    $typeStyle = 'display:none';
                }
                @endphp
                <table class="table table-icc-rankings" id="{{ strtolower($a['type']) }}" style='{{ $typeStyle }}' width="100%">
                    <tr>
                        <th width="15%">Position</th>
                        <th width="40%">Team</th>
                        <th width="15%">Matches</th>
                        <th width="15%">Points</th>
                        <th width="15%">Rating</th>
                    </tr>
                    @foreach( $a['team'] as $t )
                        <tr>
                            <td>{{ $t['ranking']['position'] }}</td>
                            <td>
                                <div class="team-name-ranking">
                                    <img src="{{ $helper->setImage($t['image_path']) }}">
                                    {{ $t['name'] }} 
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