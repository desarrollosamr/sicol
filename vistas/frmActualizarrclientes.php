<?php
@session_start();
require "../clases/ConexionDatos.php";

//if (!isset($_SESSION['user']))
	//header("location:index.php?x=2");

$objConexion=Conectarse();

$sql="select * from gc_clientes where nit = '$_REQUEST[Id]'";
$resultadoclientes = $objConexion->query($sql);

$clientes = $resultadoclientes->fetch_object();

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta charset=utf-8 />
<title>Formulario Actualizar clientes</title>
</head>

<body>
<form id="form1" name="form1" method="post" action="../controlador/coclientes.php" class="smart-green">
        <?php if ($_REQUEST[x]==1) { ?>
	<h1>Agregar clientes</h1>
        <?php } else { ?>
	<h1>Actualizar clientes</h1>
        <?php }
		if ($_REQUEST[x]==1) {?>
   <label>
    	<span>NIT</span>
	    <input name="nit" type="text" id="nit" value="<?php echo $clientes->nit?>" size="40" placeholder="NIT" required/>
   </label>
        <?php } ?>
   <label>
    	<span>Razon Social</span>
	    <input name="razonsocial" type="text" id="razonsocial" value="<?php echo $clientes->nombre?>" size="40" placeholder="Razon social" required/>
   </label>
   <label>
    	<span>Direccion</span>
	    <input name="direccion" type="text" id="direccion" value="<?php echo $clientes->direccion?>" size="40" placeholder="Dirección" />
   </label>
   <label>
    	<span>Telefono</span>
	    <input name="telefono" type="text" id="telefono" value="<?php echo $clientes->telefono?>" size="40" placeholder="Teléfono" />
   </label>
   <label>
      <?php echo $clientes->tipo?>
    	<div><span>Tipo</span></div>
        <div>
        <?php if ($clientes->tipo === "p"){?>
	   		<input type="radio" id="tipop" name="tipo" value="p" checked="checked"/>
        <?php }elseif ($clientes->tipo === "c") { ?>
	   		<input type="radio" id="tipop" name="tipo" value="p"/>
    <?php }else{ ?>
        <input type="radio" id="tipop" name="tipo" value="p"/>
		<?php } ?>
           	<label for="tipop" class="enlinea">Propietario</label>
        <?php if ($clientes->tipo === "c"){ ?>
	   		<input type="radio" id="tipoc" name="tipo" value="c" checked="checked"/>			
        <?php }elseif ($clientes->tipo === "p"){ ?>
	   		<input type="radio" id="tipoc" name="tipo" value="c"/>
    <?php }else{ ?>
        <input type="radio" id="tipoc" name="tipo" value="c" checked="checked"/>
		<?php } ?>
           	<label for="tipoc" class="enlinea">Comprador</label>
        </div>
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
   <input name="Id" type="hidden" value="<?php echo $_REQUEST['codigo'] ?>" />
</form>
</body>
</html>