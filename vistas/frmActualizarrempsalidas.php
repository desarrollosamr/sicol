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
$productos = "select productosId, nombre from gc_productos order by nombre";
$rsproductos = $objConexion->query($productos);
$motonaves = "select gc_programacion_recibo.id as id, gc_programacion_recibo.fecha_arribo as arribo, gc_motonaves.nombre as motonave from gc_programacion_recibo left join gc_motonaves on gc_programacion_recibo.motonave = gc_motonaves.motonaveId order by gc_motonaves.nombre";
$rsmotonaves = $objConexion->query($motonaves);
$motivos = "select id, nombre from gc_emp_actividades order by nombre";
$rsmotivos = $objConexion->query($motivos);


$sql="select * from gc_emp_despacho where id = '$_REQUEST[Id]'";
$resultadoempdespacho = $objConexion->query($sql);

$empdespacho = $resultadoempdespacho->fetch_object();



?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Formulario Actualizar Despachos de Empaque</title>
<script language="javascript">
 function muestra(){
	 if (document.getElementById("motivo").value == "1") {
        if (document.getElementById("ord").style.display='none') {
			document.getElementById("ord").style.display='block'; 
			document.getElementById("oper").style.display='none'; 
		}
	} else if (document.getElementById("motivo").value == "2") {
        if (document.getElementById("oper").style.display='none') {
			document.getElementById("ord").style.display='none'; 
			document.getElementById("oper").style.display='block'; 
		}
	} else {
			document.getElementById("ord").style.display='none'; 
			document.getElementById("oper").style.display='none'; 
	}
 }
</script>
</head>

<body>
<form id="form1" name="form1" method="post" action="../controlador/coempsalidas.php" class="smart-green">
        <?php if ($_REQUEST[x]==1) { ?>
	<h1>Agregar despacho de empaque</h1>
        <?php } else { ?>
	<h1>Actualizar despacho de empaque</h1>
        <?php } ?>
   <label>
    	<span>Fecha</span>
	    <input name="fecha" type="date" id="fecha" value="<?php echo $empdespacho->fecha?>" size="10" placeholder="Fecha" required/>
   </label>
   <label>
    	<span>Motivo</span>
        <select name="motivo" id="motivo" onchange="muestra()">
              <option value="0">Seleccione</option>
              <?php		 
              while ($motivo = $rsmotivos->fetch_object())
              {
                 if ($motivo->id==$empdespacho->motivo)
                 {
                 ?> 			 
                    <option value="<?php echo $motivo->id?>" selected="selected"><?php echo $motivo->nombre?></option>              
                 <?php
                 } else {
                 ?>
                    <option value="<?php echo $motivo->id?>"><?php echo $motivo->nombre?></option>                
            <?php 
				 }
              }		//cierra el Mientras  
            ?>          
        </select>
   </label>
   <label>
    	<div><span>Turno</span></div>
        <div>
        <?php if ($empdespacho->turno == 1){ ?>
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
                 if ($empaque->codigo==$empdespacho->grado)
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
	    <input name="cantidad" type="text" id="cantidad" value="<?php echo $empdespacho->cantidad?>" size="10" placeholder="Cantidad" required/>
   </label>
   <label>
    	<span>Producto</span>
        <select name="producto" id="producto">
              <option value="0">Seleccione</option>
              <?php		 
              while ($producto = $rsproductos->fetch_object())
              {
                 if ($producto->productosId==$empdespacho->producto)
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
              while ($bodega = $rsbodegas->fetch_object())
              {
                 if ($bodega->id==$empdespacho->bodega)
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
   <div id="oper" style="display:none;">
   <label >
    	<span>Operacion</span>
        <select name="operacion" id="operacion">
              <option value="0">Seleccione</option>
              <?php		 
              while ($operacion = $rsmotonaves->fetch_object())
              {
				 $descripcion = $operacion->motonave . "-" . $operacion->arribo;
                 if ($operacion->id==$empdespacho->operacion)
                 {
                 ?> 			 
                    <option value="<?php echo $operacion->id?>" selected="selected"><?php echo $descripcion?></option>              
                 <?php
                 } else {
                 ?>
                    <option value="<?php echo $operacion->id?>"><?php echo $descripcion?></option>                
            <?php 
				 }
              }		//cierra el Mientras  
            ?>          
        </select>
   </label>
   </div>
   <div id="ord" style="display:none;">
   <label >
    	<span>Orden</span>
	    <input name="orden" type="text" id="orden" value="<?php echo $empdespacho->orden?>" size="10" placeholder="Orden"/>
   </label>
   </div>
   <label>
    	<span>Autoriza</span>
	    <input name="autoriza" type="text" id="autoriza" value="<?php echo $empdespacho->autoriza?>" size="10" placeholder="Autoriza" required/>
   </label>
   <label>
    	<span>Recibe</span>
	    <input name="recibe" type="text" id="recibe" value="<?php echo $empdespacho->recibe?>" size="10" placeholder="Recibe" required/>
   </label>
   <label>
    	<span>Observacion</span>
	    <input name="observacion" type="text" id="observacion" value="<?php echo $empdespacho->observacion?>" size="10" placeholder="Observacion"/>
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