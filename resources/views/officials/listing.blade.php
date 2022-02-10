@extends('layouts/app')

@section('content')

<div class="heading"> Officials </div>
<hr />

<div class="row main-div">
    <table class="table official-list-table" width="100%">
        <tr>
            <th width="40%">Name</th>
            <th width="20%">Gender</th>
            <th width="20%">Date Of Birth</th>
            <th width="20%">Country</th>
        </tr>
        @foreach( $applicableOfficials as $official )
            <tr>
                <td> {{ $official['fullname'] }} </td>
                <td> {{ $official['gender'] == 'm' ? 'Male' : 'Female' }} </td>
                <td> {{ $official['dateofbirth'] ? date('d M Y', strtotime($official['dateofbirth'])) : '' }} </td>
                <td>
                    <div class="country-data">
                        <img src="{{ $helper->setImage($official['country']['image_path']) }}">
                        {{ $official['country']['name'] }}
                    </div>
                </td>
            </tr>
        @endforeach
    </table>
</div>

@endsection