<?php
require "../clases/ConexionDatos.php";

//if (!isset($_SESSION['user']))
	//header("location:index.php?x=2");

$objConexion=Conectarse();
extract($_REQUEST);
if (isset($_SESSION['orden'])){
	$ordena = $_SESSION['orden'];
} else {
	$ordena = $_REQUEST['orden'];
}
if (isset($_SESSION['proveedor'])){
	$proveedora = $_SESSION['proveedor'];
} else {
	$proveedora = $_REQUEST['proveedor'];
}

$tarifas = "select id, nombre from gc_tarifas where proveedor=" . $proveedora . " order by nombre";
//$tarifas = "select id, nombre from gc_tarifas order by nombre";
$rstarifas = $objConexion->query($tarifas);
$operacion = "select gc_programacion_recibo.id as id, gc_motonaves.nombre as motonave from gc_programacion_recibo left join gc_motonaves on gc_programacion_recibo.motonave = gc_motonaves.motonaveId order by motonave";
$rsoperaciones = $objConexion->query($operacion);

$sql="select * from gc_ordenes_detalle where id = '$_REQUEST[Id]'";
$resultadoordenesdetalle = $objConexion->query($sql);

$ordenesdetalle = $resultadoordenesdetalle->fetch_object();


?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Formulario Actualizar Detalles de Solicitudes de Ordenes de Compra</title>
</head>

<body>
<form id="form1" name="form1" method="post" action="../controlador/coordenesdetalle.php" class="smart-green">
        <?php if ($_REQUEST[x]==1) { ?>
	<h1>Agregar detalle de orden de compra</h1>
        <?php } else { ?>
	<h1>Actualizar detalle de orden de compra</h1>
        <?php } ?>
   <label>
    	<span>Tarifa</span>
        <select name="tarifa" id="tarifa" onchange="muestra()">
              <option value="0">Seleccione</option>
              <?php		 
              while ($tarifa = $rstarifas->fetch_object())
              {
                 if ($tarifa->id==$ordenesdetalle->tarifa)
                 {
                 ?> 			 
                    <option value="<?php echo $tarifa->id?>" selected="selected"><?php echo $tarifa->nombre?></option>              
                 <?php
                 } else {
                 ?>
                    <option value="<?php echo $tarifa->id?>"><?php echo $tarifa->nombre?></option>                
            <?php 
				 }
              }		//cierra el Mientras  
            ?>          
        </select>
   </label>
   <label>
    	<span>Cantidad</span>
	    <input name="cantidad" type="text" id="cantidad" value="<?php echo $ordenesdetalle->cantidad?>" size="10" placeholder="Cantidad" required/>
   </label>
   <label >
    	<span>Operacion</span>
        <select name="operacion" id="operacion" >
              <option value="0">Seleccione</option>
              <?php		 
              while ($operacion = $rsoperaciones->fetch_object())
              {
                 if ($operacion->id==$ordenesdetalle->operacion)
                 {
                 ?> 			 
                    <option value="<?php echo $operacion->id?>" selected="selected"><?php echo $operacion->motonave?></option>              
                 <?php
                 } else {
                 ?>
                    <option value="<?php echo $operacion->id?>"><?php echo $operacion->motonave?></option>                
            <?php 
				 }
              }		//cierra el Mientras  
            ?>          
        </select>
   </label>
   <label>
   	<span>Detalle</span>
   	<textarea name="detalle" id="detalle" rows="2"></textarea>
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
   <input name="orden" type="hidden" value="<?php echo $ordena ?>"/>
   <input name="proveedor" type="hidden" value="<?php echo $proveedora ?>"/>
</form>
</body>
</html>