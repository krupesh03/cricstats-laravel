@extends('layouts/app')

@section('content')

<div class="heading"> Officials </div>
<hr />

<div class="row main-div">
    <div class="official-filter-list">
        <form method="GET" class="search-form">
            <div class="form-group row">
                <label class="col-md-2 col-form-label">Country : </label>
                <div class="col-md-2">
                    <select name="country" class="form-control">
                        <option value="">--Select--</option>
                        @foreach( $dropdowns['countries'] as $id => $name )
                            <option value="{{ $id }}" {{ (isset($_GET['country']) && $id == $_GET['country']) ? 'selected' : '' }}> {{ ucfirst($name) }} </option>
                        @endforeach
                    </select> 
                </div>
                <label class="col-md-2 col-form-label">Lastname : </label>
                <div class="col-md-2">
                    <input type="text" class="form-control" placeholder="Search by lastname" name="lastname" autocomplete="off" value="{{ (isset($_GET['lastname'])) ? $_GET['lastname'] : '' }}">
                </div>
            </div>
            <div class="form-group row">
                <div class="col-md-12 search-button">
                    <input type="reset" name="reset_search" value="Reset">
                    <input type="submit" name="find_officials" value="Search">
                </div>
            </div>
        </form>
        <hr />

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
</div>

@endsection