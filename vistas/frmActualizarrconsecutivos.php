<?php
@session_start();
require "../clases/ConexionDatos.php";

//if (!isset($_SESSION['user']))
	//header("location:index.php?x=2");

$objConexion=Conectarse();

$sql="select * from gc_consecutivos where id = '$_REQUEST[Id]'";
$resultadoconsecutivos = $objConexion->query($sql);

$consecutivos = $resultadoconsecutivos->fetch_object();



?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta charset=utf-8 />
<title>Formulario Actualizar consecutivos</title>
</head>

<body>
<form id="form1" name="form1" method="post" action="../controlador/coconsecutivos.php" class="smart-green">
        <?php if ($_REQUEST[x]==1) { ?>
	<h1>Agregar consecutivos</h1>
        <?php } else { ?>
	<h1>Actualizar consecutivos</h1>
        <?php }
		if ($_REQUEST[x]==1) {?>
   <label>
    	<span>Tabla</span>
	    <input name="nit" type="text" id="nit" value="<?php echo $consecutivos->tabla?>" size="40" placeholder="NIT" required/>
   </label>
        <?php } ?>        
   <label>
    	<span>Consecutivo</span>
	    <input name="razonsocial" type="number" step="1" id="razonsocial" value="<?php echo $consecutivos->consecutivo?>" size="40" placeholder="Consecutivo" required/>
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