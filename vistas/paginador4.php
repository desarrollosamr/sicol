<!DOCTYPE HTML>
<html>
    <head>
        <meta charset="utf-8">
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
				var codigo = decodeURIComponent($.urlParam('Id'));
				var campo = decodeURIComponent($.urlParam('campo'));
				var filtro = decodeURIComponent($.urlParam('filtro'));
				var module = decodeURIComponent($.urlParam('modulo'));
				var progid = decodeURIComponent($.urlParam('progid'));
				var idprog = decodeURIComponent($.urlParam('idprog'));
				var tipo = decodeURIComponent($.urlParam('tipo'));
                var malo  = decodeURIComponent($.urlParam('malo'));
                var fecha  = decodeURIComponent($.urlParam('fecha'));
				var x = decodeURIComponent($.urlParam('x'));
				var turno = decodeURIComponent($.urlParam('turno'));
				function loading_show(){
                    $('#loading').html("<img src='../Imagenes/ajax-loader.gif' />").fadeIn('fast');
                }
                function loading_hide(){
                    $('#loading').fadeOut('fast');
                }                
                function loadData(page, codigo, campo, filtro, tipo){
                    loading_show();                    
                    $.ajax
                    ({
                        type: "POST",
                        url: tabla,
                        data: "page="+page+"&codigo="+codigo+"&modulo="+module+"&progid="+progid+"&x="+x+"&idprog="+idprog+"&campo="+campo+"&filtro="+filtro+"&tipo="+tipo+"&malo="+malo+"&fecha="+fecha+"&turno="+turno,
                        success: function(msg)
                        {
                            $("#jtable-main-container").ajaxComplete(function(event, request, settings)
                            {
                                loading_hide();
                                $("#jtable-main-container").html(msg);
                            });
                        }
                    });
                }
                loadData(1, codigo, campo, filtro,tipo);  // For first time page load default results
                $('#divpagination li.active').live('click',function(){
                    var page = $(this).attr('p');
					if (typeof codigo == 'undefined'){
						codigo = ""}
					if (typeof campo == 'undefined'){
						campo = ""}
					if (typeof tipo == 'undefined'){
						tipo = ""}
                    loadData(page, codigo, campo,flitro,tipo);
                    
                });           
                $('#go_btn').live('click',function(){
                    var page = parseInt($('.goto').val());
                    var no_of_pages = parseInt($('.total').attr('a'));
                    if(page != 0 && page <= no_of_pages){
                        loadData(page, codigo, campo, filtro);
                    }else{
                        alert('Digite una pagina entre 1 y '+no_of_pages);
                        $('.goto').val("").focus();
                        return false;
                    }
                    
                });
                $('#filtrar').live('click',function(){
	                codigo = $('.criterio').val();
                    loadData(1, codigo, campo, filtro);
                });
                $('#filtrobodega').live('change',function(){
	                filtro = $('#filtrobodega').val();
                    loadData(1, codigo, campo, filtro);
                });
				$("#navigationwrapleft").load('../vistas/muestramenu.php?modulo='+module);
            });
        </script>


    </head>
    <body>

<div id="jtable-main-container"  class="jtable-main-container">
</div>
<div id="loading"></div>
</body>
</html>
