<!DOCTYPE html>
<html lang="en">

<head>
    <title>Gestión de Capacitaciones </title>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <meta http-equiv="X-UA-Compatible" content="IE=edge" />
        
        <link rel="icon" href="img/ndc.png" type="image/x-icon">
        <link href="{{ URL::to('css/css.css') }}" rel="stylesheet">
        <link href="{{ URL::to('assets/pages/waves/css/waves.min.css') }}" rel="stylesheet">
        <link href="{{ URL::to('assets/fontawesome/css/all.css') }}" rel="stylesheet">
        <link href="{{ URL::to('assets/css/bootstrap/css/bootstrap.min.css') }}" rel="stylesheet">
        <link href="{{ URL::to('assets/icon/themify-icons/themify-icons.css') }}" rel="stylesheet">
        <link href="{{ URL::to('assets/css/style.css') }}" rel="stylesheet">
        <link href="{{ URL::to('css/select2.css') }}" rel="stylesheet">
        <link href="https://cdn.datatables.net/1.12.1/css/dataTables.bootstrap4.min.css" rel="stylesheet">


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
          <nav class="navbar header-navbar pcoded-header" style="background-color: #0077B8">
              <div class="navbar-wrapper">
                  <div class="navbar-logo ml-3" >
                      <a class="mobile-menu waves-effect waves-light" id="mobile-collapse" href="#!">
                          <i class="ti-menu"></i>
                      </a>
                      <a href="#" >
                        <img src="{{ URL::to('img/ndc.png')}}" width="30px;" style="border-radius: 20px;"  class="mb-1"><br>
                         <b>Gestión de Capacitaciones</b> 
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
                              <a href="#!" >
                                  <span style="font-size: 15px; padding:9px;" class="badge badge-pill badge-light">{{session('nombre')}}</span>
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
                      <div class="sidebar_toggle"><a href="#"><i class="icon-close icons"></i></a></div>
                      <div class="pcoded-inner-navbar main-menu">
                          <div class="pcoded-navigation-label" data-i18n="nav.category.navigation" style="color: #0077B8">Menú</div>
                          <ul class="pcoded-item pcoded-left-item">
                            <li class="{{ ! Route::is('home') ?: 'active' }}">
                                <a href="home" class="waves-effect waves-dark"  >
                                      <span  class="pcoded-micon"><i class="ti-home"></i></span>
                                      <span class="pcoded-mtext" data-i18n="nav.dash.main">Inicio</span>
                                      <span class="pcoded-mcaret"></span>
                                  </a>
                            </li>
                            <li class="{{ ! Route::is('registro') ?: 'active' }}">
                                <a href="registro" class="waves-effect waves-dark"  >
                                      <span  class="pcoded-micon"><i class="fas fa-file-alt"></i></span>
                                      <span class="pcoded-mtext" data-i18n="nav.dash.main">Reg. Planilla Verde</span>
                                      <span class="pcoded-mcaret"></span>
                                  </a>
                            </li>
                            <li class="{{ ! Route::is('prueba1') ?: 'active' }}">
                                <a href="prueba1" class="waves-effect waves-dark"  >
                                      <span  class="pcoded-micon"><i class="fas fa-check"></i></span>
                                      <span class="pcoded-mtext" data-i18n="nav.dash.main">Reg. Prueba N° 1</span>
                                      <span class="pcoded-mcaret"></span>
                                  </a>
                            </li>
                            <li class="{{ ! Route::is('prueba2') ?: 'active' }}">
                                <a href="prueba2" class="waves-effect waves-dark"  >
                                      <span  class="pcoded-micon"><i class="far fa-check-circle"></i></span>
                                      <span class="pcoded-mtext" data-i18n="nav.dash.main">Reg. Prueba N° 2</span>
                                      <span class="pcoded-mcaret"></span>
                                  </a>
                            </li>
                            <li class="{{ ! Route::is('prueba3') ?: 'active' }}">
                                <a href="prueba3" class="waves-effect waves-dark"  >
                                      <span  class="pcoded-micon"><i class="fas fa-check-circle"></i></span>
                                      <span class="pcoded-mtext" data-i18n="nav.dash.main">Reg. Prueba N° 3</span>
                                      <span class="pcoded-mcaret"></span>
                                  </a>
                            </li>
                            <li class="{{ ! Route::is('rezagados') ?: 'active' }}">
                                <a href="rezagados" class="waves-effect waves-dark"  >
                                      <span  class="pcoded-micon"><i class="fas fa-user-alt-slash"></i></span>
                                      <span class="pcoded-mtext" data-i18n="nav.dash.main">Rezagados</span>
                                      <span class="pcoded-mcaret"></span>
                                  </a>
                            </li>
                            <li class="{{ ! Route::is('correlativo') ?: 'active' }}">
                                <a href="correlativo" class="waves-effect waves-dark"  >
                                      <span  class="pcoded-micon"><i class="fas fa-list-ol"></i></span>
                                      <span class="pcoded-mtext" data-i18n="nav.dash.main">Reg. Correlativo</span>
                                      <span class="pcoded-mcaret"></span>
                                  </a>
                            </li>
                            <li class="{{ ! Route::is('desc_certificado') ?: 'active' }}">
                                <a href="desc_certificado" class="waves-effect waves-dark"  >
                                      <span  class="pcoded-micon"><i class="fas fa-download"></i></span>
                                      <span class="pcoded-mtext" data-i18n="nav.dash.main">Descargar Certificados</span>
                                      <span class="pcoded-mcaret"></span>
                                  </a>
                            </li>
                            <li class="{{ ! Route::is('desc_diplomas') ?: 'active' }}">
                                <a href="desc_diplomas" class="waves-effect waves-dark"  >
                                      <span  class="pcoded-micon"><i class="fas fa-download"></i></span>
                                      <span class="pcoded-mtext" data-i18n="nav.dash.main">Descargar Diplomas</span>
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
                                              <a href="#"> <i class="fa fa-home"></i> </a>
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
    <script src="https://cdn.datatables.net/1.12.1/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.12.1/js/dataTables.bootstrap4.min.js"></script>

@yield("script")
 
</body>

</html>
