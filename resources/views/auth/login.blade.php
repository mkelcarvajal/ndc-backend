<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="description" content="">
  <meta name="author" content="">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>OHT
  </title>
  <link rel="icon" type="image/png" href="img/icono.png" sizes="32x32">
  <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
  <link rel="stylesheet" type="text/css" href="css/sb-admin-2.css">
  {{-- Fuente --}}
  <link rel="stylesheet" type="text/css" href="css/css.css?family=Source+Sans+Pro:200,200i,300,300i,400,400i,600,600i,700,700i,900,900i"> 
  <link href="{{asset('loginpu/css/coming-soon.min.css')}}" rel="stylesheet">
</head>
<body>
  <div class="overlay"></div>
 <video playsinline="playsinline" autoplay="autoplay" muted="muted" loop="loop">
    <source src="{{asset('loginpu/mp4/bg.mp4')}}" type="video/mp4">
  </video> 
  <div class="masthead">
    <div class="masthead-bg"></div>
    <div class="container h-100">
      <div class="row h-100">
        <div class="col-12 my-auto">
          <div class="masthead-content text-white py-5 py-md-0">
            <br><br>
          <img src="{{asset('loginpu/img/hcc.png')}}" class="responsive" style="width:150px; height:80px; margin-top:-380px;">
            <h1 class="mb-3">OHT</h1>
            <p class="mb-5">Pruebas &reg;</p>
            <main class="py-4" id="vueValidate">
              @if(session('info'))
            <div class="container">
              <div class="row">
                <div class="col">
                  <div role="alert" class="alert alert-theme alert-danger alert-dismissible">
                    <button type="button" data-dismiss="alert" aria-label="Close" class="close"><span aria-hidden="true" class="fa fa-times-circle"></span></button>
                    <div class="icon"></div>
                    <div class="message"><strong>¡ERROR!</strong>  {{ session('info') }}</div>
                  </div>
                </div>
              </div>
            </div>
              @endif
            </main>
            {!! Form::open(['route' => 'login', 'method' => 'POST']) !!}
             <div class="form-group">
               <input type="text" class="form-control form-control-user" name="user" placeholder="Ingresa tu usuario" required autofocus >
             </div>
             <div class="form-group">
               <input type="password" class="form-control form-control-user" name="password" placeholder="Ingresa tu Contraseña" required autocomplete='off'>
             </div>
             {{ Form::submit('Iniciar Sesión', ['class' => 'btn btn-secondary btn-block']) }}
             {!! Form::close() !!}
             <hr>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  <script src="{{ asset('js/app.js') }}"></script>
</body>
</html>
