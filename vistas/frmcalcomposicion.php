<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Formulario Calcular Composicion por Bache</title>
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

function valdosi() {
	var fecha=document.forms['form1'].fecha.value;
	var tons=document.forms['form1'].kilos.value;
	var url = "../clases/dosificacion.php?fecha="+fecha+"&tons="+tons;
	myRequest.open("GET", url, true);
	myRequest.onreadystatechange = respvalpro;
	myRequest.send(null);
}

function respvalpro() {
    if(myRequest.readyState == 4) {
        if(myRequest.status == 200) {
			 document.getElementById('dosificacion').innerHTML = myRequest.responseText;
        } else {
            document.getElementById('respuesta').innerHTML = myRequest.status;
        }
    }
}
function exportar(){
	var salida=document.getElementById('dosificacion').innerHTML;
	var url = "../clases/exportar.php?contenido="+salida;
	myRequest.open("GET", url, true);
	myRequest.onreadystatechange = respvalpro;
	myRequest.send(null);
}
</script>
<style>
table {
    border-collapse: collapse;
}

table, td, th {
    border: 1px solid black;
}
</style>
</head>

<body>
<div id="formulario">
	<form id="form1" name="form1" method="post" class="smart-green">
		<h1>Calcular composicion por Bache</h1>
	   <label>
	    	<span>Fecha</span>
		    <input name="fecha" type="date" id="fecha" size="10" placeholder="Fecha" required/>
	   </label>
	   <label>
	        <span>Toneladas por bache&nbsp;&nbsp;</span>
	        <input type="radio" id="kilos1" name="kilos" value="4" checked="checked">
           	<label for="kilos1"><img src="../Imagenes/4.png" /></label>
	        <input type="radio" id="kilos2" name="kilos" value="5">
           	<label for="kilos2"><img src="../Imagenes/5.png" /></label>
	   </label>
	   <label>
	        <span>&nbsp;</span> 
	        <input type="button" name="calcular" value="Calcular" class="button" onclick="valdosi()">
	        <input type="button" name="cancelar" id="cancelar" class="button" value="Cancelar" onclick="window.history.go(-1); return false;" />
	   </label>    
	</form>
</div>
<div id="dosificacion"></div>
<input  type="button" onclick="exportar()"/>
</body>
</html>