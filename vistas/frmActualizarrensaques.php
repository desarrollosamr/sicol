<?php
@session_start();
extract($_REQUEST);
require "../clases/ConexionDatos.php";

//if (!isset($_SESSION['user']))
	//header("location:index.php?x=2");

$objConexion=Conectarse();
$moto="select * from gc_motonaves order by nombre";
$rsmotonaves=$objConexion->query($moto);
$bodegas = "select id, nombre from gc_bodegas order by nombre";
$rsbodegas = $objConexion->query($bodegas);
$productos = "select productosId, nombre, presentacion from gc_productos where presentacion<>9 order by nombre";
$rsproductos = $objConexion->query($productos);
//$graneles="select gc_tmpexistencias.producto as producto, gc_tmpexistencias.bodega as bodega, gc_tmpexistencias.lote as lote, gc_tmpexistencias.cantidad_tm as kilos,gc_tmpexistencias.cantidad_sacos as sacos, gc_productos.nombre as nproducto, gc_productos.presentacion as presentacion, gc_bodegas.nombre as nbodega from gc_tmpexistencias left join gc_productos on gc_tmpexistencias.producto=gc_productos.productosId left join gc_bodegas on gc_tmpexistencias.bodega=gc_bodegas.id where presentacion=9 order by nproducto";
$graneles="select gc_tmpexistencias.producto as producto, gc_tmpexistencias.bodega as bodega, gc_tmpexistencias.lote as lote, gc_tmpexistencias.cantidad_tm as kilos,gc_tmpexistencias.cantidad_sacos as sacos, gc_productos.nombre as nproducto, gc_productos.presentacion as presentacion, gc_bodegas.nombre as nbodega from gc_tmpexistencias left join gc_productos on gc_tmpexistencias.producto=gc_productos.productosId left join gc_bodegas on gc_tmpexistencias.bodega=gc_bodegas.id order by nproducto";
$rsgraneles = $objConexion->query($graneles);
if ($_REQUEST['codigo']){
	$sql="select gc_ensaque.Id as id, gc_ensaque.consecutivo as consecutivo, gc_ensaque.fecha as fecha, gc_ensaque.producto as producto, gc_ensaque.granel as granel, gc_ensaque.bodega as bodega, gc_ensaque.lote as lote, gc_ensaque.cantidad_tm as kilos, gc_ensaque.cantidad_sacos as sacos, (select nombre from gc_productos where gc_productos.productosId=gc_ensaque.producto) as nproducto, (select nombre from gc_productos where gc_productos.productosId=gc_ensaque.granel) as ngranel, gc_productos.presentacion as presentacion, gc_bodegas.nombre as nbodega, (select nombre from gc_bodegas where gc_bodegas.Id=gc_ensaque.bodega_granel) as nbgranel, gc_ensaque.bodega_granel as bgranel, gc_ensaque.lote_granel as loteg from gc_ensaque left join gc_productos on gc_ensaque.granel=gc_productos.productosId left join gc_bodegas on gc_ensaque.bodega=gc_bodegas.id where gc_ensaque.consecutivo = '$_REQUEST[codigo]'";
	$resultadoensaques = mysqli_query($objConexion,$sql) or die("Error" . mysqli_error($objConexion));
	$ensaques = $resultadoensaques->fetch_object();
	$granelns=$ensaques->ngranel . "-" . $ensaques->nbgranel . "-" . $ensaques->loteg;
	$granelcs=$ensaques->granel . "-" . $ensaques->bgranel . "-" . $ensaques->loteg;
}
?>

<html>
<head>
<meta charset="utf-8" />
<title>Formulario Actualizar Ensaques de Productos</title>
<script type="text/javascript" >
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

</script>

</head>

<body>
<form id="form1" name="form1" method="post" action="../controlador/coensaques.php" class="smart-green">
        <?php if ($_REQUEST[x]==1) { ?>
	<h1>Agregar ensaque de producto</h1>
        <?php } else { ?>
	<h1>Actualizar ensaque de producto</h1>
        <?php } ?>
   <label>
    	<span>Fecha</span>
	    <input name="fecha" type="date" id="fecha" value="<?php echo $ensaques->fecha?>" size="10" placeholder="Fecha" required/>
   </label>
   <label>
    	<span>Granel</span><div id="respuesta"></div>
        <select name="granel" id="granel">
              <option value="0">Seleccione</option>
              <?php		 
              while ($granel = $rsgraneles->fetch_object())
              {
              	 $graneln=$granel->nproducto . "-" . $granel->nbodega . "-" . $granel->lote;
              	 $granelc=$granel->producto . "-" . $granel->bodega . "-" . $granel->lote;
                 if ($granelc==$granelcs)
                 {
                 ?> 			 
                    <option value="<?php echo $granelcs?>" selected="selected"><?php echo $granelns?></option>              
                 <?php
                 } else {
                 ?>
                    <option value="<?php echo $granelc?>"><?php echo $graneln?></option>                
            <?php 
				 }
              }		//cierra el Mientras  
            ?>          
        </select>
   </label>
   <label>
    	<span>Producto</span><div id="respuesta"></div>
        <select name="producto" id="producto">
              <option value="0">Seleccione</option>
              <?php		 
              while ($producto = $rsproductos->fetch_object())
              {
                 if ($producto->productosId==$ensaques->producto)
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
    	<span>Cantidad en Kilos</span>
	    <input name="cantidadtm" type="number" step="1" id="cantidadtm" value="<?php echo $ensaques->kilos?>"   onchange="valpro()" size="10" placeholder="Cantidad en Kilos"  required/>
   </label>
   <label>
    	<span id="sacos">Cantidad en sacos o big bags</span>
	    <input name="cantidadsacos" type="number" step="1" id="cantidadsacos" value="<?php echo $ensaques->sacos?>" size="10" placeholder="Cantidad en sacos" required/>
   </label>
   <label>
    	<span>Bodega</span>
        <select name="bodega" id="bodega">
              <option value="0">Seleccione</option>
              <?php		 
              while ($bodega = $rsbodegas->fetch_object())
              {
                 if ($bodega->id==$ensaques->bodega)
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
    	<span>Lote</span>
	    <input name="lote" type="text" id="lote" value="<?php echo $ensaques->lote?>" size="10" placeholder="Lote"  onkeypress="javascript:{this.value = this.value.toUpperCase(); }" onkeydown="return validarNumerosyLetras(event);"  required/>
   </label>
   <label>
	<span>Motonave</span>
    <select name="motonave" id="motonave">
          <option value="0">Seleccione</option>
          <?php		 
          while ($motonave = $rsmotonaves->fetch_object())
          {
             if ($motonave->motonaveId==$ensaques->motonave)
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
   <input name="progid" type="hidden" value="<?php echo $_REQUEST['progid'] ?>" />
   <input name="despachoid" type="hidden" value="<?php echo $_REQUEST['codigo'] ?>" />
</form>
</body>
</html>