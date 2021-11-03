<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="description" content="">
  <meta name="author" content="">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">

  <title>NDC - Reporteria
  </title>
  {{-- <link rel="icon" type="image/png" href="img/icono.png" sizes="32x32"> --}}
  <link rel="shortcut icon" href="assets/images/ndc.ico">


  <link rel="stylesheet" type="text/css" href="css/sb-admin-2.css">
  {{-- Fuente --}}
  <link rel="stylesheet" type="text/css" href="css/css.css?family=Source+Sans+Pro:200,200i,300,300i,400,400i,600,600i,700,700i,900,900i"> 
  <link href="{{asset('loginpu/css/coming-soon.css')}}" rel="stylesheet">
</head>
<body>
  <div class="overlay"></div>
 <video playsinline="playsinline" autoplay="autoplay" muted="muted" loop="loop">
    <source src="{{asset('loginpu/mp4/escritorio.mp4')}}" type="video/mp4">
  </video> 
  <div class="masthead">
    <div class="masthead-bg"></div>
    <div class="container h-100">
      <div class="row h-100">
        <div class="col-12 my-auto">
          <div class="masthead-content text-white py-5 py-md-0">
            <br><br>
          <img src="{{asset('loginpu/img/ndc.png')}}" class="responsive" style="width:220px; height:100px; margin-top:-380px;">

            <b><h1 class="mb-3" style="color: #56b5b1; font-family: Optima,Segoe,Segoe UI,Candara,Calibri,Arial,sans-serif;  ">Reporteria NDC</h1></b>
            <p class="mb-5"> &reg;</p>

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

             <div class="form-group">
              <input type="text" class="form-control form-control-user" name="codigo" placeholder="Codigo" required autocomplete='off'>
            </div>

             @if ($message = Session::get('error'))
             <div class="alert alert-danger">
                <b> {{ $message }} </b>
             </div>
            @endif
            @if ($message = Session::get('errorusuario'))
            <div class="alert alert-danger">
              <b> {{ $message }} </b>
            </div>
           @endif

           @if ($message = Session::get('errorcodigo'))
           <div class="alert alert-danger">
             <b> {{ $message }} </b>
           </div>
          @endif

             {{ Form::submit('Iniciar Sesión', ['class' => 'btn btn-secondary btn-block']) }}
             {!! Form::close() !!}
             <hr>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  
</body>
</html>
