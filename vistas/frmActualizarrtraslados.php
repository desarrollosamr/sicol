<?php
@session_start();
require "../clases/ConexionDatos.php";

//if (!isset($_SESSION['user']))
	//header("location:index.php?x=2");

$objConexion=Conectarse();
$vactmp = "truncate gc_tmpexistencias";
$rvactmp = mysqli_query($objConexion,$vactmp) or die('MySql Error1' . mysql_error());
require_once"../clases/calcular_existencias1.php";
$bodegas = "select id, nombre from gc_bodegas order by nombre";
$rsbodegas = $objConexion->query($bodegas);
$bodegasd = "select id, nombre from gc_bodegas1 order by nombre";
$rsbodegasd = $objConexion->query($bodegasd);
$productos = "select productosId, nombre from gc_productos order by nombre";
$rsproductos = $objConexion->query($productos);
$moto="select * from gc_motonaves";
$rsmotonaves=$objConexion->query($moto);
$sql="select * from gc_traslados where id = '$_REQUEST[Id]'";
$resultadotraslados = $objConexion->query($sql);
$traslados = $resultadotraslados->fetch_object();

if ($_REQUEST[x]==1){
	$uc="select consecutivo from gc_consecutivos where tabla='traslados'";
	$ruc=$objConexion->query($uc);
	$rsuc=$ruc->fetch_object();
	$sco=$rsuc->consecutivo+1;
}

$clide="select * from gc_clientes where tipo='c'";
$rsclientes=mysqli_query($objConexion,$clide) or die("Error" . mysqli_error($objConexion));
?>

<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8" />
<title>Formulario Actualizar Traslados</title>
<script type="text/javascript" >
var myRequest = getXMLHTTPRequest();
function getXMLHTTPRequest() {
	var request = false;
	if(window.XMLHttpRequest){
	  request = new XMLHttpRequest();
	} else {
	  if(window.ActiveXObject){
	    try {
	      request = new ActiveXObject("Msml2.XMLHTTP");
	    }
	    catch(err1){
	      try {
	        request = new ActiveXObject("Microsoft.XMLHTTP");
	      }
	      catch(err2) {
	        request = false;
	      }
	    }
	  }
    }
    return request;
}

function valpro() {
	var producto=document.forms['form1'].producto.value;
	var ton=document.forms['form1'].cantidadtm.value;
	var url = "../clases/validadores.php?producto=" + producto+"&tons="+ton+"&accion=dispo";
	myRequest.open("GET", url, true);
	myRequest.onreadystatechange = respvalpro;
	myRequest.send(null);
}

function respvalpro() {
    if(myRequest.readyState == 4) {
        if(myRequest.status == 200) {
			 document.getElementById('cantidadsacos').value=parseInt(myRequest.responseText);
        } else {
            document.getElementById('respuesta').innerHTML= myRequest.status;
        }
    }
}
function valbod(estado) {
	var producto=document.forms['form1'].producto.value;
	if (estado!=""){
		var url = "../clases/validadores.php?producto=" + producto+"&bodega=" + estado + "&accion=exbod";
	}else{
		var url = "../clases/validadores.php?producto=" + producto+"&accion=exbod";
	}
	myRequest.open("GET", url, true);
	myRequest.onreadystatechange = respvalbod;
	myRequest.send(null);
}
function respvalbod() {
    if(myRequest.readyState == 4) {
        if(myRequest.status == 200) {
			 document.getElementById('bodegas').innerHTML = myRequest.responseText;
        } else {
            document.getElementById('respuesta').innerHTML= myRequest.status;
        }
    }
}

function valexi() {
	var producto=document.forms['form1'].producto.value;
	var ton=document.forms['form1'].cantidadtm.value;
	var bod=document.forms['form1'].bodega.value;
	var lote=document.forms['form1'].lote.value;
	var url = "../clases/validadores.php?producto=" + producto + "&bodega=" + bod + "&tons=" + ton + "&lote=" + lote + "accion=valex";
	myRequest.open("GET", url, true);
	myRequest.onreadystatechange = respvalexi;
	myRequest.send(null);
}
function respvalexi() {
    if(myRequest.readyState == 4) {
        if(myRequest.status == 200) {
        	answer=myRequest.responseText;
        	if (answer != ""){
				document.getElementById('estado').innerHTML= myRequest.responseText;
				document.getElementById('cantidadtm').value="";
				document.getElementById('cantidadsacos').value="";
				document.getElementById('bodega').value=0;
			}
			
        } else {
            document.getElementById('respuesta').innerHTML= myRequest.status;
        }
    }
}

