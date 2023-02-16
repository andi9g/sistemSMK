<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <title>@yield('judul')</title>
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="stylesheet" href="{{ url('bootstrap/bootstrap.min.css', []) }}">
    <link rel="stylesheet" href="{{ url('bootstrap/mycss.css', []) }}">
    

  </head>
<body style="background: url('img/patern.png')" class="loaded">
    <div id="loader-wrapper">
        <div id="loader"></div>
        <div class="loader-section section-left"></div>
        <div class="loader-section section-right"></div>
    </div>
    {{-- Navbar  --}}
    <nav class="navbar navbar-expand-lg bg-none">
        <div class="navbar-collapse collapse w-100 dual-collapse2 order-1 order-md-0">
            <ul class="navbar-nav ml-auto text-center">
                <li class="nav-item">
                    <a class="nav-link @yield('activeAbsen')" disable href="{{ url('/', []) }}">ABSENSI</a>
                </li>
            </ul>
        </div>
        <div class="mx-3 my-2 order-0 order-md-1 position-relative">
            <a class="mx-auto d-none d-md-block disabled">
                |
            </a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target=".dual-collapse2" class="border:1px solid gray">
                MENU
            </button>
        </div>
        <div class="navbar-collapse collapse w-100 dual-collapse2 order-2 order-md-2">
            <ul class="navbar-nav mr-auto text-center">
                <li class="nav-item">
                    <a class="nav-link @yield('activeLogin')" href="{{ url('login', []) }}">LOGIN</a>
                </li>
            </ul>
        </div>
    </nav>


    
    <div class="container mt-4">
        <div class="row">
            <div class="col-md-2"></div>
            <div class="col-md-8 text-center align-content-center justify-content-center" style="">

                @yield('jam')

                
                    @yield('content')
                
        

            </div>

            
        </div>
    </div>

    




    <script src="{{ url('bootstrap/jquery.slim.min.js', []) }}"></script>
    

    <script src="{{ url('bootstrap/bootstrap.bundle.min.js', []) }}"></script>
    <script src="{{ url('jquery.min.js', []) }}"></script>

    @include('sweetalert::alert')
    @yield('myScript')
    
    

  </body>
</html>