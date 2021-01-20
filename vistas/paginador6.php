<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1">
        <title>Pagination with Jquery, Ajax, PHP</title>
        <script type="text/javascript">
            $(document).ready(function(){
				$.urlParam = function(name){
   				 var results = new RegExp('[\?&]' + name + '=([^&#]*)').exec(window.location.href);
				if (results==null){
				   return null;
				}
				else{
				   return results[1] || 0;
				}
			}
				var tabla  = decodeURIComponent($.urlParam('lista'));
				alert(tabla);
				var codigo = decodeURIComponent($.urlParam('Id'));
				var module = decodeURIComponent($.urlParam('modulo'));
				var progid = decodeURIComponent($.urlParam('progid'));
				var x = decodeURIComponent($.urlParam('x'));
				function loading_show(){
                    $('#loading').html("<img src='../Imagenes/ajax-loader.gif' />").fadeIn('fast');
                }
                function loading_hide(){
                    $('#loading').fadeOut('fast');
                }                
				$("#navigationwrapleft").load('../vistas/muestramenu.php?modulo='+module);
            });
        </script>


    </head>
    <body>
<div id="navigationwrapleft">
</div>

<div id="jtable-main-container"  class="jtable-main-container">
</div>
<div id="loading"></div>
</body>
</html>
