<?php
@session_start();
require "../clases/ConexionDatos.php";

//if (!isset($_SESSION['user']))
	//header("location:index.php?x=2");

$objConexion=Conectarse();
extract($_REQUEST);

$sql="select * from gc_varios where Id = '$_REQUEST[Id]'";
$resultadovarios = $objConexion->query($sql);

$varios = $resultadovarios->fetch_object();



?>

<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta charset="utf-8" />
<title>Formulario Actualizar varios</title>
</head>

<body>
<form id="form1" name="form1" method="post" action="../controlador/covarios.php" class="smart-green">
        <?php if ($_REQUEST[x]==1) { ?>
	<h1>Agregar varios</h1>
        <?php } else { ?>
	<h1>Actualizar varios</h1>
        <?php } ?>
   <label>
    	<span>Fecha</span>
	    <input name="fecha" type="date" id="fecha" value="<?php echo $varios->fecha?>" size="10" placeholder="Fecha" required/>
   </label>
   <label>
    	<span>Detalle</span>
	    <input name="detalle" type="text" id="detalle" value="<?php echo $varios->detalle?>" size="40" placeholder="Detalle" required/>
   </label>
   <label>
        <span>&nbsp;</span> 
        <?php if ($_REQUEST[x]==1) { ?>
        <input type="submit" name="boton" id="boton" class="button" value="Agregar" /> 
        <?php } else { ?>
        <input type="submit" name="boton" id="boton" class="button" value="Actualizar" /> 
        <?php } ?>
        <input type="button" name="cancelar" id="cancelar" class="button" value="Cancelar" onclick="window.history.go(-1); return false;" />
   </label>    
   <input name="Id" type="hidden" value="<?php echo $_REQUEST['Id'] ?>" />
</form>
</body>
</html>