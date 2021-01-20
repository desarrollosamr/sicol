<?php
@session_start();
require "../clases/ConexionDatos.php";

//if (!isset($_SESSION['user']))
	//header("location:index.php?x=2");

$objConexion=Conectarse();
$fac=date("Y-m-d");
extract($_REQUEST);


if ($_REQUEST[x]==1){
	$uf="select fecha from gc_consecutivos where tabla='turnos'";
	$ruf=$objConexion->query($uf);	
	$rsuf=$ruf->fetch_object();
	if ($rsuf->fecha!=date("Y-m-d")){
		$rcc="update gc_consecutivos set consecutivo=0, fecha='" . $fac  . "' where tabla='turnos'";
		$rscc=mysqli_query($objConexion,$rcc) or die("Error" . mysqli_error($objConexion));
	}
	$uc="select consecutivo from gc_consecutivos where tabla='turnos'";
	$ruc=$objConexion->query($uc);	
	$rsuc=$ruc->fetch_object();	
	$sco=$rsuc->consecutivo+1;	
}

if ($_REQUEST['fecha']!="null" and $_REQUEST['fecha']!="undefined") {
	$fecha = $_REQUEST['fecha'];
} else {
	$fecha = date("Y-m-d");
}
$sql="select * from gc_turnos where Id = '$_REQUEST[codigo]'";
$resultdespa = $objConexion->query($sql);
$prodespacho = $resultdespa->fetch_object();
/*
if ($_REQUEST[x]==1){
	$clitur = $prodespacho->cliente;
	$buce = "select cedula from gc_clientes where id=" . $clitur;
	$resbc = $objConexion->query($buce);
	$resbce = $resbc->fetch_object();
	$cedula = $resbce->cedula;
}
*/
$cond="select * from gc_conductores";
$rsconductores=$objConexion->query($cond);

$clide="select * from gc_clientes where tipo='c'";
$rsclientes=mysqli_query($objConexion,$clide) or die("Error" . mysqli_error($objConexion));

$bodegas = "select Id, nombre from gc_bodegas order by nombre";
$rsbodegas = $objConexion->query($bodegas);

?>

<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8" />
<title>Formulario Actualizar turnos de Productos</title>
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
<form id="form1" name="form1" method="post" action="../controlador/coturnos.php" class="smart-green">
	<h1>Agregar turno</h1>
   <label>
	    <span>Fecha</span>
    	<?php if ($_REQUEST[x]==1){ ?>
		    <input name="fecha" type="date" id="fecha" value="<?php echo $fecha?>" size="10" placeholder="Fecha" required/>
		<?php } else { ?>
		    <input name="fecha" type="date" id="fecha" value="<?php echo $prodespacho->fecha?>" size="10" placeholder="Fecha" required/>
		<?php } ?>			
   </label>
   <label >
    	<span>Turno</span>
    	<?php if ($_REQUEST[x]==1){ ?>
		    <input name="turno" type="number" id="turno" value="<?php echo $sco ?>" size="10" placeholder="Turno" readonly="readonly"/>
		<?php } else { ?>
		    <input name="turno" type="number"  step="1" id="turno" value="<?php echo $prodespacho->turno ?>" size="10" placeholder="Turno" readonly="readonly"/>
		<?php } ?>			
   </label>        	
       <?php  	$cm = $prodespacho->clase_movimiento; ?>
   <label >
    	<span>Clase de movimiento</span>
        <select name="tipomov" id="tipomov" required="required">
              <option value="">Seleccione</option>
              <?php
         	
            if ($cm==="cs"){  ?>
	            <option value="cs" selected="selected">Cargue ensacado</option>;              
			<?php }else{ ?>
				<option value="cs">Cargue ensacado</option>
			<?php  }     
            if ($cm==="cg"){  ?>
	            <option value="cg" selected="selected">Cargue granel</option>              
			<?php }else{ ?>
				<option value="cg">Cargue granel</option>
			<?php }            
			if ($cm==="ds"){  ?>
	            <option value="ds" selected="selected">Descargue ensacado</option>              
			<?php }else{ ?>
				<option value="ds">Descargue ensacado</option>
			<?php }            
			if ($cm==="dg"){   ?>
	            <option value="dg" selected="selected">Descargue granel</option>              
			<?php }else{ ?>
				<option value="dg">Descargue granel</option>
			<?php } 
			if ($cm==="cb"){   ?>
	            <option value="cb" selected="selected">Cargue de big bags</option>              
			<?php }else{ ?>
				<option value="cb">Cargue de big bags</option>
			<?php }		
			if ($cm==="db"){   ?>
	            <option value="db" selected="selected">Descargue de big bags</option>              
			<?php }else{ ?>
				<option value="db">Descargue de big bags</option>
			<?php }		?>			
				 
        </select>
   </label>
    <label>
    	<span>Hora de registro</span>
	    <input name="hinicio" type="time" id="hinicio" value="<?php echo ($prodespacho->hora_registro=="00:00:00" ? "" : $prodespacho->hora_registro)?>" size="10" placeholder="Hora de inicio" required/>
   </label>
   <label>
    	<span>Hora de llamado</span>
	    <input name="hfinal" type="time" id="hfinal" value="<?php echo ($prodespacho->hora_atencion=="00:00:00" ? "" : $prodespacho->hora_atencion)?>" size="10" placeholder="Hora final" />
   </label>
   <label >
    	<span>Orden</span>
	    <input name="orden" type="number"  step="1" id="orden" value="<?php echo $prodespacho->orden?>" size="10" placeholder="Orden"/>
   </label>
   <label >
        <span>Placas</span>
        <input name="placas" type="text" id="placas" value="<?php echo $prodespacho->placas?>" size="50" placeholder="Placas"  onkeypress="javascript:{this.value = this.value.toUpperCase(); }" onkeydown="return validarNumerosyLetras(event);"/>
    </label>

   <label>
    	<span>Peso de la carga</span>
	    <input name="pesocarga" type="number" step="any" id="pesocarga" value="<?php echo $prodespacho->peso_carga?>" size="10" placeholder="Peso de la carga" required/>
   </label>
   <label >
    	<span>Conductor</span>
        <select name="conductor" id="conductor" required="required">
              <option value="">Seleccione</option>
              <?php		 
              while ($conductor = $rsconductores->fetch_object())
              {
                 if ($conductor->id==$prodespacho->conductor)
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
	             if ($cliente->nit==$prodespacho->cliente)
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
		<span>Productos</span>
	    <input name="productos" type="text" id="productos" value="<?php echo $prodespacho->productos?>"  size="10" placeholder="Productos" required/>
   </label>   
   <label>
        <span>Bodega</span>
        <select name="bodega" id="bodega">
            <option value="0">Seleccione</option>
            <?php
            while ($bodega = $rsbodegas->fetch_object())
            {
                if ($bodega->Id==$prodespacho->bodega)
                {
                    ?>
                    <option value="<?php echo $bodega->Id?>" selected="selected"><?php echo $bodega->nombre?></option>
                <?php
                } else {
                    ?>
                    <option value="<?php echo $bodega->Id?>"><?php echo $bodega->nombre?></option>
                <?php
                }
            }		
            ?>
        </select>
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
</form>
</body>
</html>