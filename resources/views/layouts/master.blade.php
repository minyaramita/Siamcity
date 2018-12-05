<!DOCTYPE html>
<!--
This is a starter template page. Use this page to start your new project from
scratch. This page gets rid of all links and provides the needed markup only.
-->
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta http-equiv="x-ua-compatible" content="ie=edge">
  <title>Siam City</title>
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <link rel="stylesheet" href="/css/app.css">
  <link rel="shortcut icon" href="img/favicon.png" type="image/x-icon">
</head>

<body class="hold-transition sidebar-mini fix-header">
<div class="wrapper" id="app">

  <!-- Navbar -->
  <nav class="main-header navbar navbar-expand bg-white navbar-light border-bottom" style="height:77px;">
    <!-- Left navbar links -->
    <ul class="navbar-nav">
      <li class="nav-item">
        <a class="nav-link" data-widget="pushmenu" href="#"><i class="fa fa-bars"></i></a>
      </li>
    </ul> 

    <div class="form-inline ml-3">
      <div class="input-group input-group-sm">
        <input class="form-control form-control-navbar" type="search" @keyup="searchit" v-model ="search" placeholder="ค้นหา" aria-label="Search">
        <div class="input-group-append">
          <button class="btn btn-navbar" @click="searchit">
            <i class="fa fa-search"></i>
          </button>
        </div>
      </div>
    </div>

    <ul class="navbar-nav ml-auto">
      <!-- Messages Dropdown Menu -->
      <li class="nav-item dropdown">
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
    </ul>                            
  </nav>
  <!-- /.navbar -->

  <!-- Main Sidebar Container -->
  <aside class="main-sidebar sidebar-light-primary elevation-4">
    <!-- Brand Logo -->
    <a href="#" class="brand-link">
      <img src="./img/logo.png" alt="Siam City Logo" width="60%"
           style="opacity: 1">
      <span class="brand-text font-weight-light"></span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">
      <!-- Sidebar user panel (optional) -->
      <div class="user-panel mt-3 pb-3 mb-3 d-flex">
        <div class="image">
          <img src="img/profile/{{Auth::user()->photo}}" class="img-circle elevation-2"  alt="User Image">
        </div>
        <div class="info">
          <a href="#" class="d-block">
          {{Auth::user()->name}}
          <br>
          {{Auth::user()->type}}
          </a>
        </div>
      </div>

      <!-- Sidebar Menu -->
      <nav class="mt-2">
        <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
          <!-- Add icons to the links using the .nav-icon class
               with font-awesome or any other icon font library -->

          <li class="nav-item">
            <router-link to="/home" class="nav-link">
              <i class="nav-icon fas fa-home"></i>
              <p>
                หน้าหลัก
              </p>
            </a>
          </li>
          <li class="nav-item">
            <router-link to="/namelist" class="nav-link">
              <i class="nav-icon fas fa-list-ul"></i>
              <p>
                การรับรายชื่อ
              </p>
            </a>
          </li>
          <li class="nav-item">
            <router-link to="/insurer" class="nav-link">
              <i class="nav-icon fas fa-users"></i>
              <p>
                ผู้ทำประกัน
              </p>
            </a>
          </li>
          <li class="nav-item">
            <router-link to="/claim" class="nav-link">
              <i class="nav-icon fas fa-user-injured"></i>
              <p>
                การเคลมประกัน
              </p>
            </a>
          </li>
          <li class="nav-item">
            <router-link to="/school" class="nav-link">
              <i class="nav-icon fas fa-school"></i>
              <p>
                สถานศึกษา
              </p>
            </a>
          </li>
          <li class="nav-item has-treeview">
            <a href="#" class="nav-link">
              <i class="nav-icon fas fa-file-alt"></i>
              <p>
                รายงาน
                <i class="right fas fa-angle-left blue"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <a href="{{ url('/chartClaim')}}" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>รายงานการเคลมประกัน</p>
                </a>
              </li>
            </ul>
          </li>
          <!--
          <li class="nav-item">
            <router-link to="/hospital" class="nav-link">
              <i class="nav-icon fas fa-hospital-alt"></i>
              <p>
                โรงพยาบาลคู่สัญญา
              </p>
            </a>
          </li>
          <li class="nav-item">
            <router-link to="/developer" class="nav-link">
              <i class="nav-icon fas fa-cogs"></i>
              <p>
                นักพัฒนา
              </p>
            </a>
          </li>
          -->
          @can('isAdmin')
          <li class="nav-item">
            <router-link to="/users" class="nav-link">
              <i class="nav-icon fas fa-users-cog"></i>
              <p>
                ผู้ใช้งาน
              </p>
            </a>
          </li>
          @endcan
          <li class="nav-item">
            <router-link to="/profile" class="nav-link">
              <i class="nav-icon fas fa-user"></i>
              <p>
                ประวัติผู้ใช้งาน
              </p>
            </a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="{{ route('logout') }}"
              onclick="event.preventDefault();
                            document.getElementById('logout-form').submit();">
                <i class="nav-icon fa fa-power-off red"></i>
                <p>
                  {{ __('ออกจากระบบ') }}
                </p>    
            </a>
            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
              @csrf
            </form>
          </li>
        </ul>
      </nav>
      <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
  </aside>

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Main content -->
    <div class="content">
      <div class="container-fluid">
        <router-view></router-view>
    
        <vue-progress-bar></vue-progress-bar>

      </div><!-- /.container-fluid -->
    </div>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->

  <!-- Main Footer -->
  <footer class="main-footer" style="padding-bottom:0px;">
    <!-- To the right -->
    <div class="float-right d-none d-sm-inline">
      
    </div>
    <!-- Default to the left -->
    
    <div class="row">
      <div class="col-md-2">
        <img class="img-fluid" src="img/logo3.png"  style="margin-left:60px; width:65%; padding-top:10px;"> &nbsp;
      </div>
      <div class="col-md-10" style="text-align:left;">
      <br>
        <h5 style="color:#0158a7;">บริษัท สยามซิตี้ประกันภัย จำกัด (มหาชน)</h5>
        <h6 style="color:#0158a7;">สำนักงานตัวแทนประกันวินาศภัย โดย คุณบุญยง เอมซ์บุตร</h6>
        <p style="color:#0158a7;">48/7 หมู่ 5 ตำบลท่าช้าง อำเภอเมือง จังหวัดจันทบุรี โทร. 039-471887 E-mail: bonyongchan@gmail.com</p>
      </div>
    </div>
     </footer>
</div>
<!-- ./wrapper -->

@auth
  <script>
      window.user = @json(auth()->user())
  </script> 
@endauth

<script src="/js/app.js"></script> 
</body>
</html>
