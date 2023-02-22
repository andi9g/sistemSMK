
<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>Sistem Informasi Absensi</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="{{ url('resource_admin/plugins/fontawesome-free/css/all.min.css', []) }}">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
  <link rel="stylesheet" href="{{ url('resource_admin/dist/css/adminlte.min.css', []) }}">
  <link rel="stylesheet" href="{{ url('resource_admin/dist/css/cssku.css?v=1', []) }}">
  @yield('atas')
  <link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700" rel="stylesheet">
  @stack('start')
  

</head>
<body class="sidebar-mini sidebar-closed text-sm loaded" id="badan">
  <div id="loader-wrapper" style="z-index: 1000000000">
    <div id="loader"></div>
    <div class="loader-section section-left"></div>
    <div class="loader-section section-right"></div>
</div>

  <!-- Modal -->
<div class="modal fade" id="ubahPassword" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Ubah Password</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <div class="">
          <div class="">
            <div class="tab-content">
              <div class="active tab-pane" id="activity">
                
                <div class="post">
                  <form class="form-horizontal" action="{{ route('ubah.password', []) }}" method="post">
                      @csrf
                      @method('PUT')
                      <div class="form-group row">
                        <label for="inputPassword1" class="col-sm-3 col-form-label">Password Baru</label>
                        <div class="col-sm-9">
                          <input type="password" class="form-control" onkeyup="cek()" name="password1" id="inputPassword1" placeholder="password baru">
                        </div>
                      </div>
                      <div class="form-group row">
                        <label for="inputPassword2" class="col-sm-3 col-form-label">Ulangi Password Baru</label>
                        <div class="col-sm-9">
                          <input type="password" class="form-control" onkeyup="cek();" name="password2" id="inputPassword2" placeholder="ulangi password baru">
                        </div>
                      </div>
                      <div class="form-group row">
                          <div class="offset-sm-3 col-sm-9">
                            <button type="submit" class="btn btn-danger">Ubah Password</button>
                          </div>
                        </div>
                  </form>
  
                  <script>
                      function cek(){
                          var pass1 = document.getElementById('inputPassword1').value;
                          var pass2 = document.getElementById('inputPassword2').value;
  
                          if(pass1.length >=5 ){
                                  document.getElementById('inputPassword1').className="form-control";
                              if(pass1 == pass2){
                                  document.getElementById('inputPassword1').className="form-control is-valid";
                                  document.getElementById('inputPassword2').className="form-control is-valid";
                              }else if(pass2.length == 0){
                                  document.getElementById('inputPassword2').className="form-control";
  
                              }else {
                                   document.getElementById('inputPassword2').className="form-control is-invalid";
                              }
                          }else if(pass1.length==0){
                                  document.getElementById('inputPassword1').className="form-control";
                          }else {
                              document.getElementById('inputPassword1').className="form-control is-invalid";
  
                          }
                         
  
                      }
                  </script>
                 
                </div>
                <!-- /.post -->
              </div>
              
              <!-- /.tab-pane -->
            </div>
            <!-- /.tab-content -->
          </div><!-- /.card-body -->
        </div>
      </div>
    </div>
  </div>
</div>



