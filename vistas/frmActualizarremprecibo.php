<?php
@session_start();
require "../clases/ConexionDatos.php";

//if (!isset($_SESSION['user']))
	//header("location:index.php?x=2");

$objConexion=Conectarse();

$empaques = "select codigo, nombre from gc_empaques order by nombre";
$rsempaques = $objConexion->query($empaques);
$bodegas = "select id, nombre from gc_bodegas order by nombre";
$rsbodegas = $objConexion->query($bodegas);

$sql="select * from gc_emp_recibo where id = '$_REQUEST[Id]'";
$resultadoemprecibo = $objConexion->query($sql);

$emprecibo = $resultadoemprecibo->fetch_object();



?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Formulario Actualizar programacion de sellado</title>
</head>

<body>
<form id="form1" name="form1" method="post" action="../controlador/coemprecibo.php" class="smart-green">
        <?php if ($_REQUEST[x]==1) { ?>
	<h1>Agregar recibo de empaque</h1>
        <?php } else { ?>
	<h1>Actualizar recibo de empaque</h1>
        <?php } ?>
   <label>
    	<span>Fecha</span>
	    <input name="fecha" type="date" id="fecha" value="<?php echo $emprecibo->fecha?>" size="10" placeholder="Fecha" required/>
   </label>
   <label>
    	<span>Grado</span>
        <select name="grado" id="grado">
              <option value="0">Seleccione</option>
              <?php		 
              while ($empaque = $rsempaques->fetch_object())
              {
                 if ($empaque->codigo==$emprecibo->grado)
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
    	<span>Cantidad</span>
	    <input name="cantidad" type="text" id="cantidad" value="<?php echo $emprecibo->cantidad?>" size="10" placeholder="Cantidad" required/>
   </label>
   <label>
    	<span>Bodega</span>
        <select name="bodega" id="bodega">
              <option value="0">Seleccione</option>
              <?php		 
              while ($bodega = $rsbodegas->fetch_object())
              {
                 if ($bodega->id==$emprecibo->bodega)
                 {
                 ?> 			 
                    <option value="<?php echo $bodega->id?>" selected="selected"><?php echo $bodega->nombre?></option>              
                 <?php
                 } else {
                 ?>
                    <option value="<?php echo $bodega->id?>"><?php echo $bodega->nombre?></option>                
            <?php 
				 }
              }		//cierra el Mientras  
            ?>          
        </select>
   </label>
   <label>
    	<span>Origen</span>
	    <input name="origen" type="text" id="origen" value="<?php echo $emprecibo->origen?>" size="10" placeholder="Origen" required/>
   </label>
   <label>
    	<span>Observacion</span>
	    <input name="observacion" type="text" id="observacion" value="<?php echo $emprecibo->observacion?>" size="10" placeholder="Observacion"/>
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