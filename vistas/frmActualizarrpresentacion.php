<?php
@session_start();
require "../clases/ConexionDatos.php";

//if (!isset($_SESSION['user']))
	//header("location:index.php?x=2");

$objConexion=Conectarse();
extract($_REQUEST);

$sql="select * from gc_presentacion where id = '$_REQUEST[Id]'";
$resultadomotonaves = $objConexion->query($sql);

$motonaves = $resultadomotonaves->fetch_object();



?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Formulario Actualizar Presentaciones</title>
</head>

<body>
<form id="form1" name="form1" method="post" action="../controlador/copresentacion.php" class="smart-green">
        <?php if ($_REQUEST[x]==1) { ?>
	<h1>Agregar presentacin</h1>
        <?php } else { ?>
	<h1>Actualizar presentacin</h1>
        <?php } ?>
   <label>
    	<span>Kilos</span>
	    <input name="kilos" type="text" id="kilos" value="<?php echo $motonaves->kilos?>" size="40" placeholder="Kilos" required/>
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