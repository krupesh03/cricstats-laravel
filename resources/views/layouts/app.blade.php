<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <link rel="SHORTCUT ICON" href="{{ url('assets/logo/shortcut-icon.png') }}"/>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ env('APP_NAME') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@100&display=swap" rel="stylesheet">

    <!-- Styles -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>
    <link href="{{ asset('assets/css/styles.css') }}" rel="stylesheet">
</head>
<body>

<div class="m-4 site-layout">
    <nav class="navbar navbar-expand-sm navbar-light bg-light background-blue">
        <div class="container-fluid">
            <a href="{{ url('/') }}" class="navbar-brand">
                <img class="avatar-class" src="{{url('assets/logo/logo.png') }}" alt="" width="auto" height="32">
            </a>
            <button type="button" class="navbar-toggler" data-bs-toggle="collapse" data-bs-target="#navbarCollapse">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div id="navbarCollapse" class="collapse navbar-collapse">
                <ul class="nav navbar-nav">
                    <li class="nav-item">
                        <a href="{{ url('/teams') }}" class="nav-link">Teams</a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ url('/players') }}" class="nav-link">Players</a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ url('/officials') }}" class="nav-link">Officials</a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ url('/venues') }}" class="nav-link">Venues</a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ url('/fixture') }}" class="nav-link">Schedule/Results</a>
                    </li>
                    <li class="nav-item dropdown">
                        <a href="#" class="nav-link dropdown-toggle" data-bs-toggle="dropdown">Live Scores</a>
                        <div class="dropdown-menu">
                            <a href="#" class="dropdown-item"><span class="error-msg">(Coming Soon)</span></a>
                        </div>
                    </li>
                    <li class="nav-item dropdown">
                        <a href="#" class="nav-link dropdown-toggle" data-bs-toggle="dropdown">Rankings</a>
                        <div class="dropdown-menu">
                            <a href="{{ url('icc-rankings/men/teams') }}" class="dropdown-item">ICC Team Rankings - Men</a>
                            <a href="{{ url('icc-rankings/women/teams') }}" class="dropdown-item">ICC Team Rankings - Women</a>
                        </div>
                    </li>
                </ul>
                <ul class="nav navbar-nav ms-auto">
                    <li class="nav-item dropdown">
                        <div class="search-container">
                            <form action="/players" method="GET">
                                <input type="text" placeholder="Search by player lastname" name="search_key" autocomplete="off">
                                <button type="submit"><i class="fa fa-search"></i></button>
                            </form>
                        </div>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <main class="py-4 main-content">
            @yield('content')
    </main>   
</div>

</body>

<footer>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
    <script src="{{ asset('assets/js/app.min.js') }}" type="text/javascript"></script>
</footer>

</html>