<?php
@session_start();
require "../clases/ConexionDatos.php";

//if (!isset($_SESSION['user']))
	//header("location:index.php?x=2");

$objConexion=Conectarse();

$proveedores = "select nit, razon_social from gc_proveedores order by razon_social";
$rsproveedores = $objConexion->query($proveedores);

$sql="select * from gc_ordenes where id = '$_REQUEST[Id]'";
$resultadoordenes = $objConexion->query($sql);

$ordenes = $resultadoordenes->fetch_object();



?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Formulario Actualizar Solicitudes de Ordenes de Compra</title>
</head>

<body>
<form id="form1" name="form1" method="post" action="../controlador/coordenes.php" class="smart-green">
        <?php if ($_REQUEST[x]==1) { ?>
	<h1>Agregar solicitud de orden de compra</h1>
        <?php } else { ?>
	<h1>Actualizar solicitud de orden de compra</h1>
        <?php } ?>
   <label>
    	<span>Proveedor</span>
        <select name="proveedor" id="proveedor" onchange="muestra()">
              <option value="0">Seleccione</option>
              <?php		 
              while ($proveedor = $rsproveedores->fetch_object())
              {
                 if ($proveedor->nit==$ordenes->proveedor)
                 {
                 ?> 			 
                    <option value="<?php echo $proveedor->nit?>" selected="selected"><?php echo $proveedor->razon_social?></option>              
                 <?php
                 } else {
                 ?>
                    <option value="<?php echo $proveedor->nit?>"><?php echo $proveedor->razon_social?></option>                
            <?php 
				 }
              }		//cierra el Mientras  
            ?>          
        </select>
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
   <input name="fecha" type="hidden" value="<?php echo new DateTime ?>" />
</form>
</body>
</html>