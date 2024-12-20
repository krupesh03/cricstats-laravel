@extends('layouts/app')

@section('content')

<div class="heading"> Officials </div>
<hr />

<div class="row main-div">
    <div class="official-filter-list">
        <form method="GET" class="search-form">
            <div class="form-group row">
                <label class="col-md-3 col-form-label">Country : </label>
                <div class="col-md-3">
                    <select name="country" class="form-control">
                        <option value="">Search by country</option>
                        @foreach( $dropdowns['countries'] as $id => $name )
                            <option value="{{ $id }}" {{ $id == request()->query('country') ? 'selected' : '' }}> {{ ucfirst($name) }} </option>
                        @endforeach
                    </select> 
                </div>
                <label class="col-md-3 col-form-label">Lastname : </label>
                <div class="col-md-3">
                    <input type="text" class="form-control" placeholder="Search by lastname" name="lastname" autocomplete="off" value="{{ request()->query('lastname') }}">
                </div>
            </div>
            <div class="form-group row">
                <div class="col-md-12 search-button">
                    <input type="submit" name="find_officials" value="search">
                    <input type="reset" name="reset_search" value="reset">
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
            @if( empty($applicableOfficials) )
                @if( request()->query('find_officials') == 'search' )
                    <tr>
                        <td colspan="4">
                            <div class="error-msg"> No Data Found </div>
                        </td>
                    </tr>
                @endif
            @endif
        </table>
    </div>
</div>

@endsection