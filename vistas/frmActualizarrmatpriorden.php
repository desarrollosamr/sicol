<?php
require "../clases/ConexionDatos.php";
extract ($_REQUEST);

//if (!isset($_SESSION['user']))
	//header("location:index.php?x=2");

$objConexion=Conectarse();

$productos = "select productosId, codigo, nombre from gc_productos order by nombre";
$rsproductos = $objConexion->query($productos);

$sql="select * from gc_materia_prima_por_orden where id = '$_REQUEST[Id]'";
$resultadoproduccion = mysqli_query($objConexion,$sql) or die('MySql Error' . mysql_error());
$produccion = $resultadoproduccion->fetch_object();
$ordena = $_REQUEST['progid'];

$nupu = "select count(*) as nmp from gc_materia_prima_por_orden where orden =" . $ordena;
$rnupu = $objConexion->query($nupu);
$pactual = $rnupu->fetch_object();
$pusi = $pactual->nmp + 1;


?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Formulario Actualizar Materia Prima Por Orden</title>
</head>

<body>
<form id="form1" name="form1" method="post" action="../controlador/comatpriorden.php" class="smart-green">
        <?php if ($_REQUEST[x]==1) { ?>
	<h1>Agregar materia prima</h1>
        <?php } else { ?>
	<h1>Actualizar materia prima</h1>
        <?php } ?>
   <label>
    	<span>Puesto</span>
        <?php if ($_REQUEST[x]==1) { ?>
		    <input name="puesto" type="number" step="1" id="puesto" value="<?php echo $pusi?>" size="10" placeholder="Cantidad"  readonly="readonly" required/>
        <?php } else { ?>
		    <input name="puesto" type="number" step="1" id="puesto" value="<?php echo $produccion->puesto?>" size="10" placeholder="Cantidad" required/>
        <?php } ?>
   </label>
   <label>
    	<span>Producto</span>
        <select name="producto" id="producto">
              <option value="0">Seleccione</option>
              <?php		 
              while ($producto = $rsproductos->fetch_object())
              {
                 if ($producto->codigo==$produccion->producto)
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
    	<span>Kilos</span>
	    <input name="cantidad" type="number" step="any" id="cantidad" value="<?php echo $produccion->cantidad?>" size="10" placeholder="Cantidad" required/>
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
   <input name="progid" type="hidden" value="<?php echo $ordena ?>"/>
   <input name="idprog" type="hidden" value="<?php echo $_REQUEST['idprog'] ?>"/>
</form>
</body>
</html>