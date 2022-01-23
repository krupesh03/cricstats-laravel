@extends('layouts/app')

@section('content')

<div class="row">
    @if( $apiData['success'] )
        @foreach( $apiData['data']['data'] as $a )
            <div class="col-md-4 format-rankings">
                @php 
                $typeStyle = '';
                $typeStatus = 'color-switch-active';
                if( $a['type'] != 'T20I' ) {
                    $typeStyle = 'display:none';
                    $typeStatus = 'color-switch-inactive';
                }
                @endphp
                <div class="team-format {{ $typeStatus }}"> {{ $a['type'] }} </div> 
            </div>
            <table class="table" style='{{ $typeStyle }}' width="50%">
                <thead>
                    <tr>
                        <th scope="col" width="5%">Position</th>
                        <th scope="col" width="30%">Team</th>
                        <th scope="col" width="5%">Matches</th>
                        <th scope="col" width="5%">Points</th>
                        <th scope="col" width="5%">Rating</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach( $a['team'] as $t )
                        <tr>
                            <td>{{ $t['ranking']['position'] }}</td>
                            <td>
                                <div class="team-name-ranking">
                                    @if( $t['image_path'] )
                                        <p> <img src="{{ $t['image_path'] }}"> </p>
                                    @else
                                        <p> <img src="{{  url('assets/images/dummy/dummy.jpg') }}"> </p>
                                    @endif
                                    <p style="width: 100px;padding-left: 2px;"> {{ $t['name'] }} </p>
                                </div>
                            </td>
                            <td>{{ $t['ranking']['matches'] }}</td>
                            <td>{{ $t['ranking']['points'] }}</td>
                            <td>{{ $t['ranking']['rating'] }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endforeach
    @endif
</div>

@endsection