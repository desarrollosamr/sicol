<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1">
        <title>Pagination with Jquery, Ajax, PHP</title>
		<script type="text/javascript"  src="../js/prueba.js"></script>
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
				var module = decodeURIComponent($.urlParam('modulo'));
				var progid = decodeURIComponent($.urlParam('progid'));
				var x = decodeURIComponent($.urlParam('x'));
				var id = decodeURIComponent($.urlParam('id'));
				$("#content").load('../vistas/baches_data.php?progid=' + progid);
				$("#navform").load('../vistas/frmActualizarrmatpribache.php?x=' + x + '&modulo=produccion&progid=' +  progid + '&id=' + id);
            });
        </script>
		<link rel="stylesheet" type="text/css" href="../css/jtable.css" />
    </head>
    <body>
<div id="navform">
</div>
<div id="content">
</div>
</body>
</html>
