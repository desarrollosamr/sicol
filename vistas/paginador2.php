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
				var tabla = decodeURIComponent($.urlParam('lista'));
				var acorden='<?php echo isset($_SESSION["orden"]) ? $_SESSION["orden"] : 0; ?>';
				var prove=decodeURIComponent($.urlParam('proveedor'));
				var module = decodeURIComponent($.urlParam('modulo'));
				function loading_show(){
                    $('#loading').html("<img src='../Imagenes/ajax-loader.gif' />").fadeIn('fast');
                }
                function loading_hide(){
                    $('#loading').fadeOut('fast');
                }                
                function loadData(page, orden){
                    loading_show();                    
                    $.ajax
                    ({
                        type: "POST",
                        url: tabla,
                        data: "page="+page+"&orden="+acorden+"&proveedor="+prove,
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
                loadData(1, acorden);  // For first time page load default results
                $('#divpagination li.active').live('click',function(){
                    var page = $(this).attr('p');
					if (typeof acorden == 'undefined'){
						acorden = ""}
                    loadData(page, acorden);
                    
                });           
                $('#go_btn').live('click',function(){
                    var page = parseInt($('.goto').val());
                    var no_of_pages = parseInt($('.total').attr('a'));
                    if(page != 0 && page <= no_of_pages){
                        loadData(page, acorden);
                    }else{
                        alert('Digite una pagina entre 1 y '+no_of_pages);
                        $('.goto').val("").focus();
                        return false;
                    }
                    
                });
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