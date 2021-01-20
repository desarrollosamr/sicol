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
<script type="text/javascript">
	function verificarigualdad(){
		claven=document.getElementById("passn").value;
		clavenr=document.getElementById("passnr").value;
		if (claven==clavenr){
			document.getElementById("button").disabled=false;
		}else{
			alert("Las claves no coinciden");
			return false;
		}
	}
</script>
</head>

<body>
<header style="width:1000px; line-height:100%; margin:0 auto; padding-bottom:30px;"><img src="Imagenes/SICOL.jpg" width="986px" /></header>
<form id="form1" name="form1" method="post" action="controlador/cocamclave.php" class="smart-green">
  
  <h1>Cambiar contraseña</h1>
  <label>
  	<span>Clave anterior</span>
    <input name="passwordv" type="password" id="passwordv" size="40" required/>
  </label>
  <label>
  	<span>Nueva clave</span>
    <input name="passn" type="password" id="passn" size="40" required/>
  </label>
  <label>
  	<span>Confirmar nueva clave</span>
    <input name="passnr" type="password" id="passnr" size="40" required onblur="verificarigualdad()"/>
  </label>  
  <label>
  	<input type="submit" name="button" id="button" value="Enviar" disabled="disabled" />
  </label>
</form>

<?php

if ($x == 1)
    echo "<br><div class='smart-green'><h1> Usuario no registrado con los datos ingresados, vuelva a intentar";
if ($x == 2)
    echo "<br><div class='smart-green'><h1 class='smart-green'> Deben Iniciar Sesión para poder ingresar a la Aplicación";
if ($x == 3)
    echo "<br><div class='smart-green'><h1 class='smart-green'> El Usuario ha cerrado la Sesión";
?>


</body>
</html>
