<!DOCTYPE html>
<html lang="en">

<head>
    <title>Entrega de turno Clínico </title>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <meta http-equiv="X-UA-Compatible" content="IE=edge" />
        
        <link rel="icon" href="assets/images/favicon.ico" type="image/x-icon">
        <link href="{{ URL::to('css/css.css') }}" rel="stylesheet">
        <link href="{{ URL::to('assets/pages/waves/css/waves.min.css') }}" rel="stylesheet">
        <link href="{{ URL::to('assets/fontawesome/css/all.css') }}" rel="stylesheet">
        <link href="{{ URL::to('assets/css/bootstrap/css/bootstrap.min.css') }}" rel="stylesheet">
        <link href="{{ URL::to('assets/icon/themify-icons/themify-icons.css') }}" rel="stylesheet">
        <link href="{{ URL::to('assets/css/style.css') }}" rel="stylesheet">
        <link href="{{ URL::to('css/select2.css') }}" rel="stylesheet">

</head>
  <body>
  <div class="theme-loader">
      <div class="loader-track">
          <div class="preloader-wrapper">
              <div class="spinner-layer spinner-blue">
                  <div class="circle-clipper left">
                      <div class="circle"></div>
                  </div>
                  <div class="gap-patch">
                      <div class="circle"></div>
                  </div>
                  <div class="circle-clipper right">
                      <div class="circle"></div>
                  </div>
              </div>
              <div class="spinner-layer spinner-red">
                  <div class="circle-clipper left">
                      <div class="circle"></div>
                  </div>
                  <div class="gap-patch">
                      <div class="circle"></div>
                  </div>
                  <div class="circle-clipper right">
                      <div class="circle"></div>
                  </div>
              </div>
            
              <div class="spinner-layer spinner-yellow">
                  <div class="circle-clipper left">
                      <div class="circle"></div>
                  </div>
                  <div class="gap-patch">
                      <div class="circle"></div>
                  </div>
                  <div class="circle-clipper right">
                      <div class="circle"></div>
                  </div>
              </div>
            
              <div class="spinner-layer spinner-green">
                  <div class="circle-clipper left">
                      <div class="circle"></div>
                  </div>
                  <div class="gap-patch">
                      <div class="circle"></div>
                  </div>
                  <div class="circle-clipper right">
                      <div class="circle"></div>
                  </div>
              </div>
          </div>
      </div>
  </div>
  <!-- Pre-loader end -->

  <div id="pcoded" class="pcoded">
      <div class="pcoded-overlay-box"></div>
      <div class="pcoded-container navbar-wrapper">
          <nav class="navbar header-navbar pcoded-header" style="background-color: #007A74">
              <div class="navbar-wrapper">
                  <div class="navbar-logo ml-3" >
                      <a class="mobile-menu waves-effect waves-light" id="mobile-collapse" href="#!">
                          <i class="ti-menu"></i>
                      </a>
                      <a href="home" >
                        <img src="{{ URL::to('img/hcc.png')}}" width="60px;"  class="mb-1"><br>
                         <b>Entrega de Turno Clínico</b> 
                      </a>
                  </div>
                
                  <div class="navbar-container container-fluid">
                      <ul class="nav-left">
                          <li>
                              <div class="sidebar_toggle"><a href="javascript:void(0)"><i class="ti-menu"></i></a></div>
                          </li>
                  
                      </ul>
                      <ul class="nav-right">
                          <li class="user-profile header-notification">
                              <a href="#!" class="waves-effect waves-light">
                                  <span>{{session('nombre')}}</span>
                                  <i class="ti-angle-down"></i>
                              </a>
                              <ul class="show-notification profile-notification">
                                  <li class="waves-effect waves-light">
                                      <a href="Salir">
                                          <i class="ti-layout-sidebar-left"></i> Logout
                                      </a>
                                  </li>
                              </ul>
                          </li>
                      </ul>
                  </div>
              </div>
          </nav>
          <div class="pcoded-main-container">
              <div class="pcoded-wrapper">
                  <nav class="pcoded-navbar">
                      <div class="sidebar_toggle"><a href="home"><i class="icon-close icons"></i></a></div>
                      <div class="pcoded-inner-navbar main-menu">
                          <div class="pcoded-navigation-label" data-i18n="nav.category.navigation" style="color: #00628F">Menú</div>
                          <ul class="pcoded-item pcoded-left-item">
                            <li class="{{ ! Route::is('home') ?: 'active' }}">
                                <a href="home" class="waves-effect waves-dark"  >
                                      <span  class="pcoded-micon"><i class="ti-home"></i></span>
                                      <span class="pcoded-mtext" data-i18n="nav.dash.main">Inicio</span>
                                      <span class="pcoded-mcaret"></span>
                                  </a>
                              </li>
                              <li class="{{ ! Route::is('indexMedico') ?: 'active' }}">
                                <a href="indexMedico" class="waves-effect waves-dark" >
                                      <span class="pcoded-micon"><i class="fas fa-procedures"></i></span>
                                      <span class="pcoded-mtext" data-i18n="nav.dash.main">Turno Médico</span>
                                      <span class="pcoded-mcaret"></span>
                                  </a>
                              </li>
                              <li class="{{ ! Route::is('reportes') ?: 'active' }}">
                                <a href="reportes" class="waves-effect waves-dark" >
                                      <span class="pcoded-micon"><i class="fas fa-file-alt"></i></span>
                                      <span class="pcoded-mtext" data-i18n="nav.dash.main">Reportes</span>
                                      <span class="pcoded-mcaret"></span>
                                  </a>
                              </li>
                          </ul>
                
                      </div>
               
                  </nav>
                  <div class="pcoded-content">
                      <div class="page-header">
                          <div class="page-block">
                              <div class="row align-items-center">
                                  <div class="col-md-8">
                                      <div class="page-header-title">                                        
                                      </div>
                                  </div>
                                  <div class="col-md-4">
                                      <ul class="breadcrumb-title">
                                          <li class="breadcrumb-item">
                                              <a href="/"> <i class="fa fa-home"></i> </a>
                                          </li>
                                          <li class="breadcrumb-item" style="color: white"><a href="home" style="color: white">Inicio</a>
                                          </li>
                                      </ul>
                                  </div>
                              </div>
                          </div>
                      </div>
                      @yield("content")
                    </div>
                </div>
            </div>
        </div>
    </div>


    <script type="text/javascript" src="{{ URL::to('assets/js/jquery/jquery.min.js') }}"></script>
    <script type="text/javascript" src="{{ URL::to('assets/js/popper.js/popper.min.js') }}"></script>
    <script type="text/javascript" src="{{ URL::to('assets/js/bootstrap/js/bootstrap.min.js ') }}"></script>
    <script type="text/javascript" src="{{ URL::to('assets/pages/waves/js/waves.min.js') }}"></script>
    <script type="text/javascript" src="{{ URL::to('assets/js/pcoded.min.js') }}"></script>
    <script type="text/javascript" src="{{ URL::to('assets/js/vertical-layout.min.js') }}"></script>
    <script type="text/javascript" src="{{ URL::to('assets/js/script.js') }}"></script>
    <script type="text/javascript" src="{{ URL::to('js/select2.min.js') }}"></script>
    <script type="text/javascript" src="{{ URL::to('js/moment.min.js') }}"></script>

@yield("script")
 
</body>

</html>
