// JavaScript Document
function grabar(){  
	// Agregamos el valor de insertar
	$('#boton').val('Agregar')	      //el  valor del id del   campo oculto   para mandar  al controlador   
	/* if($("#nombre").val() == ""){
        alert("El campo Nombre no puede estar vacío.");
        $("#nombre").focus();       // Esta función coloca el foco de escritura del usuario en el campo Nombre directamente.
        return false;
    }
    if($("#apellidos").val() == ""){
        alert("El campo Apellidos no puede estar vacío.");
        $("#apellidos").focus();
        return false;}*/
	
	// Parametros
	var url		=' ../controlador/comatpribache.php',   //  adonde  me procesa   la  informacion  el  controlador como si   enviara    normal  mi form 
		cadena	=$("#form1").serialize();     // aca  recojo  todos   los    valores    de mi form   
	// Llamar a la funcion
	e_ajax(url,cadena);

}
function grabar2(){  
 $( '#modificar'). val (   prompt('escriba el id del campo que desea modificar ',''));     ///  campo oculto de  nombre  modificar 
	// Agregamos el valor de   cada  funcion
	$('#procesar').val('2');
// Parametros
	   var url		=' ../controlador/controlador.php', 
		cadena	=$("#formulario").serialize();	
	// Llamar a la funcion
	e_ajax(url,cadena);     	
}
function grabar3(){  
$( '#modificar'). val (   prompt('escriba el id del campo que desea eliminar ',''));  
	// Agregamos el valor de suma
	$('#procesar').val('3');

	// Parametros
	var url		=' ../controlador/controlador.php', 
		cadena	=$("#formulario").serialize();	// Llamar a la funcion
	e_ajax(url,cadena);     

} 


   //ajax   la funcion  en que    encapsulo    cada   onclick  me trera  ca 
	//  la funcion e_ajax(url,cadena)      es  la  que  se le  asigno aca  d  onclick para  asi llamar  ajax desede esta funcion 
function e_ajax(url,cadena){
  $.ajax({
		async:true,  //que sea   de  modo que no recarge la pagina 
			type:"POST",  //   va atrerme     por post     lo que  yo envio desdde aca   
			dataType:"html",    //un   dato  thml
			contentType: "application/x-www-form-urlencoded",
			url:url,  //  url la    variable  que declare      en cad  onclick   
			data:cadena,   //  la cadena  q es loq    captura de la vista  y me  trae aca  para  enviar los  datos  ay  al php 
			success: function(data){   ///    peticion  de  php 
				
				$("#content").load('../vistas/baches_data.php?progid=20001453');				
				return false;
					},
			error: function(){
				 
			},
			timeout:4000
		});
	
   //$("#respuesta").thml("")

}
  

 




 
		

