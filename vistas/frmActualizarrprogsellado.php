<?php
@session_start();
require "../clases/ConexionDatos.php";

//if (!isset($_SESSION['user']))
	//header("location:index.php?x=2");

$objConexion=Conectarse();
$empaques = "select codigo, nombre from gc_empaques order by nombre";
$rsempaques = $objConexion->query($empaques);
$productos = "select productosId,codigo, nombre from gc_productos order by nombre";
$rsproductos = $objConexion->query($productos);
$presentaciones = "select id, kilos from gc_presentacion order by kilos";
$rspresentaciones = $objConexion->query($presentaciones);

$sql="select * from gc_produccion_programacion where id = '$_REQUEST[Id]'";
$resultadoprogsellado = $objConexion->query($sql);

$progsellado = $resultadoprogsellado->fetch_object();



?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Formulario Actualizar programacion de sellado</title>
</head>

<body>
<form id="form1" name="form1" method="post" action="../controlador/coprogsellado.php" class="smart-green">
        <?php if ($_REQUEST[x]==1) { ?>
	<h1>Agregar programacion de sellado</h1>
        <?php } else { ?>
	<h1>Actualizar programacion de sellado</h1>
        <?php } ?>
   <label>
    	<span>Fecha</span>
	    <input name="fecha" type="date" id="fecha" value="<?php echo $progsellado->fecha?>" size="10" placeholder="Fecha" required/>
   </label>
   <label>
    	<span>Orden</span>
	    <input name="orden" type="text" id="orden" value="<?php echo $progsellado->orden?>" size="10" placeholder="Orden" required/>
   </label>
   <label>
    	<span>Presentacion</span>
        <select name="presentacion" id="presentacion">
              <option value="0">Seleccione</option>
              <?php		 
              while ($presenta = $rspresentaciones->fetch_object())
              {
                 if ($presenta->id==$progsellado->presentacion)
                 {
                 ?> 			 
                    <option value="<?php echo $presenta->id?>" selected="selected"><?php echo $presenta->kilos?></option>              
                 <?php
                 } else {
                 ?>
                    <option value="<?php echo $presenta->id?>"><?php echo $presenta->kilos?></option>                
            <?php 
				 }
              }		//cierra el Mientras  
            ?>          
        </select>
   </label>
   <label>
    	<span>Grado</span>
        <select name="grado" id="grado">
              <option value="0">Seleccione</option>
              <?php		 
              while ($empaque = $rsempaques->fetch_object())
              {
                 if ($empaque->codigo==$progsellado->grado)
                 {
                 ?> 			 
                    <option value="<?php echo $empaque->codigo?>" selected="selected"><?php echo $empaque->nombre?></option>              
                 <?php
                 } else {
                 ?>
                    <option value="<?php echo $empaque->codigo?>"><?php echo $empaque->nombre?></option>                
            <?php 
				 }
              }		//cierra el Mientras  
            ?>          
        </select>
   </label>
   <label>
    	<span>Producto</span>
        <select name="producto" id="producto">
              <option value="0">Seleccione</option>
              <?php		 
              while ($producto = $rsproductos->fetch_object())
              {
                 if ($producto->codigo==$progsellado->producto)
                 {
                 ?> 			 
                    <option value="<?php echo $producto->codigo?>" selected="selected"><?php echo $producto->nombre?></option>              
                 <?php
                 } else {
                 ?>
                    <option value="<?php echo $producto->codigo?>"><?php echo $producto->nombre?></option>                
            <?php 
				 }
              }		//cierra el Mientras  
            ?>          
        </select>
   </label>
   <label>
    	<span>Solicitante</span>
	    <input name="solicitante" type="text" id="solicitante" value="<?php echo $progsellado->solicitante?>" size="10" placeholder="Solicitante" required/>
   </label>
   <label>
    	<span>Cantidad</span>
	    <input name="cantidad" type="text" id="cantidad" value="<?php echo $progsellado->cantidad?>" size="10" placeholder="Cantidad" required/>
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