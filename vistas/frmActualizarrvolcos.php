<?php
@session_start();
require "../clases/ConexionDatos.php";
$progid=$_POST['progid'];

//if (!isset($_SESSION['user']))
	//header("location:index.php?x=2");

$objConexion=Conectarse();
$nave = "select gc_programacion_recibo_productos.producto as producto, gc_productos.nombre as nombre from gc_programacion_recibo_productos left join gc_productos on gc_programacion_recibo_productos.producto = gc_productos.codigo where gc_programacion_recibo_productos.programacionid=" . $_POST['progid'];
$rsoperpro = $objConexion->query($nave);

$kilos = "select id, kilos from gc_presentacion order by kilos";
$rskilos = $objConexion->query($kilos);
$bodegas = "select id, nombre from gc_bodegas order by nombre";
$rsbodegas = $objConexion->query($bodegas);
$productos = "select productosId, nombre from gc_productos order by nombre";
$rsproductos = $objConexion->query($productos);
$motonaves = "select gc_programacion_recibo.motonave as nave, gc_programacion_recibo.fecha_arribo as llegada, gc_motonaves.nombre from gc_programacion_recibo left join gc_motonaves on gc_programacion_recibo.motonave = gc_motonaves.motonaveId  where gc_programacion_recibo.id=" . $_POST['progid'];
$rsmotonaves = $objConexion->query($motonaves);
$arribado = $rsmotonaves->fetch_object();
if ($arribado->llegada == '0000-00-00'){
	echo "<font size=3px color=red><b>Debe actualizar la fecha de arribo antes de digitar la llegada de los carros</b></font>";
	exit;
}

$sql="select * from gc_recibo_buques_contenedores where id = '$_REQUEST[Id]'";
$resultadovolcos = $objConexion->query($sql);
$volcos = $resultadovolcos->fetch_object();


?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Formulario Actualizar Recibo de Volcos y Contenedores</title>
<script type="text/javascript">
	function validarNumerosyLetras(e) { // 1
		tecla = (document.all) ? e.keyCode : e.which; // 2
		if (tecla==8) return true; // backspace
		if (tecla==9) return true; // backspace
		if (e.ctrlKey && tecla==86) { return true}; //Ctrl v
		if (e.ctrlKey && tecla==67) { return true}; //Ctrl c
		if (e.ctrlKey && tecla==88) { return true}; //Ctrl x
		if (tecla>=96 && tecla<=105) { return true;} //numpad

		patron = /[a-zA-Z0-9]/; // patron

		te = String.fromCharCode(tecla); 
		return patron.test(te); // prueba
	}
</script>
</head>

<body>
<form id="form1" name="form1" method="post" action="../controlador/covolcos.php" class="smart-green">
        <?php if ($_REQUEST[x]==1) { ?>
	<h1>Agregar recibo</h1>
        <?php } else { ?>
	<h1>Actualizar recibo</h1>
        <?php } ?>
   <label>
    	<span>Fecha</span>
	    <input name="fecha" type="date" id="fecha" value="<?php echo $volcos->fecha?>" min="<?php echo $arribado->llegada?>" size="10" placeholder="Fecha" required/>
   </label>
   <label>
    	<span>Producto</span>
        <select name="producto" id="producto">
              <?php		 
              while ($producto = $rsoperpro->fetch_object())
              {
                 if ($producto->producto==$volcos->producto)
                 {
                 ?> 			 
                    <option value="<?php echo $producto->producto?>" selected="selected"><?php echo $producto->nombre?></option>              
                 <?php
                 } else {
                 ?>
                    <option value="<?php echo $producto->producto?>"><?php echo $producto->nombre?></option>                
            <?php 
				 }
              }		  
            ?>          
        </select>
   </label>
   <label >
    	<span>Presentacion</span>
        <select name="presentacion" id="presentacion">
              <option value="0">Seleccione</option>
              <?php		 
              while ($presentacion = $rskilos->fetch_object())
              {
                 if ($presentacion->id==$volcos->presentacion)
                 {
                 ?> 			 
                    <option value="<?php echo $presentacion->id?>" selected="selected"><?php echo $presentacion->kilos?></option>              
                 <?php
                 } else {
                 ?>
                    <option value="<?php echo $presentacion->id?>"><?php echo $presentacion->kilos?></option>                
            <?php 
				 }
              }		  
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
                 if ($bodega->id==$volcos->bodega)
                 {
                 ?> 			 
                    <option value="<?php echo $bodega->id?>" selected="selected"><?php echo $bodega->nombre?></option>              
                 <?php
                 } else {
                 ?>
                    <option value="<?php echo $bodega->id?>"><?php echo $bodega->nombre?></option>                
            <?php 
				 }
              }		  
            ?>          
        </select>
   </label>
   <label >
    	<span>Tiquete</span>
	    <input name="tiquete" type="number" id="tiquete" value="<?php echo $volcos->tiquete?>" size="10" placeholder="Tiquete de bascula" required/>
   </label>
   <label>
		<span>Placas</span>
	    <input name="placas" type="text" id="placas" value="<?php echo $volcos->placas?>"  onkeypress="javascript:{this.value = this.value.toUpperCase(); }" onkeydown="return validarNumerosyLetras(event);" size="10" placeholder="Placas" required/>
   </label>
   <label>
    	<span>Peso de Origen</span>
	    <input name="porigen" type="text" id="porigen" value="<?php echo $volcos->peso_origen?>" size="10" placeholder="Peso de origen" required/>
   </label>
   <label>
    	<span>Peso de bascula</span>
	    <input name="pbascula" type="text" id="pbascula" value="<?php echo $volcos->peso_bascula?>" size="10" placeholder="Peso de bascula"/>
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