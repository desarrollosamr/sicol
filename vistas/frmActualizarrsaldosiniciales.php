<?php
@session_start();
require "../clases/ConexionDatos.php";

//if (!isset($_SESSION['user']))
	//header("location:index.php?x=2");

$objConexion=Conectarse();

$sql="select * from gc_saldos_iniciales where id = '$_REQUEST[codigo]'";
$resultadoexistencias = $objConexion->query($sql);
$existencias = $resultadoexistencias->fetch_object();

$sqlbodegas="select * from gc_bodegas order by nombre";
$resultadobodegas = $objConexion->query($sqlbodegas);

$sqlproductos="select * from gc_productos order by nombre";
$resultadoproductos = $objConexion->query($sqlproductos);

$sqlpresenta="select * from gc_presentacion order by kilos";
$resultadopresenta = $objConexion->query($sqlpresenta);

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Formulario Actualizar Saldos Iniciales</title>
</head>

<body>
<form id="form1" name="form1" method="post" action="../controlador/cosaldosiniciales.php" class="smart-green">
        <?php if ($_REQUEST[x]==1) { ?>
	<h1>Agregar saldo inicial</h1>
        <?php } else { ?>
	<h1>Actualizar saldo inicial</h1>
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
        <input name="lote" type="text" id="lote" value="<?php echo $existencias->lote?>" size="10" placeholder="Lote"  required/>
   </label>
   <label>
        <span>Saldo kilos</span>
        <input name="saldok" type="number" id="saldok" value="<?php echo $existencias->saldo_inicial?>" size="10" placeholder="Cantidad en Kilos"  required/>
   </label>
   <label>
        <span>Saldo sacos</span>
        <input name="saldos" type="number" id="saldos" value="<?php echo $existencias->saldo_inicial_sacos?>" size="10" placeholder="Cantidad en Sacos"  required/>
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