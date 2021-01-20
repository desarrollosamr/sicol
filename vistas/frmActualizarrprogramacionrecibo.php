<?php
require "../clases/ConexionDatos.php";

//if (!isset($_SESSION['user']))
	//header("location:index.php?x=2");
$objConexion=Conectarse();

$motonaves = "select * from gc_motonaves order by nombre";
$rsmotonaves = $objConexion->query($motonaves);

$sql="select * from gc_programacion_recibo where id = '$_REQUEST[Id]'";
$resultadoprogrecibo = $objConexion->query($sql);

$progrecibo = $resultadoprogrecibo->fetch_object();


?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Formulario Actualizar Programacion de recibo de materias primas</title>
</head>

<body>
<form id="form1" name="form1" method="post" action="../controlador/coprogrecibo.php" class="smart-green">
        <?php if ($_REQUEST[x]==1) { ?>
	<h1>Agregar programacion</h1>
        <?php } else { ?>
	<h1>Actualizar programacion</h1>
        <?php } ?>
   <label>
    	<span>Motonave</span>
        <select name="motonave" id="motonave">
              <option value="0">Seleccione</option>
              <?php		 
              while ($motonave = $rsmotonaves->fetch_object())
              {
                 if ($motonave->motonaveId==$progrecibo->motonave)
                 {
                 ?> 			 
                    <option value="<?php echo $motonave->motonaveId?>" selected="selected"><?php echo $motonave->nombre?></option>
                 <?php
                 } else {
                 ?>
                    <option value="<?php echo $motonave->motonaveId?>"><?php echo $motonave->nombre?></option>                
            <?php 
				 }
              }		//cierra el Mientras  
            ?>          
        </select>
   </label>
   <label>
   		<span>Fecha estimada de arribo</span>
	    <input name="fecha" type="date" id="fecha" value="<?php echo $progrecibo->fecha_estimada?>" size="10" placeholder="Fecha Estimada" required/>
   </label>
   <label>
   		<span>Fecha de arribo</span>
	    <input name="fechaarribo" type="date" id="fechaarribo" value="<?php echo $progrecibo->fecha_arribo?>" size="10" placeholder="Fecha Estimada"/>
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
</form>
</body>
</html>