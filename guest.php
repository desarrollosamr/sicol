<?php
extract($_REQUEST);
if (!isset($_REQUEST['x']))
    $x = 0;
?>
<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8" />
<title>BIENVENIDOS AL SITIO OFICIAL DEL SISTEMA DE INFORMACIÓN PARA CONTROL LOGÍSTICO</title>
<link href="css/formularios.css" rel="stylesheet" type="text/css" />
</head>

<body>
<header style="width:1000px; line-height:100%; margin:0 auto; padding-bottom:30px;"><img src="Imagenes/SICOL.jpg" width="986px" /></header>
<form id="form1" name="form1" method="get" action="controlador/coguest.php" class="smart-green">
  
  <h1>Iniciar Sesión</h1>
  <label>
  	<span>Usuario</span>
    <input name="login" type="text" id="login" size="40" required/>
  </label>
  <label>
  	<span>Cedula</span>
    <input name="password" type="password" id="pass" size="40" required/>
  </label>
  <label>
  	<input type="submit" name="button" id="button" value="Enviar" />
  </label>
</form>

<?php

if ($x == 1)
    echo "<br><div class='smart-green'><h1> Usuario no registrado con los datos ingresados, vuelva a intentar";
if ($x == 2)
    echo "<br><div class='smart-green'><h1 class='smart-green'> Deben Iniciar Sesión para poder ingresar a la Aplicación";
if ($x == 3)
    echo "<br><div class='smart-green'><h1 class='smart-green'> El Usuario ha cerrado la Sesión";
if ($x == 4)
    echo "<br><div class='smart-green'><h1 class='smart-green'> Su clave fue cambiada exitosamente. Por favor ingrese con su nueva contraseña";
?>


</body>
</html>
