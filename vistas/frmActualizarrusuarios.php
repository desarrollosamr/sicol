<?php
@session_start();
require "../clases/ConexionDatos.php";

//if (!isset($_SESSION['user']))
	//header("location:index.php?x=2");

$objConexion=Conectarse();

$sql="select * from gc_usuarios where usuid = '$_REQUEST[Id]'";
$resultadousuarios = $objConexion->query($sql);

$usuarios = $resultadousuarios->fetch_object();



?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta charset=utf-8 />
<title>Formulario Actualizar usuarios</title>
</head>

<body>
<form id="form1" name="form1" method="post" action="../controlador/cousuarios.php" class="smart-green">
        <?php if ($_REQUEST[x]==1) { ?>
	<h1>Agregar usuarios</h1>
        <?php } else { ?>
	<h1>Actualizar usuarios</h1>
        <?php }
		if ($_REQUEST[x]==1) {?>
   <label>
    	<span>ID de usuario</span>
	    <input name="cedula" type="number" id="cedula" size="40" placeholder="ID de usuario" required/>
   </label>
   <label>
    	<span>Nombre de usuario</span>
	    <input name="nit" type="text" id="nit" value="<?php echo $usuarios->usuLogin?>" size="40" placeholder="Nombre de usuario" required/>
   </label>
        <?php } ?>        
   <label>
    	<span>Clave</span>
	    <input name="razonsocial" type="password" step="1" id="razonsocial" value="<?php echo $usuarios->usuPassword?>" size="40" placeholder="Clave" required/>
   </label>
   <label>
    	<span>Nivel</span>
	    <input name="nivel" type="number" step="1" id="nivel" value="<?php echo $usuarios->usuNivel?>" size="40" placeholder="Nivel" required/>
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