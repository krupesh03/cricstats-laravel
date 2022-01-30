@extends('layouts/app')

@section('content')

<div class="heading"> STANDINGS </div>
<hr />

<div class="row main-div">
    @if( $standings['success'] )
        @if( count($standings['data']) )
            <div class="league-standings-table">
                <div class="season-name"> Season: 
                    <span> {{ isset($standings['data'][0]['season']['name']) ? $standings['data'][0]['season']['name'] : '' }} </span> 
                </div>
                @if( isset($standings['data'][0]['league']['name']) && !empty( $standings['data'][0]['league']['name'] ) ) 
                    <div class="league-logo">
                        <img src="{{ $helper->setImage($standings['data'][0]['league']['image_path']) }}">
                        <div class="league-name"> {{ $standings['data'][0]['league']['name'] }} 
                            @if( $standings['data'][0]['league']['code'] )
                                ({{ $standings['data'][0]['league']['code'] }})
                            @endif
                        </div>
                    </div>
                @endif
                <hr />
                <table class="table table-league-rankings" width="100%">
                    <tr>
                        <th width="6%">Position</th>
                        <th width="15%">Team</th>
                        <th width="6%">Played</th>
                        <th width="6%">Won</th>
                        <th width="6%">Lost</th>
                        <th width="6%">Draw</th>
                        <th width="6%">NR</th>
                        <th width="6%">Points</th>
                        <th width="6%">NRR</th>
                        <th width="17%">Recent Form</th>
                    </tr>
                    @foreach( $standings['data'] as $standing )
                        <tr>
                            <td>{{ $standing['position'] }}</td>
                            <td>
                                <div class="team-name-ranking">
                                    <img src="{{ $helper->setImage($standing['team']['image_path']) }}">
                                    {{ $standing['team']['name'] }} 
                                </div>
                            </td>
                            <td>{{ $standing['played'] }}</td>
                            <td>{{ $standing['won'] }}</td>
                            <td>{{ $standing['lost'] }}</td>
                            <td>{{ $standing['draw'] }}</td>
                            <td>{{ $standing['noresult'] }}</td>
                            <td>{{ $standing['points'] }}</td>
                            <td>{{ ($standing['netto_run_rate'] > 0) ? '+' . $standing['netto_run_rate'] : $standing['netto_run_rate'] }}</td>
                            <td>
                                @foreach( $standing['recent_form'] as $form )
                                    <span class="{{ $form }}-style"> {{ $form }} </span>
                                @endforeach
                            </td>
                        </tr>
                    @endforeach
                </table>
            </div>
        @else
            <div class="error-msg"> No Standings Available </div>
        @endif
    @else
        <div class="error-msg"> {{ $standings['data'] }} </div>
    @endif
</div>

@endsection