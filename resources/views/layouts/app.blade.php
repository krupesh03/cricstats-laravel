<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <link rel="SHORTCUT ICON" href="{{ url('assets/logo/logo.svg') }}"/>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@100&display=swap" rel="stylesheet">

    <!-- Styles -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>
    <link href="{{ asset('assets/css/styles.css') }}" rel="stylesheet">
</head>
<body>

<div class="m-4">
    <nav class="navbar navbar-expand-sm navbar-light bg-light background-blue">
        <div class="container-fluid">
            <a href="#" class="navbar-brand">
                <img class="avatar-class" src="{{url('assets/logo/logo.svg') }}" alt="" width="32" height="32">
            </a>
            <button type="button" class="navbar-toggler" data-bs-toggle="collapse" data-bs-target="#navbarCollapse">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div id="navbarCollapse" class="collapse navbar-collapse">
                <ul class="nav navbar-nav">
                    <li class="nav-item">
                        <a href="#" class="nav-link">Home</a>
                    </li>
                    <li class="nav-item">
                        <a href="#" class="nav-link">Profile</a>
                    </li>
                    <li class="nav-item dropdown">
                        <a href="#" class="nav-link dropdown-toggle" data-bs-toggle="dropdown">Messages</a>
                        <div class="dropdown-menu">
                            <a href="#" class="dropdown-item">Inbox</a>
                            <a href="#" class="dropdown-item">Drafts</a>
                            <a href="#" class="dropdown-item">Sent Items</a>
                            <div class="dropdown-divider"></div>
                            <a href="#"class="dropdown-item">Trash</a>
                        </div>
                    </li>
                </ul>
                <ul class="nav navbar-nav ms-auto">
                    <li class="nav-item dropdown">
                        <a href="#" class="nav-link dropdown-toggle" data-bs-toggle="dropdown">Admin</a>
                        <div class="dropdown-menu dropdown-menu-end">
                            <a href="#" class="dropdown-item">Reports</a>
                            <a href="#" class="dropdown-item">Settings</a>
                            <div class="dropdown-divider"></div>
                            <a href="#" class="dropdown-item">Logout</a>
                        </div>
                    </li>
                </ul>
            </div>
        </div>
    </nav>    
</div>

</body>
</html>