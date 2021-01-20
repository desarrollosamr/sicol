<!DOCTYPE HTML>
<html>
    <head>
        <meta charset="UTF-8">
        <title>Sistema de información para el control de operaciones logísticas</title>
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
				var tabla = decodeURIComponent($.urlParam('lista'));
				var codigo = decodeURIComponent($.urlParam('Id'));
				var campo = decodeURIComponent($.urlParam('campo'));
				var progid = decodeURIComponent($.urlParam('progid'));
				var idprog = decodeURIComponent($.urlParam('idprog'));
				var x = decodeURIComponent($.urlParam('x'));
				var module = decodeURIComponent($.urlParam('modulo'));
				var filtro = decodeURIComponent($.urlParam('filtro'));
				var filtrob = decodeURIComponent($.urlParam('filtrob'));
				var filtroc = decodeURIComponent($.urlParam('filtroc'));
				var filtrom = decodeURIComponent($.urlParam('filtrom'));
				var tipom = decodeURIComponent($.urlParam('tipo'));
				var opr = decodeURIComponent($.urlParam('opr'));
				var filtrarpor  = decodeURIComponent($.urlParam('filtrarpor'));
				var filinfo = decodeURIComponent($.urlParam('filinfo'));
                var tipomov  = decodeURIComponent($.urlParam('tipomov'));
                var filtrofecha = decodeURIComponent($.urlParam('filtrofecha'));
                var criterio = decodeURIComponent($.urlParam('criterio'));
                
				function loading_show(){
                    $('#loading').html("<img src='../Imagenes/ajax-loader.gif' />").fadeIn('fast');
                }
                function loading_hide(){
                    $('#loading').fadeOut('fast');
                }                
                function loadData(page , codigo , campo, filtro, filtrob, filtroc, filtrom, tipo, opr, filtrarpor, filinfo, tipomov, filtrofecha, criterio){
                    loading_show();  
                    $.ajax
                    ({
                        type: "POST",
                        url: tabla,
                        data: "page="+page+"&codigo="+codigo+"&modulo="+module+"&progid="+progid+"&x="+x+"&idprog="+idprog+"&campo="+campo+"&filtro="+filtro+"&filtrob="+filtrob+"&filtroc="+filtroc+"&filtrom="+filtrom+"&tipo="+tipom+"&opr="+opr+"&filtrarpor="+filtrarpor+"&filinfo="+filinfo+"&tipomov="+tipomov+"&filtrofecha="+filtrofecha+"&criterio="+criterio,
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
                loadData(1, codigo, campo, filtro, filtrob, filtroc, filtrom, tipom, opr, filtrarpor, filinfo, tipomov, filtrofecha, criterio); 
                $('#divpagination li.active').live('click',function(){
                    var page = $(this).attr('p');
					if (typeof codigo == 'undefined'){
						codigo = ""}
					if (typeof filtro == 'undefined'){
						filtro = 0}
					if (typeof filtrob == 'undefined'){
						filtrob = 0}
					if (typeof filtroc == 'undefined'){
						filtroc = 0}
					if (typeof filtrom == 'undefined'){
						filtrom = 0}
					if (typeof campo == 'undefined'){
						campo = ""}
					if (typeof tipom == 'undefined'){
						tipom = ""}
					if (typeof criterio == 'undefined'){
						criterio = ""}
					if (typeof opr == 'undefined'){
						opr = ""}
					if (typeof filtrarpor == 'undefined'){
						filtrarpor = 0}
					if (typeof filinfo == 'undefined'){
						filinfo = "general"}
                    if (typeof tipomov == 'undefined'){
                        tipomov = "1"}
                    if (typeof filtrofecha == 'undefined'){
                        filtrofecha = "0000-00-00"}
					loadData(page, codigo, campo, filtro, filtrob, filtroc, filtrom, tipom, opr, filtrarpor, filinfo, tipomov, filtrofecha, criterio);
                });           
                $('#go_btn').live('click',function(){
                    var page = parseInt($('.goto').val());
                    var no_of_pages = parseInt($('.total').attr('a'));
                    if(page != 0 && page <= no_of_pages){
                        loadData(page, criterio);
                    }else{
                        alert('Digite una pagina entre 1 y '+no_of_pages);
                        $('.goto').val("").focus();
                        return false;
                    }
                });
                $('#criterio').live('change',function(){
	                filinfo = $('#criterio').val();
                    loadData(1, codigo, campo, filtro, filtrob, filtroc, filtrom, tipom, opr, filtrarpor, filinfo);
                });
                $('#filtroproducto').live('change',function(){
	                filtro = $('#filtroproducto').val();
                    loadData(1, codigo, campo, filtro, filtrob, filtroc);
                });
                $('#filtrobodega').live('change',function(){
	                filtrob = $('#filtrobodega').val();
                    loadData(1, codigo, campo, filtro, filtrob, filtroc);
                });
                $('#filtrocliente').live('change',function(){
	                filtroc = $('#filtrocliente').val();
                    loadData(1, codigo, campo, filtro, filtrob, filtroc);
                });
                $('#filtromoto').live('change',function(){
	                filtrom = $('#filtromoto').val();
                    loadData(1, codigo, campo, filtro, filtrob, filtroc, filtrom);
                });
                $('#resetear').live('click',function(){
	                filtroc = 0;
	                filtrom = 0;
	                filtro = 0;
	                filtrob = 0;
                    loadData(1, codigo, campo, filtro, filtrob, filtroc, filtrom);
                });
                $('#fechaexistencia').live('change',function(){
                    if (typeof tipo == 'undefined'){
                        tipo = ""}
                    filtrofecha = $('#fechaexistencia').val();
                    loadData(1, codigo, campo, filtro, filtrob, filtroc, filtrom, tipo, opr, filtrarpor, filinfo, tipomov, filtrofecha);
                });
                $('#movimientos').live('change',function(){
                    tipomov = $('#movimientos').val();
                    loadData(1, codigo, campo, filtro, filtrob, filtroc, filtrom, tipom, opr, filtrarpor, filinfo, tipomov, filtrofecha);
                });                
                $("#navigationwrapleft").load('../vistas/muestramenu.php?modulo='+module);
                 $(".jtable-more-command-button").live('click',function(){
                    var clave=$(this).attr('data-id');
                    if ($( '#tr'+clave ).css('display') == 'none'){
                        $( '#tr'+clave ).css( 'display', 'table-row' );
                    } else {
                        $( '#tr'+clave ).css( 'display', 'none' );
                    }
                });
                $('#despachar').live('click',function(){
					var xhr = new XMLHttpRequest();
					xhr.open("GET", "https://platform.clickatell.com/messages/http/send?apiKey=LpUIxW7WTc6QkaUWXWWM8g==&to=573233656749&content=Test+message+text", true);
					xhr.onreadystatechange = function(){
					    if (xhr.readyState == 4 && xhr.status == 200) {
					        console.log('success');
					    }
					};
					xhr.send();
                });
           });
        </script>
    </head>
    <body>
<div id="jtable-main-container"  class="jtable-main-container"></div>
<div id="loading"></div>
</body>
</html>