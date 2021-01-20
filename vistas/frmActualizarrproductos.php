<?php
@session_start();
require "../clases/ConexionDatos.php";

//if (!isset($_SESSION['user']))
	//header("location:index.php?x=2");

$objConexion=Conectarse();

$sql="select * from gc_productos where productosId = '$_REQUEST[Id]'";
$resultadoproductos = $objConexion->query($sql);
$productos = $resultadoproductos->fetch_object();
$clientes = "select * from gc_clientes order by nombre";
$rsclientes = $objConexion->query($clientes);
$presentaciones = "select * from gc_presentacion order by kilos";
$rspresentaciones = $objConexion->query($presentaciones);

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Formulario Actualizar Productos</title>
</head>

<body>
<form id="form1" name="form1" method="post" action="../controlador/coproductos.php" class="smart-green">
        <?php if ($_REQUEST[x]==1) { ?>
	<h1>Agregar productos</h1>
        <?php } else { ?>
	<h1>Actualizar productos</h1>
        <?php } ?>
   <label>
    	<span>Codigo</span>
	    <input name="codigo" type="text" id="codigo" value="<?php echo $productos->codigo?>" size="10" placeholder="Codigo del empaque" required/>
   </label>
   <label>
    	<span>Nombre</span>
	    <input name="nombre" type="text" id="nombre" value="<?php echo $productos->nombre?>" size="40" placeholder="Nombre del producto" required/>
   </label>
   <label>
		<span>Presentacion</span>
	    <select name="presentacion" id="presentacion">
	          <option value="0">Seleccione</option>
	          <?php		 
	          while ($presentacion = $rspresentaciones->fetch_object())
	          {
	             if ($presentacion->id==$productos->presentacion)
	             {
	             ?> 			 
	                <option value="<?php echo $presentacion->id?>" selected="selected"><?php echo $presentacion->kilos?></option>   	     
	             <?php
	             } else {
	             ?>
	                <option value="<?php echo $presentacion->id?>"><?php echo $presentacion->kilos?></option>              	            			 <?php 
				 }
	           }		//cierra el Mientras  
	        ?>          
	    </select>
   </label>

   <label>
		<span>Cliente</span>
	    <select name="cliente" id="cliente">
	          <option value="0">Seleccione</option>
	          <?php		 
	          while ($cliente = $rsclientes->fetch_object())
	          {
	             if ($cliente->nit==$productos->cliente)
	             {
	             ?> 			 
	                <option value="<?php echo $cliente->nit?>" selected="selected"><?php echo $cliente->nombre?></option>   	             <?php
	             } else {
	             ?>
	                <option value="<?php echo $cliente->nit?>"><?php echo $cliente->nombre?></option>              	            			 <?php 
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
</form>
</body>
</html>