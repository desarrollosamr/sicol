<?php
@session_start();
require "../clases/ConexionDatos.php";

//if (!isset($_SESSION['user']))
	//header("location:index.php?x=2");

$objConexion=Conectarse();

$sql="select * from gc_existencias where id = '$_REQUEST[Id]'";
$resultadoexistencias = $objConexion->query($sql);
$existencias = $resultadoexistencias->fetch_object();

$sqlbodegas="select * from gc_bodegas order by nombre";
$resultadobodegas = $objConexion->query($sqlbodegas);

$sqlproductos="select * from gc_productos order by nombre";
$resultadoproductos = $objConexion->query($sqlproductos);

$sqlpresenta="select * from gc_presentacion order by kilos";
$resultadopresenta = $objConexion->query($sqlpresenta);
$clie="select * from gc_clientes order by nombre";
$rsclie = $objConexion->query($clie);
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Formulario Actualizar Existencias</title>
</head>

<body>
<form id="form1" name="form1" method="post" action="../controlador/coexistencias.php" class="smart-green">
        <?php if ($_REQUEST[x]==1) { ?>
	<h1>Agregar existencias</h1>
        <?php } else { ?>
	<h1>Actualizar existencias</h1>
        <?php } ?>
   <label>
        <span>Producto</span>
        <select name="producto" id="producto">
            <option value="0">Seleccione</option>
            <?php
            while ($producto = $resultadoproductos->fetch_object())
            {
                if ($producto->productosId==$existencias->producto)
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
    	<span>Cliente</span>
        <select name="cliente" id="cliente">
              <option value="0">Seleccione</option>
              <?php		 
              while ($cliente = $rsclie->fetch_object())
              {
                 if ($cliente->nit==$existencias->cliente)
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
   <label>
        <span>Bodega</span>
        <select name="bodega" id="bodega">
            <option value="0">Seleccione</option>
            <?php
            while ($bodega = $resultadobodegas->fetch_object())
            {
                if ($bodega->Id==$existencias->bodega)
                {
                    ?>
                    <option value="<?php echo $bodega->Id?>" selected="selected"><?php echo $bodega->nombre?></option>
                <?php
                } else {
                    ?>
                    <option value="<?php echo $bodega->Id?>"><?php echo $bodega->nombre?></option>
                <?php
                }
            }		//cierra el Mientras
            ?>
        </select>
   </label>
   <label>
        <span>Lote</span>
        <input name="lote" type="number" step="any" id="lote" value="<?php echo $existencias->lote?>" size="10" placeholder="Lote"  required/>
   </label>
   <label>
        <span>Saldo</span>
        <input name="saldo" type="number" step="any" id="saldo" value="<?php echo $existencias->saldo?>" size="10" placeholder="Cantidad en Kilos"  required/>
   </label>
   <label>
    	<span>Fecha</span>
	    <input name="fecha" type="date" id="fecha" value="<?php echo $existencias->fecha?>" size="10" placeholder="Fecha" required/>
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