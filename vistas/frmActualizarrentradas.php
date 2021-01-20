<?php
@session_start();
require "../clases/ConexionDatos.php";

//if (!isset($_SESSION['user']))
	//header("location:index.php?x=2");

$objConexion=Conectarse();

$bodegas = "select id, nombre from gc_bodegas order by nombre";
$rsbodegas = $objConexion->query($bodegas);
$productos = "select * from gc_productos order by nombre";
$rsproductos = $objConexion->query($productos);
$clie="select * from gc_clientes order by nombre";
$rsclie = $objConexion->query($clie);
$sql="select * from gc_ensaque where id = '$_REQUEST[Id]'";
$resultadoentradas = $objConexion->query($sql);
$entradas = $resultadoentradas->fetch_object();
?>

<html>
<head>
<meta "charset=utf-8" />
<title>Formulario Actualizar Entradas de Productos</title>
</head>

<body>
<form id="form1" name="form1" method="post" action="../controlador/coentradas.php" class="smart-green">
        <?php if ($_REQUEST[x]==1) { ?>
	<h1>Agregar entrada de producto</h1>
        <?php } else { ?>
	<h1>Actualizar entrada de producto</h1>
        <?php } ?>
   <label>
    	<span>Fecha</span>
	    <input name="fecha" type="date" id="fecha" value="<?php echo $entradas->fecha?>" size="10" placeholder="Fecha" required/>
   </label>
   <label >
    	<span>Tiquete</span>
	    <input name="tiquete" type="number"  step="1" id="tiquete" value="<?php echo $entradas->tiquete?>" size="10" placeholder="tiquete"/>
   </label>      
   <label>
    	<span>Producto</span><div id="respuesta"></div>
        <select name="producto" id="producto">
              <option value="0">Seleccione</option>
              <?php		 
              while ($producto = $rsproductos->fetch_object())
              {
                 if ($producto->productosId==$entradas->producto)
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
    	<span>Cantidad en Kilos</span>
	    <input name="cantidadtm" type="number" step="1" id="cantidadtm" value="<?php echo $entradas->cantidad_tm?>" size="10" placeholder="Cantidad en Kilos"  required/>
   </label>
   <label>
    	<span id="sacos">Cantidad en sacos o big bags</span>
	    <input name="cantidadsacos" type="number" step="1" id="cantidadsacos" value="<?php echo $entradas->cantidad_sacos?>" size="10" placeholder="Cantidad en sacos" required/>
   </label>
   <label>
    	<span>Bodega</span>
        <select name="bodega" id="bodega">
              <option value="0">Seleccione</option>
              <?php		 
              while ($bodega = $rsbodegas->fetch_object())
              {
                 if ($bodega->id==$entradas->bodega)
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
    	<span>Cliente</span>
        <select name="cliente" id="cliente">
              <option value="0">Seleccione</option>
              <?php		 
              while ($cliente = $rsclie->fetch_object())
              {
                 if ($cliente->nit==$entradas->cliente)
                 {
                 ?> 			 
                    <option value="<?php echo $cliente->nit?>" selected="selected"><?php echo $cliente->nombre?></option>              
                 <?php
                 } else {
                 ?>
                    <option value="<?php echo $cliente->nit?>"><?php echo $cliente->nombre?></option>                
            <?php 
				 }
              }		//cierra el Mientras  
            ?>          
        </select>
   </label>
   <label >
    	<span>Orden</span>
	    <input name="orden" type="number"  step="1" id="orden" value="<?php echo $entradas->orden?>" size="10" placeholder="Orden"/>
   </label>   
   <label>
    	<span>Lote</span>
	    <input name="lote" type="text" id="lote" value="<?php echo $entradas->lote?>" size="10" placeholder="Lote" required/>
   </label>
   <label>
        <span>&nbsp;</span> 
        <?php if ($_REQUEST[x]==1) { ?>
        <input type="submit" name="boton" id="boton" class="button" value="Agregar" /> 
        <?php } else { ?>
        <input type="submit" name="boton" id="boton" class="button" value="Actualizar" /> 
        <?php } ?>
        <input type="button" name="cancelar" id="cancelar" class="button" value="Cancelar" onClick="window.history.go(-1); return false;" />
   </label>    
   <input name="Id" type="hidden" value="<?php echo $_REQUEST['Id'] ?>" />
   <input name="progid" type="hidden" value="<?php echo $_REQUEST['progid'] ?>" />
   <input name="despachoid" type="hidden" value="<?php echo $_REQUEST['codigo'] ?>" />
</form>
</body>
</html>