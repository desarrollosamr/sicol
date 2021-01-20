<?php
session_start();
if (!isset($_SESSION['user'])){
	header("location:../guest.php?x=2");
} else {
	$nivel=$_SESSION['nivel'];
	$usuario=$_SESSION['userid'];
}
//$nivel=$_SESSION['nivel'];
include"../clases/ConexionDatos.php";
$objConexion=Conectarse();
extract($_REQUEST);
date_default_timezone_set('America/Bogota');	
?>
<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8" />
<title>Documento sin título</title>
</head>

<body>
<?php 
if ($nivel!=4){
	?>
	<br>
	BIENVENIDOS AL SISTEMA DE INFORMACION PARA EL CONTROL DE OPERACIONES LOGISTICAS.
<?php } else {
	ob_clean();
	header("location: ../Plantilla/vistaPrincipal.php?pg=../vistas/paginador1.php&lista=../vistas/turnos_data.php?x=1&modulo=despachos");
}	
?>
</body>
</html>