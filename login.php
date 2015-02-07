<?php 
session_start();
require './config.php';
?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="LP">
    <link rel="shortcut icon" href="favicon.png">

    <title>Login | <?php echo PAGE_TITLE; ?></title>

    <!-- Bootstrap core CSS -->
    <link href="css/bootstrap.css" rel="stylesheet">

    <!-- Custom styles for this template -->
    <link href="css/signin.css" rel="stylesheet">

    <!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
      <script src="js/html5shiv.js"></script>
      <script src="js/respond.min.js"></script>
    <![endif]-->
  </head>

  <body>

    <div class="container">

        
      <form class="form-signin" action="inicio.php?option=login" method="post">
        <h2 class="form-signin-heading">Inicio de Sesi&oacute;n</h2>
        
        
        <p class="bg-danger"></p>
        <?php if(isset($_SESSION["error_message"])): ?>
        <div class="alert alert-danger alert-dismissable">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
            <?php echo $_SESSION["error_message"]; ?>
        </div>
        <?php endif; ?>
        
        <input type="text" name="user" class="form-control" placeholder="Usuario" autofocus>
        <br/>
        <input type="password" name="pass" class="form-control" placeholder="Contrase&ntilde;a">
        <label class="checkbox">
          <input type="checkbox" value="remember-me"> Recordarme
        </label>
        <button class="btn btn-lg btn-primary btn-block" type="submit">Iniciar Sesi&oacute;n</button>
        
      </form>

    </div> <!-- /container -->


    <!-- Bootstrap core JavaScript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
    <script type="text/javascript" src="js/jquery.js"></script>
    <script type="text/javascript" src="js/bootstrap.min.js"></script>
  </body>
</html>
