<?php
@session_start();
require "../clases/ConexionDatos.php";

//if (!isset($_SESSION['user']))
	//header("location:index.php?x=2");

$objConexion=Conectarse();

$bodegas = "select Id, nombre from gc_bodegas order by nombre";
$rsbodegas = $objConexion->query($bodegas);

$clientes = "select * from gc_clientes order by nombre";
$rsclientes = $objConexion->query($clientes);

$productos="select * from gc_productos order by nombre";
$rsproductos = $objConexion->query($productos);
$row = mysqli_num_rows($rsproductos);

$moto="select * from gc_motonaves order by nombre";
$rsmotonaves=$objConexion->query($moto);

$sql="select * from gc_despachos_producto where id = '$_REQUEST[Id]'";
$resultadoprodespacho = $objConexion->query($sql);
$prodespacho = $resultadoprodespacho->fetch_object();
$bodac=$prodespacho->bodega;
?>

<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8" />
<title>Formulario Actualizar Despachos de Productos</title>
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
	var fecha=document.forms['form1'].fecha.value;
	var url = "../clases/validadores.php?producto=" + producto + "&bodega=" + bod + "&tons=" + ton + "&lote=" + lote + "&fecha=" + fecha +"&accion=valex";
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
				document.getElementById('lote').value="";
			}
			
        } else {
            document.getElementById('respuesta').innerHTML= myRequest.status;
        }
    }
}

</script>
</head>

<body>
	<form id="form1" name="form1" method="post" action="../controlador/coprosalidas.php" class="smart-green">
	        <?php if ($_REQUEST[x]==1 && $_REQUEST[tipo]=="s") { ?>
		<h1>Agregar despacho de producto</h1>
	        <?php } elseif ($_REQUEST[tipo]=="s") { ?>
		<h1>Actualizar despacho de producto</h1>
	        <?php } elseif ($_REQUEST[x]==1 && $_REQUEST[tipo]=="e") { ?>
	    <h1>Agregar recibo de producto</h1>
	        <?php } elseif ($_REQUEST[tipo]=="e") { ?>
	    <h1>Actualizar recibo de producto</h1>
	        <?php } ?>
	   <label>
	    	<span>Producto</span><div id="respuesta"></div>
	        <?php if ($_REQUEST[x]==1 and $_REQUEST[tipo]=="s") { ?>
		        <select name="producto" id="producto" onchange="valbod()">
		    <?php } elseif ($_REQUEST[x]==1 and $_REQUEST[tipo]=="e") { ?>
		        <select name="producto" id="producto">
		    <?php } elseif ($_REQUEST[x]==0 and $_REQUEST[tipo]=="s") { ?>
		    	<script type="text/javascript">valbod(<?php $bodac?>);</script>
		    	<select name="producto" id="producto" onchange="valbod()">
		    <?php } elseif ($_REQUEST[x]==0 and $_REQUEST[tipo]=="e") { ?>
		        <select name="producto" id="producto">
		    <?php } ?>	
	            <option value="0">Seleccione</option>
	              <?php		 
	              while ($producto = $rsproductos->fetch_object())
	              {
	                 if ($producto->productosId==$prodespacho->producto)
	                 {
	                 ?> 			 
	                    <option value="<?php echo $producto->productosId?>" selected="selected"><?php echo $producto->nombre?></option>              
	                 <?php
	                 } else {
	                 ?>
	            <option value="<?php echo $producto->productosId?>"><?php echo $producto->nombre?></option>                        	  <?php 
					 }
	              }		//cierra el Mientras  
	          ?>          
	        </select>
	   </label>
	   <label>
	    	<span>Cantidad en Kilos</span>
		    <input name="cantidadtm" type="number" step="1" id="cantidadtm" value="<?php echo $prodespacho->cantidad_tm?>" size="10" placeholder="Cantidad en Kilos"  onchange="valpro()" required/>
	   </label>
	   <label>
	    	<span id="sacos">Cantidad en sacos o big bags</span>
		    <input name="cantidadsacos" type="number" step="1" id="cantidadsacos" value="<?php echo $prodespacho->cantidad_sacos?>" size="10" placeholder="Cantidad en sacos" required/>
	   </label>
	   <div id="estado"></div>
       <div id="bodegas">
			<?php if ($_REQUEST[tipo]=="e"){ ?>
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
			            }		//cierra el Mientras
			            ?>
			        </select>
			   </label>
			<?php } ?>
       </div>
	   <label>
	    	<span>Lote</span>
		    <input name="lote" type="text" id="lote" value="<?php echo $prodespacho->lote?>" size="10" placeholder="Lote" onchange='valexi()' required/>
	   </label>
	   	<label>
		<span>Motonave</span>
	    <select name="motonave" id="motonave">
	          <option value="0">Seleccione</option>
	          <?php		 
	          while ($motonave = $rsmotonaves->fetch_object())
	          {
	             if ($motonave->motonaveId==$prodespacho->motonave)
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
	   <input name="progid" type="hidden" value="<?php echo $_REQUEST['progid'] ?>" />
	   <input name="remisionid" type="hidden" value="<?php echo $_REQUEST['progid'] ?>" />
	   <input  type="hidden" name="tipo" value="<?php echo $_REQUEST['tipo'] ?>"/>
	   <input type="hidden" name="fecha" value="<?php echo $_REQUEST['fecha'] ?>"/>
	</form>	
</body>
</html>