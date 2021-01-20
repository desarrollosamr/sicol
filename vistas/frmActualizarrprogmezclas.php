<?php
@session_start();
require "../clases/ConexionDatos.php";

//if (!isset($_SESSION['user']))
	//header("location:index.php?x=2");

$objConexion=Conectarse();
$productos = "select productosId,codigo, nombre from gc_productos order by nombre";
$rsproductos = $objConexion->query($productos);
$sql="select * from gc_produccion_programacion where id = '$_REQUEST[Id]'";
$resultadoprogmezclas = $objConexion->query($sql);
$progmezclas = $resultadoprogmezclas->fetch_object();
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Formulario Actualizar programacion de mezclas</title>
</head>
<body>
<form id="form1" name="form1" method="post" action="../controlador/coprogmezclas.php" class="smart-green">
        <?php if ($_REQUEST[x]==1) { ?>
	<h1>Agregar programacion de mezclas</h1>
        <?php } else { ?>
	<h1>Actualizar programacion de mezclas</h1>
        <?php } ?>
   <label>
    	<span>Fecha</span>
   <?php if ($_REQUEST[x]==1) { ?>
	    <input name="fecha" type="date" id="fecha" value="<?php echo date('Y-m-d')?>" size="10" placeholder="Fecha" re
   <?php } else { ?>
	    <input name="fecha" type="date" id="fecha" value="<?php echo $progmezclas->fecha?>" size="10" placeholder="Fecha" required/>
        <?php } ?>
   </label>
   <label>
    	<span>Producto</span>
        <select name="producto" id="producto">
              <option value="0">Seleccione</option>
              <?php		 
              while ($producto = $rsproductos->fetch_object())
              {
                 if ($producto->productosId==$progmezclas->producto)
                 {
                 ?> 			 
                    <option value="<?php echo $producto->productosId?>" selected="selected"><?php echo $producto->nombre?></option>              
                 <?php
                 } else {
                 ?>
                    <option value="<?php echo $producto->productosId?>"><?php echo $producto->nombre?></option>                
            <?php 
				 }
              }		//cierra el Mientras  
            ?>          
        </select>
   </label>
   <label>
    	<span>TM Programadas</span>
	    <input name="tmprog" type="number" step="any" id="tmprog" value="<?php echo $progmezclas->tm_programadas?>" size="10" placeholder="TM Programadas" required/>
   </label>
   <label>
    	<span>TM Producidas</span>
   <?php if ($_REQUEST[x]==1) { ?>
	    <input name="tmprod" type="number" step="any" id="tmprod" value="0" size="10" placeholder="TM Producidas" required/>
   <?php } else { ?>
	    <input name="tmprod" type="number" step="any" id="tmprod" value="<?php echo $progmezclas->tm_producidas?>" size="10" placeholder="TM Producidas" required/>
   <? 	 } ?>
   </label>
   <label>
    	<span>Orden</span>
	    <input name="orden" type="number" step="1" id="orden" value="<?php echo $progmezclas->orden?>" size="10" placeholder="TM Producidas" required/>
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