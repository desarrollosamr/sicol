<?php
@session_start();
require "../clases/ConexionDatos.php";

//if (!isset($_SESSION['user']))
	//header("location:index.php?x=2");

$objConexion=Conectarse();
extract($_REQUEST);
if ($_REQUEST[x]==1 and $_REQUEST['tipo']=="s"){
	$uc="select consecutivo from gc_consecutivos where tabla='despachos'";
	$ruc=$objConexion->query($uc);
	$rsuc=$ruc->fetch_object();
	$sco=$rsuc->consecutivo+1;
} elseif ($_REQUEST[x]==1 and $_REQUEST['tipo']=="e"){
	$uc="select consecutivo from gc_consecutivos where tabla='recibos'";
	$ruc=$objConexion->query($uc);
	$rsuc=$ruc->fetch_object();
	$sco=$rsuc->consecutivo+1;	
}

$sql="select * from gc_despachos where Id = '$_REQUEST[codigo]'";
$resultdespa = $objConexion->query($sql);
$prodespacho = $resultdespa->fetch_object();

$trans="select * from gc_transportadores";
$rstransportadores=$objConexion->query($trans);

$cond="select * from gc_conductores";
$rsconductores=$objConexion->query($cond);

$clide="select * from gc_clientes where tipo='c'";
$rsclientes=mysqli_query($objConexion,$clide) or die("Error" . mysqli_error($objConexion));

$btu="select * from gc_turnos where turno=" . $turno . " and fecha='" . $fecha . "'";
$rbtu=mysqli_query($objConexion,$btu) or die("Error" . mysqli_error($objConexion));
$rsbtu=$rbtu->fetch_object();

?>

<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8" />
<title>Formulario Actualizar Despachos de Productos</title>
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
<form id="form1" name="form1" method="post" action="../controlador/codespachos.php" class="smart-green">
        <?php if ($_REQUEST[x]==1 and $_REQUEST[tipo]=="s") { ?>
	<h1>Agregar despacho de producto</h1>
        <?php } elseif ($_REQUEST[x]==1 and $_REQUEST[tipo]=="e") { ?>
	<h1>Agregar recibo de producto</h1>
        <?php } elseif ($_REQUEST[x]!=1 and $_REQUEST[tipo]=="s") { ?>
	<h1>Actualizar despacho de producto</h1>
        <?php } elseif ($_REQUEST[x]!=1 and $_REQUEST[tipo]=="e") { ?>
	<h1>Actualizar recibo de producto</h1>
        <?php } ?>
   <label>
    	<span>Fecha</span>
	    <input name="fecha" type="date" id="fecha" value="<?php echo ($turno == "null" ? $prodespacho->fecha : $rsbtu->fecha)?>" size="10" placeholder="Fecha" required/>
   </label>
    <label>
    	<span>Hora de inicio</span>
	    <input name="hinicio" type="time" id="hinicio" value="<?php echo ($prodespacho->hora_inicio=="00:00:00" ? "" : $prodespacho->hora_inicio)?>" size="10" placeholder="Hora de inicio" required/>
   </label>
   <label>
    	<span>Hora final</span>
	    <input name="hfinal" type="time" id="hfinal" value="<?php echo ($prodespacho->hora_final=="00:00:00" ? "" : $prodespacho->hora_final)?>" size="10" placeholder="Hora final"/>
   </label>
   <label >
    	<span>Remision</span>
    	<?php if ($_REQUEST[x]==1){ ?>
		    <input name="remision" type="number"  step="1" id="remision" value="<?php echo $sco ?>" size="10" placeholder="Remision" readonly="readonly"/>
		<?php } else { ?>
		    <input name="remision" type="number"  step="1" id="remision" value="<?php echo $prodespacho->remision ?>" size="10" placeholder="Remision" readonly="readonly"/>
		<?php } ?>			
   </label>        	
   		<span>Tiquete</span>
	    <input name="tiquete" type="number"  step="1" id="tiquete" value="<?php echo $prodespacho->tiquete?>" size="10" placeholder="Tiquete"/>
   </label>      
   <label >
    	<span>Orden</span>
	    <input name="orden" type="number"  step="1" id="orden" value="<?php echo ($turno == "null" ? $prodespacho->orden : $rsbtu->orden)?>" size="10" placeholder="Orden"/>
   </label>

   <label >
        <span>Observacion</span>
        <input name="observacion" type="text" id="observacion" value="<?php echo $prodespacho->observacion?>" size="50" placeholder="Observacion"/>
    </label>
    <label>
		<span>Placas</span>
	    <input name="placas" type="text" id="placas" value="<?php echo ($turno == "null" ? $prodespacho->placas : $rsbtu->placas)?>"  onkeypress="javascript:{this.value = this.value.toUpperCase(); }" onkeydown="return validarNumerosyLetras(event);" size="10" placeholder="Placas" required/>
   </label>
   <label>
    	<span>Peso de la carga</span>
	    <input name="pesocarga" type="number" step="any" id="pesocarga" value="<?php echo ($turno == "null" ? $prodespacho->peso_carga : $rsbtu->peso_carga)?>" size="10" placeholder="Peso de la carga" required/>
   </label>
   <label >
    	<span>Transportadora</span>
        <select name="transportador" id="transportador">
              <option value="0">Seleccione</option>
              <?php		 
              while ($transportador = $rstransportadores->fetch_object())
              {
                 if ($transportador->nit==$prodespacho->transportador)
                 {
                 ?> 			 
                    <option value="<?php echo $transportador->nit?>" selected="selected"><?php echo $transportador->razon_social?></option>              
                 <?php
                 } else {
                 ?>
                    <option value="<?php echo $transportador->nit?>"><?php echo $transportador->razon_social?></option>              	            <?php 
				 }
              }		//cierra el Mientras  
            ?>          
        </select>
   </label>
   <label >
    	<span>Conductor</span>
        <select name="conductor" id="conductor" required="required">
              <option value="">Seleccione</option>
              <?php		 
              while ($conductor = $rsconductores->fetch_object())
              {
                 if ($conductor->id==$prodespacho->conductor or $conductor->id==$rsbtu->conductor)
                 {
                 ?> 			 
                    <option value="<?php echo $conductor->id?>" selected="selected"><?php echo $conductor->nombre?></option>              
                 <?php
                 } else {
                 ?>
                    <option value="<?php echo $conductor->id?>"><?php echo $conductor->nombre?></option>              	            	   <?php 
				 }
              }	  
            ?>          
        </select>
   </label>
   <label>
		<span>Cliente</span>
	    <select name="cliente" id="cliente">
	          <option value="0">Seleccione</option>
	          <?php		 
	          while ($cliente = $rsclientes->fetch_object())
	          {
	             if ($cliente->nit==$prodespacho->cliente_destino or $cliente->nit==$rsbtu->cliente)
	             {
	             ?> 			 
	                <option value="<?php echo $cliente->nit?>" selected="selected"><?php echo $cliente->nombre?></option>
	             <?php
	             } else {
	             ?>
	                <option value="<?php echo $cliente->nit?>"><?php echo $cliente->nombre?></option>
	             <?php 
				 }
	           }		
	        ?>          
	    </select>
	</label>
   <label>
   		<span>Destino</span>
   		<input type="text" id="destino" name="destino" value="<?php echo $prodespacho->destino ?>"/>
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
   <input name="tipo" type="hidden" value="<?php echo $_REQUEST['tipo'] ?>"/>
   <input name="turno" type="hidden" value="<?php echo ($_REQUEST[x]==1 ? $_REQUEST['turno'] : $prodespacho->turno) ?>"/>

</form>
</body>
</html>