</script>

</head>

<body>
<form id="form1" name="form1" method="post" action="../controlador/cotraslados.php" class="smart-green">
        <?php if ($_REQUEST[x]==1) { ?>
	<h1>Agregar traslados</h1>
        <?php } else { ?>
	<h1>Actualizar traslados</h1>
        <?php } ?>
   <label>
    	<span>Fecha</span>
	    <input name="fecha" type="date" id="fecha" value="<?php echo $traslados->fecha?>" size="10" placeholder="Fecha" required/>
   </label>
   <label >
    	<span>Consecutivo</span>
    	<?php if ($_REQUEST[x]==1){ ?>
		    <input name="consecutivo" type="number" step="1" id="consecutivo" value="<?php echo $sco?>" size="10" readonly="readonly" placeholder="Consecutivo"/>
		<?php } else { ?>
		    <input name="consecutivo" type="number" step="1" id="consecutivo" value="<?php echo $traslados->consecutivo?>" size="10" readonly="readonly" placeholder="Consecutivo"/>
		<?php } ?>
   </label>
   <label>
    	<span>Producto</span>
        <select name="producto" id="producto">
 	        <?php if ($_REQUEST[x]==1 and $_REQUEST[tipo]=="s") { ?>
		        <select name="producto" id="producto" onchange="valbod()">
		    <?php } elseif ($_REQUEST[x]==0 and $_REQUEST[tipo]=="s") { ?>
		    	<script type="text/javascript">valbod(<? echo $traslados->bodega; ?>);</script>
		    	<select name="producto" id="producto" onchange="valbod()">
		    <?php } elseif ($_REQUEST[tipo]=="e") { ?>
		    	<select name="producto" id="producto">
		    <?php } ?>	
              <option value="0">Seleccione</option>
              <?php		 
              while ($producto = $rsproductos->fetch_object())
              {
                 if ($producto->productosId==$traslados->producto)
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
    	<span>Lote</span>
	    <input name="lote" type="text" id="lote" value="<?php echo $traslados->lote?>" size="10" placeholder="Lote"  required/>
   </label>      
   <label>
    	<span>Cantidad kilos</span>
	    <input name="cantidad" type="number" step="any" id="cantidad" value="<?php echo $traslados->cantidad?>" size="10" placeholder="Cantidad en Kilos"  required/>
   </label>   
   <label >
    	<span>Cantidad sacos</span>
	    <input name="sacos" type="text" id="sacos" value="<?php echo $traslados->cantidad_sacos?>" size="10" placeholder="Cantidad sacos"/>
   </label>
   <label>
    	<span>Bodega origen</span>
        <select name="bodega" id="bodega">
              <option value="0">Seleccione</option>
              <?php		 
              while ($bodega = $rsbodegas->fetch_object())
              {
                 if ($bodega->id==$traslados->origen)
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
   <label>
    	<span>Bodega destino</span>
        <select name="bodegad" id="bodegad">
              <option value="0">Seleccione</option>
              <?php		 
              while ($bodegad = $rsbodegasd->fetch_object())
              {
                 if ($bodegad->id==$traslados->destino)
                 {
                 ?> 			 
                    <option value="<?php echo $bodegad->id?>" selected="selected"><?php echo $bodegad->nombre?></option>              
                 <?php
                 } else {
                 ?>
                    <option value="<?php echo $bodegad->id?>"><?php echo $bodegad->nombre?></option>                
            <?php 
				 }
              }		//cierra el Mientras  
            ?>          
        </select>
   </label>
   <label>
	<span>Motonave</span>
    <select name="motonave" id="motonave">
          <option value="0">Seleccione</option>
          <?php		 
          while ($motonave = $rsmotonaves->fetch_object())
          {
             if ($motonave->motonaveId==$traslados->motonave)
             {
             ?> 			 
                <option value="<?php echo $motonave->motonaveId?>" selected="selected"><?php echo $motonave->nombre?></option>   	             <?php
             } else {
             ?>
                <option value="<?php echo $motonave->motonaveId?>"><?php echo $motonave->nombre?></option>              	            			 <?php 
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
        <input type="button" name="cancelar" id="cancelar" class="button" value="Cancelar" onClick="window.history.go(-1); return false;" />
   </label>    
   <input name="Id" type="hidden" value="<?php echo $_REQUEST['Id'] ?>" />
</form>
</body>
</html>