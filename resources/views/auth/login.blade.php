<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">
      <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <title>NDC</title>
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
<img class="wave" src="img/fondo1.svg">
    <img class="wavecel" src="img/layer2.svg">
<div style="" class=''>
    
    <div class="container">
      <div class="img animate__animated animate__fadeInTopLeft">
        <img src="img/ndc.svg">
      </div>
      <div class="login-content animate__animated animate__fadeInTopRight">
        <form method="post" action="GetUser">
            {{ csrf_field() }}
            <img src="img/office_team.png">
          <h2 class="title" style="font-size:1.6rem;">Gestión de Capacitaciones</h2>
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
      NDC
    </div>

</body>
</html>