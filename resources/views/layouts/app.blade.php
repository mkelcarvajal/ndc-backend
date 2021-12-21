<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />

    <title>Reporteria NDC</title>

    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta content="A fully featured admin theme which can be used to build CRM, CMS, etc." name="description" />
    <meta content="Coderthemes" name="author" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="csrf-token" content="{{ csrf_token() }}" />

    <!-- App favicon -->
    <link rel="shortcut icon" href="assets/images/ndc.ico">

    <!-- App css -->
    <link href="assets/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
    <link href="assets/css/icons.min.css" rel="stylesheet" type="text/css" />
    <link href="assets/css/app.min.css" rel="stylesheet" type="text/css" />
    <link href="css/select2.min.css" rel="stylesheet" type="text/css" />

    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet" type="text/css" />
</head>
    <body data-layout="topnav">
        <!-- Begin page -->
        <div class="wrapper">

            <!-- Topbar Start -->
            <div class="navbar navbar-expand flex-column flex-md-row navbar-custom">
                <div class="container-fluid">
                    <!-- LOGO -->

                    <a href="indexReportes" class="navbar-brand mr-0 mr-md-2 logo">
                        <span class="logo-lg">
                            <img src="assets/images/ndc.png" alt="" height="50" />
                            <span class="d-inline h5 ml-1 text-logo">Reporteria NDC</span>

                        </span>
                        <span class="logo-sm">
                            <img src="assets/images/logo.png" alt="" height="24">
                        </span>
                    </a>

                    <ul class="navbar-nav bd-navbar-nav flex-row list-unstyled menu-left mb-0">
                        <li class="">
                            <button class="button-menu-mobile open-left disable-btn">
                                <i data-feather="menu" class="menu-icon"></i>
                                <i data-feather="x" class="close-icon"></i>
                            </button>
                        </li>
                    </ul>

                    <ul class="navbar-nav flex-row ml-auto d-flex list-unstyled topnav-menu float-right mb-0">
                    

                        <li class="dropdown notification-list align-self-center profile-dropdown">
                            <a class="nav-link dropdown-toggle nav-user mr-0" data-toggle="dropdown" href="#" role="button"
                                aria-haspopup="false" aria-expanded="false">
                                <div class="media user-profile ">
                                    <i class="fas fa-user-circle fa-3x"></i>
                                        <div class="media-body text-left">
                                        <h6 class="pro-user-name ml-3 my-0">
                                            <span>{{Session::get('nombre')}}</span>
                                            <span class="pro-user-desc text-muted d-block mt-1">  {{Session::get('usuario')}} </span>
                                        </h6>
                                    </div>
                                    <span data-feather="chevron-down" class="ml-2 align-self-center"></span>
                                </div>
                            </a>
                            <div class="dropdown-menu profile-dropdown-items dropdown-menu-right">
                                <a href="logout" class="dropdown-item notify-item">
                                    <i data-feather="log-out" class="icon-dual icon-xs mr-2"></i>
                                    <span>Logout</span>
                                </a>
                            </div>
                        </li>
                    </ul>
                </div>

            </div>
            <!-- end Topbar -->

            <div class="topnav shadow-sm">
                <div class="container-fluid">
                    <nav class="navbar navbar-light navbar-expand-lg topbar-nav">
                        <div class="collapse navbar-collapse" id="topnav-menu-content">
                            <ul class="metismenu" id="menu-bar">
                                <li class="menu-title">Navigation</li>
                                {{-- <li>
                                    <a href="indexPrueba">
                                        <i data-feather="home"></i>
                                        <span class="badge badge-success float-right">1</span>
                                         <span> OTH Electrica </span>
                                    </a>
                                </li> --}}
                                <li>
                                    <a href="indexReportes">
                                        <i data-feather="file-text"></i>
                                        <span class="badge badge-success float-right">1</span>
                                         <span> Reportes </span>

                                    </a>
                                </li>
                                <li>
                                    <a onclick="sosia();" >
                                        <i data-feather="download"></i>
                                        <span class="badge badge-success float-right">1</span>
                                         <span> BD - Prueba Sosia </span>

                                    </a>
                                </li>
                            </ul>
                        </div>
                    </nav>
                </div>
            </div>
            <div class="content-page">
                <div class="content">
                <div class="container-fluid">
                    @yield("content")
                </div>
                </div>
            </div>

            <div id="overlay">
                <div class="cv-spinner">
                    <span class="spinner"></span>
                </div>
            </div>

                

                <!-- Footer Start -->
                <footer class="footer">
                    <div class="container-fluid">
                        <div class="row">
                            <div class="col-12">
                                2021 &copy; All Rights Reserved. Crafted with <i class='uil uil-heart text-danger font-size-12'></i> by <a href="#" target="_blank">NDC PERSSO GROUP</a>
                            </div>
                        </div>
                    </div>
                </footer>
                <!-- end Footer -->
            </div>
            <!-- ============================================================== -->
            <!-- End Page content -->
            <!-- ============================================================== -->
        </div>

        <!-- Vendor js -->
        <script src="assets/js/vendor.min.js"></script>
        
        <!-- App js -->
        <script src="assets/js/app.min.js"></script>
        <script src="js/select2.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/js/all.min.js"></script>
        <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>

        <script>
        function sosia(){
                let timerInterval
                Swal.fire({
                title: 'Espere mientras carga el archivo',
                html: 'Descargando BD Sosia en <b></b> Segundos.',
                timer: 10000,
                icon: 'info',
                timerProgressBar: true,
                allowOutsideClick: false,
                didOpen: () => {
                    window.location.href = "SosiaExcel";
                    Swal.showLoading()
                    const b = Swal.getHtmlContainer().querySelector('b')
                    timerInterval = setInterval(() => {
                    b.textContent = Swal.getTimerLeft()
                    }, 100)
                },
                }).then((result) => {
                /* Read more about handling dismissals below */
                if (result.dismiss === Swal.DismissReason.timer) {
                    console.log('I was closed by the timer')
                }
                })       
        }
   
        </script>

    @yield('script')
    </body>

</html>