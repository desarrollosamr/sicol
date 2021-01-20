<?php
require "../clases/ConexionDatos.php";

//if (!isset($_SESSION['user']))
	//header("location:index.php?x=2");
$progid=$_POST['progid'];
$objConexion=Conectarse();

$productos = "select productosId, codigo, nombre from gc_productos order by nombre";
$rsproductos = $objConexion->query($productos);

$sql="select * from gc_programacion_recibo_productos where id = '$_REQUEST[Id]'";
$resultadoprogreciboproductos = $objConexion->query($sql);

$progreciboproductos = $resultadoprogreciboproductos->fetch_object();


?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Formulario Actualizar Productos Para Recibo</title>
</head>

<body>
<form id="form1" name="form1" method="post" action="../controlador/coprogreciboproductos.php" class="smart-green">
        <?php if ($_REQUEST[x]==1) { ?>
	<h1>Agregar productos</h1>
        <?php } else { ?>
	<h1>Actualizar productos</h1>
        <?php } ?>
   <label>
    	<span>Producto</span>
        <select name="producto" id="producto">
              <option value="0">Seleccione</option>
              <?php		 
              while ($producto = $rsproductos->fetch_object())
              {
                 if ($producto->codigo==$progreciboproductos->producto)
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
    	<span>Cantidad en kilos</span>
	    <input name="cantidad" type="number" step="any" id="cantidad" value="<?php echo $progreciboproductos->cantidad?>" size="10" placeholder="Cantidad" required/>
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
   <input name="progid" type="hidden" value="<?php echo $progid ?>"/>
</form>
</body>
</html>