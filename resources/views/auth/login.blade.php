<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">
      <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <title>HCC</title>
 <link
    rel="stylesheet"
    href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"
  />
<link rel="icon" href="assets/images/favicon.ico" type="image/x-icon">

    <!-- <link rel="stylesheet" href="https://pro.fontawesome.com/releases/v5.10.0/css/all.css"> -->
      <link rel="stylesheet" type="text/css" href="css/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="css/login.css">
      <link rel="stylesheet" type="text/css" href="css/spin.css">
    <style type="text/css">
      .footer {
        position: fixed;
        left: 0;
        bottom: 0;
        width: 100%;
        color: black;
        text-align: right;
      }
      input{
   text-align:center;
}
    </style>
</head>
<body>
<img class="wave" src="log/img/wave.png">
    <img class="wavecel" src="log/img/wavecel.png">
<div style="" class=''>
    
    <div class="container">
      <div class="img animate__animated animate__fadeInTopLeft">
        <img src="log/img/señor.png">
      </div>
      <div class="login-content animate__animated animate__fadeInTopRight">
        <form method="post" action="GetUser">
            {{ csrf_field() }}
            <img src="log/img/circlelog.png">
          <h2 class="title" style="font-size:1.6rem;">Entrega de turno Clínico</h2>
          <!-- <a href="#">Forgot Password?</a> -->
            <input class="form-control mb-2" name="userin" placeholder="Usuario" style="border-radius: 25px;">
            <input class="form-control" name="passin" type="password" placeholder="Contraseña" style="border-radius: 25px;">
            <button type="submit" class="btn" type="button"  id="btncarga">Ingresar</button>
          <!-- <br> -->
          <center>
            <div  id="spinlod" style="display: none;">
            <div class="spinner-box">
              <div class="configure-border-1">  
                <div class="configure-core"></div>
              </div>  
              <div class="configure-border-2">
                <div class="configure-core"></div>
              </div> 
            </div>
            </div>
          </center>
        </form>
        
      </div>
    </div>
</div>
    <div class="footer">
      Dpto.Informatica.Calama.
    </div>
    <script>
      function claveunica(){
        @php $token = md5(uniqid(mt_rand(), true)); @endphp
        var link = document.getElementById('btncarga');
        link.style.display = 'none';
        var link = document.getElementById('spinlod');
        link.style.display = 'block';
        uri="http%3A%2F%2F10.67.1.36%2Fpacv2%2Fpublic%2Fclaveunica";
        // window.location.href="https://accounts.claveunica.gob.cl/openid/authorize?client_id=7034b8a8a0fb447393ed547bc0aa8c99&redirect_uri=http%3a%2f%2f10.67.1.36%2fpacv2%2fpublic%2fclaveunica&response_type=code&scope=openid run name email&state={{ $token }}";
        // window.location.href="https://accounts.claveunica.gob.cl/openid/authorize/?client_id=7034b8a8a0fb447393ed547bc0aa8c99&response_type=code&scope=openid run name&redirect_uri="+uri+"&state={{$token}}"
		window.location.href="https://accounts.claveunica.gob.cl/openid/authorize?client_id=7034b8a8a0fb447393ed547bc0aa8c99&redirect_uri=https%3a%2f%2fpacientes.hospitalcalama.cl%2fpublic%2fclaveunica&response_type=code&scope=openid run name email&state={{ $token }}";

      }
    </script>
</body>
</html>