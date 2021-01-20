<?php
require "../clases/ConexionDatos.php";

//if (!isset($_SESSION['user']))
	//header("location:index.php?x=2");

$objConexion=Conectarse();

$productos = "select productosId, codigo, nombre from gc_productos order by nombre";
$rsproductos = $objConexion->query($productos);
$listaempaques = "select codigo, nombre from gc_empaques order by nombre";
$rsempaques = mysqli_query($objConexion,$listaempaques) or die('MySql Error' . mysql_error());

$sql="select * from gc_produccion where id = '$_REQUEST[Id]'";
$resultadoproduccion = $objConexion->query($sql);

$produccion = $resultadoproduccion->fetch_object();


?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Formulario Actualizar Produccion</title>
</head>

<body>
<form id="form1" name="form1" method="post" action="../controlador/coactas.php" class="smart-green">
        <?php if ($_REQUEST[x]==1) { ?>
	<h1>Agregar acta</h1>
        <?php } else { ?>
	<h1>Actualizar acta</h1>
        <?php } ?>
   <label>
   		<span>Fecha</span>
	    <input name="fecha" type="date" id="fecha" value="<?php echo $produccion->fecha?>" size="10" placeholder="Fecha" required/>
   </label>
   <label>
   		<span>Acta</span>
	    <input name="acta" type="number" id="acta" value="<?php echo $produccion->acta?>" size="10" placeholder="Acta" required/>
   </label>
   <label>
   <label >
    	<span>Orden</span>
	    <input name="orden" type="number" id="orden" value="<?php echo $produccion->orden?>" size="10" placeholder="Orden" required/>
   </label>
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
    	<span>Cantidad a reportar</span>
	    <input name="cantidad" type="number" step="any" id="cantidad" value="<?php echo $produccion->cantidad_a_reportar?>" size="10" placeholder="Cantidad" required/>
   </label>
   <label>
    	<div><span>Turno</span></div>
        <div>
        <?php if ($produccion->turno == 1){ ?>
	        <input type="radio" id="turno1" name="turno" value="1" checked="checked">
        <?php }else{ ?>
	        <input type="radio" id="turno1" name="turno" value="1">
		<?php } ?>
           	<label for="turno1"><img src="../Imagenes/1.png" /></label>
        <?php if ($empdespacho->turno == 2){ ?>
	        <input type="radio" id="turno2" name="turno"value="2" checked="checked">
        <?php }else{ ?>
	        <input type="radio" id="turno2" name="turno"value="2">
		<?php } ?>
           	<label for="turno2"><img src="../Imagenes/2.png" /></label>
        <?php if ($empdespacho->turno == 3){ ?>
        	<input type="radio" id="turno3" name="turno" value="3" checked="checked">
        <?php }else{ ?>
        	<input type="radio" id="turno3" name="turno" value="3">
		<?php } ?>
           <label for="turno3"><img src="../Imagenes/3.png" /></label>
        </div>
   </label>
   <label>
    	<span>Grado</span>
        <select name="grado" id="grado">
              <option value="0">Seleccione</option>
              <?php		 
              while ($empaque = $rsempaques->fetch_object())
              {
                 if ($empaque->codigo==$produccion->empaque)
                 {
                 ?> 			 
                    <option value="<?php echo $empaque->codigo?>" selected="selected"><?php echo $empaque->nombre?></option>              
                 <?php
                 } else {
                 ?>
                    <option value="<?php echo $empaque->codigo?>"><?php echo $empaque->nombre?></option>                
            <?php 
				 }
              }  
            ?>          
        </select>
   </label>
   <label>
    	<span>Cantidad de empaques usados</span>
	    <input name="empaques" type="number" step="1" id="empaques" value="<?php echo $produccion->empaques_cantidad?>" size="10" placeholder="Cantidad de empaques" required/>
   </label>
   <label>
    	<span>Observacion</span>
	    <input name="observacion" type="text" id="observacion" value="<?php echo $produccion->observaciones?>" size="10" placeholder="Observacion"/>
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