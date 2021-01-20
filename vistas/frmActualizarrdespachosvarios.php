<?php
@session_start();
require "../clases/ConexionDatos.php";

//if (!isset($_SESSION['user']))
	//header("location:index.php?x=2");

$objConexion=Conectarse();
extract($_REQUEST);

$sql="select * from gc_despachos_recibos_varios where Id = '$_REQUEST[Id]'";
$resultdespa = $objConexion->query($sql);

$prodespacho = $resultdespa->fetch_object();
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Formulario Actualizar entradas y salidas varias</title>
  <!--[if lt IE 9]>
  <script src="http://html5shiv.googlecode.com/svn/trunk/html5.js"></script>
  <![endif]-->
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
<form id="form1" name="form1" method="post" action="../controlador/codespachosvarios.php" class="smart-green">
        <?php if ($_REQUEST[x]==1) { ?>
	<h1>Agregar entradas y salidas varias</h1>
        <?php } else { ?>
	<h1>Actualizar entradas y salidas varias</h1>
        <?php } ?>
   <label>
    	<div><span>Tipo de movimiento</span></div>
        <div>
        <?php if ($prodespacho->tipo == 1){ ?>
	        <input type="radio" id="entradas" name="tipo" value="1" checked="checked">
        <?php }else{ ?>
	        <input type="radio" id="entradas" name="tipo" value="1">
		<?php } ?>
           	<label for="entradas"><img src="../Imagenes/in.png" /></label>
        <?php if ($prodespacho->tipo == 2){ ?>
	        <input type="radio" id="salidas" name="tipo" value="2" checked="checked">
        <?php }else{ ?>
	        <input type="radio" id="salidas" name="tipo" value="2">
		<?php } ?>
           	<label for="salidas"><img src="../Imagenes/out.png" /></label>
        </div>
   </label>
   <label>
    	<span>Fecha</span>
	    <input name="fecha" type="date" id="fecha" value="<?php echo $prodespacho->fecha?>" size="10" placeholder="Fecha" required/>
   </label>
   <label >
    	<span>Tiquete</span>
	    <input name="tiquete" type="number" step="1" id="tiquete" value="<?php echo $prodespacho->tiquete?>" size="10" placeholder="Tiquete"/>
   </label>
   <label>
		<span>Placas</span>
	    <input name="placas" type="text" id="placas" value="<?php echo $prodespacho->placas?>"  onkeypress="javascript:{this.value = this.value.toUpperCase(); }" onkeydown="return validarNumerosyLetras(event);" size="10" placeholder="Placas" required/>
   </label>
    <label >
    	<span>Concepto</span>
	    <input name="concepto" type="text"  id="concepto" value="<?php echo $prodespacho->concepto?>" size="10" placeholder="Concepto"/>
   </label>
   <label>
    	<span>Cantidad</span>
	    <input name="cantidad" type="number" step="any" id="cantidad" value="<?php echo $prodespacho->cantidad?>" size="10" placeholder="Cantidad" required/>
   </label>
  <label >
    	<span>Unidad</span>
	    <input name="unidad" type="text" id="unidad" value="<?php echo $prodespacho->unidad?>" list="unidades" size="10" placeholder="Unidad"/>
        <datalist id="unidades">
        	<option value="UND">
            <option value="TM">
            <option value="RLL">
        </datalist>
   </label>
  <label >
    	<span>Observacion</span>
	    <input name="observacion" type="text" id="observacion" value="<?php echo $prodespacho->observacion?>" size="10" placeholder="Observacion"/>
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