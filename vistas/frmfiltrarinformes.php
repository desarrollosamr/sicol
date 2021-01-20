<?php
session_start();
include"../clases/ConexionDatos.php";
$objConexion = Conectarse();
$cli=$_SESSION['userid'];
$ncliente="select * from gc_clientes where nit=" . $cli;
$rncliente = mysqli_query($objConexion,$ncliente) or die ("Error" . mysqli_error($objConexion));
$rsncliente=mysqli_fetch_object($rncliente);
$rproducto = "select * from gc_productos order by nombre";
$rsproductos = mysqli_query($objConexion,$rproducto) or die ("Error" . mysqli_error($objConexion));
$rcliente = "select * from gc_clientes";
$rsclientes = mysqli_query($objConexion,$rcliente) or die ("Error" . mysqli_error($objConexion));
$rbodegas = "select * from gc_bodegas";
$rsbodegas = mysqli_query($objConexion,$rbodegas) or die ("Error" . mysqli_error($objConexion));
extract($_REQUEST);
$destino = "../Plantilla/vistaPrincipal.php?pg=../vistas/" . $_REQUEST['opr'] . "_report.php&modulo=informes";
$criterio=$_REQUEST['criterio'];
if ($_REQUEST['tipo']=="s"){
	$infde="despachos por:";
} elseif ($_REQUEST['tipo']=="e"){
	$infde="recibos por:";
} elseif ($_REQUEST['tipo']=="g"){
	$infde="entradas y salidas entre";
} elseif ($_REQUEST['tipo']=="r"){
	$infde="rotación por:";
} elseif ($_REQUEST['tipo']=="en"){
	$infde="ensaques por:";
}

$msg = "";
switch ($criterio){
			case "producto":
				$msg .= '<script type="text/javascript">document.getElementById("labelcliente").style.display="none";</script>';
				break;
			case "general":
				$msg  .= '<script type="text/javascript">			
				document.getElementById("labelproducto").style.display="none";
				document.getElementById("labelcliente").style.display="none";
				document.getElementById("labelbodega").style.display="none";
				</script>';
				break;				
		}
$msg .=
'<style>
table {
    border-collapse: collapse;
}

table, td, th {
    border: 1px solid black;
}
</style>
<div id="formulario">
	<form id="form1" name="form1" method="post" action="' . $destino . '" class="smart-green">
		<h1>Filtrar informe de ' . $infde . ' ' . $criterio . '</h1>
	   <label>
	    	<span>Fecha inicial</span>
		    <input name="fechai" type="date" id="fechai" size="10" placeholder="Fecha inicial" required/>
	    	<span>Fecha Final</span>
		    <input name="fechaf" type="date" id="fechaf" size="10" placeholder="Fecha final" required/>
	   </label>
	   <label id="labelproducto">
	        <span>Producto</span>
	    	<select name="producto" id="producto" onchange="valbod()">
	            <option value="0">Seleccione</option>';
	            while ($producto = $rsproductos->fetch_object())
	              {
$msg .=	            ' 			 
	          	<option value="' . $producto->productosId . '">' . $producto->nombre . '</option>';            
                  } 
$msg .=   	'</select>
	   </label>';
	if ($_SESSION[nivel]==3){ 
$msg .=		'<input type="text" readonly="readonly" value="' . $rsncliente->nombre . '"/>
		<input type="hidden" name="cliente" id="cliente" value="' . $rsncliente->nit . '"/>';
	 } else { 
$msg .=		'<label id="labelcliente">
        <span>Cliente</span>
    	<select name="cliente" id="cliente">
            <option value="0">Seleccione</option>';
            	 
            while ($cliente = $rsclientes->fetch_object())
              {
$msg .=   ' 			 
            <option value="' . $cliente->nit . '">' . $cliente->nombre . '</option>';
           	  } 
$msg .=  '</select>
	    </label>';
	}  	   

$msg .=	 '<label id="labelbodega">
	        <span>Bodega</span>
	    	<select name="bodega" id="bodega">
	            <option value="0">Seleccione</option>';
	            		 
	            while ($bodega = $rsbodegas->fetch_object())
	              {
	             			 
$msg .=      '<option value="' . $bodega->Id . '">' . $bodega->nombre . '</option>';            
	           	  } 
$msg .=    '</select>
	   </label>	   
	   <label>
	        <span>&nbsp;</span> 
	        <input type="submit" name="filtrar" value="Filtrar" class="button">&nbsp;&nbsp;
	        <input type="button" name="cancelar" id="cancelar" class="button" value="Cancelar" onclick="window.history.go(-1); return false;" />
	        <input type="hidden" name="opr" value="' . $_REQUEST["opr"] . '"/>
	        <input type="hidden" name="tipo" value="' . $_REQUEST["tipo"] . '"/>
	        <input type="hidden" name="criterio" value="' . $_REQUEST["criterio"] . '"/>
	   </label>    
	</form>
</div>
<div id="dosificacion"></div>';
echo $msg;
?>