<!-- Site wrapper -->
<div class="wrapper">
  <!-- Navbar -->
  <nav class="main-header navbar navbar-expand navbar-white navbar-light pinkku">
    <!-- Left navbar links -->
    <ul class="navbar-nav">
      <li class="nav-item">
        <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
      </li>
      
    </ul>

    <!-- Right navbar links -->
    <ul class="navbar-nav ml-auto">
      <!-- Messages Dropdown Menu -->
      
      <li class="nav-item">
        <a class="nav-link" href="{{ url('logout', []) }}" role="button">
          <i class="fa fa-power-off"></i>
        </a>
      </li>
    </ul>
  </nav>
  <!-- /.navbar -->

  <!-- Main Sidebar Container -->
  <aside class="main-sidebar   text-dark  pinkku2 elevation-4">
    <!-- Brand Logo -->
    <a href="{{ url('/welcome', []) }}" class="brand-link pink-gelapku">
      <h3 class="brand-image rounded-circle bg-info px-1 text-bold" style="padding-top:2px ">SI</h3>
      <span class="brand-text text-bold text-white" style="font-size: 17px;letter-spacing: 2px">ABSENSI E-KTM</span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">
      <!-- Sidebar user (optional) -->
      <div class="user-panel mt-3 pb-1 mb-3 d-flex">
        <div class="image mt-3">
          <img src="{{ url('resource_admin/gambar/icon.png', []) }}" class="m-0 p-0">
        </div>
        <div class="info mt-1">
          <a href="#" class="d-block text-capitalize">{{ Session::get('nama') }} ({{ Session::get('posisi') }})</a>
          <button type="button" data-toggle="modal" data-target="#ubahPassword" class="badge badge-success border-0"> Ubah Password </button>
        </div>
      </div>
      <hr>

      <!-- Sidebar Menu -->
      <nav class="mt-2">
        <ul class="nav nav-pills nav-sidebar flex-column nav-flat nav-legacy" data-widget="treeview" role="menu" data-accordion="false">
          <!-- Add icons to the links using the .nav-icon class
               with font-awesome or any other icon font library -->
        
          {{-- <li class="nav-item hoverku">
            <a href="{{ url('/welcome', []) }}" class="nav-link @yield('activekuHome')">
              <i class="nav-icon fa fa-dashboard"></i>
              <p>
                DASHBOARD
              </p>
            </a>
          </li> --}}

          <li class="nav-item hoverku">
            <a href="{{ url('/absen', []) }}" class="nav-link @yield('activekuAbsen')">
              <i class="nav-icon fa fa-door-open"></i>
              <p>
                ABSENSI
              </p>
            </a>
          </li>

          @if (Session::get('posisi')=='admin')
          <li class="nav-item hoverku">
            <a href="{{ url('/siswacard/card', []) }}" class="nav-link @yield('activekuSiswa')">
              <i class="nav-icon fa fa-id-card"></i>
              <p>
                TAG/CARD SISWA
              </p>
            </a>
          </li>

          <li class="nav-item hoverku">
            <a href="{{ url('/card/cek', []) }}" class="nav-link @yield('activekuCekCard')">
              <i class="nav-icon fa fa-wifi"></i>
              <p>
                IDENTITAS KARTU
              </p>
            </a>
          </li>
          @endif

          <li class="nav-item hoverku">
            <hr>
            <a href="{{ url('/laporan', []) }}" class="nav-link @yield('activekuLaporan')">
              <i class="nav-icon fa fa-print"></i>
              <p>
                LAPORAN
              </p>
            </a>
          </li>


          
              
          

          

          @if (Session::get('posisi')=='superadmin')
          <li class="nav-item hoverku">
            <hr>
            <a href="{{ url('/alat', []) }}" class="nav-link @yield('activekuMaster')">
              <i class="nav-icon fa fa-home"></i>
              <p>
                RUANGAN (RFID)
              </p>
            </a>
          </li>

          <li class="nav-item hoverku">
            
            <a href="{{ url('/admin', []) }}" class="nav-link @yield('activekuAdmin')">
              <i class="nav-icon fa fa-user"></i>
              <p>
                ADMIN
              </p>
            </a>
          </li>

          <li class="nav-item hoverku">
            <a href="{{ url('superadmin', []) }}" class="nav-link @yield('activekuSuperadmin')">
              <i class="nav-icon fa fa-key"></i>
              <p>
                SUPERADMIN
              </p>
            </a>
          </li>
              
          @endif

          <li class="nav-item hoverku">
            <hr>
            <a href="{{ url('/pengaturan', []) }}" class="nav-link @yield('activekuPengaturan')">
              <i class="nav-icon fa fa-wrench"></i>
              <p>
                PENGATURAN
              </p>
            </a>
          </li>
          
          
          
          
          
        </ul>
      </nav>
      <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
  </aside>

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="container">
    <section class="content-header">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>@yield('judul')</h1>
          </div>
        </div>
      </section>
      
      <!-- Main content -->
      <section class="content mx-4">
        {{-- <div class="container"> --}}
          @yield('content')
        {{-- </div> --}}
        
      </section>
      <!-- /.content -->
    </div><!-- /.container-fluid -->
  </div>
  <!-- /.content-wrapper -->

  <footer class="main-footer text-sm footerku">
    <div class="float-right d-none d-sm-block">
      <b>Version</b> 3.0.4
    </div>
    <strong>Copyright &copy; 2014-2019 <a href="http://adminlte.io">AdminLTE.io</a>.</strong> All rights
    reserved.
  </footer>

  <!-- Control Sidebar -->
  <aside class="control-sidebar control-sidebar-dark">
    <!-- Control sidebar content goes here -->
  </aside>
  <!-- /.control-sidebar -->
</div>
<!-- ./wrapper -->


@stack('end')
@include('sweetalert::alert')


@yield('myScript')
@yield('bawah')

</body>
</html>
