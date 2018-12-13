<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Siam City</title>

    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}" defer></script>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet" type="text/css">

    <link rel="shortcut icon" href="img/favicon.png" type="image/x-icon">
    
    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
</head>
<body>
    <div id="app">
        <nav class="navbar navbar-expand-md navbar-light navbar-laravel">
            <div class="container">
                <a class="navbar-brand" href="{{ url('/') }}">
                    <img src="./img/logo.png" alt="Siam City Logo" width="40%" style="opacity: 1" class="float-left">
                </a>
                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <!-- Left Side Of Navbar -->
                    <ul class="navbar-nav mr-auto">

                    </ul>

                    <!-- Right Side Of Navbar -->
                    <ul class="navbar-nav ml-auto">
                        <!-- Authentication Links -->
                        @guest
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('login') }}">
                                    <i class="fas fa-sign-in-alt"></i>
                                    {{ __('เข้าสู่ระบบ') }}
                                </a>
                            </li>
                        @else
                        <li class="nav-item inline" style="padding-top: 20px;">
                                <a class="nav-link" href="{{ url('/home') }}"><i class="nav-icon fas fa-home"></i> หน้าหลัก</a>
                            </li>
                            
                            <li class="nav-item dropdown inline">
                                <a class="nav-link inline" data-toggle="dropdown" href="#" aria-expanded="false">
                                <div class="user-panel">
                                    <div class="image" style="padding: 10px;">
                                    <img src="img/profile/{{Auth::user()->photo}}" class="img-circle elevation-2"  alt="User Image">
                                    </div>
                                </div>
                                </a>   
                                <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
                                <a href="#" class="dropdown-item">
                                    <!-- Message Start -->
                                    <div class="media">
                                    <img src="img/profile/{{Auth::user()->photo}}" class="img-size-50 mr-3 ">
                                    <div class="media-body">
                                        <h3 class="dropdown-item-title">
                                        {{ Auth::user()->name }} 
                                        </h3>
                                        <p class="text-sm">{{ Auth::user()->type }}</p>
                                        <p class="text-sm text-muted">{{ Auth::user()->email }}</p>
                                    </div>
                                    </div>
                                    <!-- Message End -->
                                </a>

                                <div class="dropdown-divider"></div>
                                <router-link to="/profile" class="dropdown-item">
                                    <i class=" fas fa-user ml-3 mr-2"></i>
                                    ประวัติผู้ใช้งาน  
                                </a><br><br>
                                <div class="dropdown-divider"></div>
                                <a class="dropdown-item" href="{{ route('logout') }}"
                                        onclick="event.preventDefault();
                                                    document.getElementById('logout-form').submit();">
                                    <i class="fa fa-power-off red mr-2"></i>
                                    {{ __('ออกจากระบบ') }} 
                                </a>
                                <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                        @csrf
                                </form>
                                </div>
                            </li>
                        @endguest
                    </ul>
                </div>
            </div>
        </nav>

        <main class="py-4">
            @yield('content')
        </main>

         <footer class="myfooter" style="padding-bottom:0px;">
            <!-- To the right -->
            <div class=" d-none d-sm-inline">
                
            </div>
            <!-- Default to the left -->
            
            <div class="row">
                <div class="col-md-2">
                <img class="img-fluid" src="img/logo3.png"  style="margin-left:100px; width:50%; padding-top:10px;"> 
                </div>
                <div class="col-md-10" style="text-align:left; margin-left:0px;" > 
                <br>
                <h5 style="color:#0158a7;">บริษัท สยามซิตี้ประกันภัย จำกัด (มหาชน)</h5>
                <h6 style="color:#0158a7;">สำนักงานตัวแทนประกันวินาศภัย โดย คุณบุญยง เอมซ์บุตร</h6>
                <p style="color:#0158a7;">48/7 หมู่ 5 ตำบลท่าช้าง อำเภอเมือง จังหวัดจันทบุรี โทร. 039-471887 E-mail: bonyongchan@gmail.com</p>
                </div>
            </div>
        </footer>
    </div>
</body>
</html>
