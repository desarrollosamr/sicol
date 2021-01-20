<?php
@session_start();
require "../clases/ConexionDatos.php";

//if (!isset($_SESSION['user']))
	//header("location:index.php?x=2");

$objConexion=Conectarse();

$sql="select * from gc_proveedores where nit = '$_REQUEST[Id]'";
$resultadoproveedores = $objConexion->query($sql);

$proveedores = $resultadoproveedores->fetch_object();



?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Formulario Actualizar proveedores</title>
</head>

<body>
<form id="form1" name="form1" method="post" action="../controlador/coproveedores.php" class="smart-green">
        <?php if ($_REQUEST[x]==1) { ?>
	<h1>Agregar proveedores</h1>
        <?php } else { ?>
	<h1>Actualizar proveedores</h1>
        <?php }
		if ($_REQUEST[x]==1) {?>
   <label>
    	<span>NIT</span>
	    <input name="nit" type="text" id="nit" value="<?php echo $proveedores->nit?>" size="40" placeholder="NIT" required/>
   </label>
        <?php } ?>
   <label>
    	<span>Razon Social</span>
	    <input name="razonsocial" type="text" id="razonsocial" value="<?php echo $proveedores->razon_social?>" size="40" placeholder="Razon social" required/>
   </label>
   <label>
    	<span>Contacto</span>
	    <input name="contacto" type="text" id="contacto" value="<?php echo $proveedores->contacto?>" size="40" placeholder="Nombre del contacto" required/>
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