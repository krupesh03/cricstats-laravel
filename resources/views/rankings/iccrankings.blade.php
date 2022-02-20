@extends('layouts/app')

@section('content')

<div class="heading"> ICC Team Rankings <span> - {{ ucfirst($slug) }} </span> </div>
<hr />

<div class="row main-div">
    @if( count($rankingData) )
        <div class="format-types">
            @if( isset($formats[$slug]) )
                @foreach( $formats[$slug] as $key => $format )
                    <div class="team-format {{ $key == 0 ? 'color-switch-active' : 'color-switch-inactive' }}" id="{{ strtolower($format) }}"> {{ $format }} </div>
                @endforeach
            @endif
        </div>

        @foreach( $rankingData as $k => $rank )
            <div class="format-rankings">
                <table class="table table-icc-rankings" id="{{ strtolower($rank['type']) }}" style='{{ $k == 0 ? '' : 'display:none' }}' width="100%">
                    <tr>
                        <th width="15%">Position</th>
                        <th width="40%">Team</th>
                        <th width="15%">Matches</th>
                        <th width="15%">Points</th>
                        <th width="15%">Rating</th>
                    </tr>
                    @foreach( $rank['team'] as $team )
                        <tr>
                            <td>{{ $team['ranking']['position'] }}</td>
                            <td>
                                <div class="team-name-ranking">
                                    <img src="{{ $helper->setImage($team['image_path']) }}">
                                    {{ $team['name'] }} 
                                </div>
                            </td>
                            <td>{{ $team['ranking']['matches'] }}</td>
                            <td>{{ $team['ranking']['points'] }}</td>
                            <td>{{ $team['ranking']['rating'] }}</td>
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