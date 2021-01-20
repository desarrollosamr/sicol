<?php
session_start();
if (!isset($_SESSION['user'])){
	header("location:../index.php?x=2");
} else {
	$univel=$_SESSION['nivel'];
	$unombre=$_SESSION['user'];
	$uid=$_SESSION['userid'];
}
?>
<div id="headerwrap">
    <div id="header">
        <p><img src="../Imagenes/SICOL.jpg" width="986px" /></p>
    </div>
</div>
<div id="navigationwrap">
    <div id="navigation">
        <ul id="nav">
      <?php if ($_SESSION['nivel']!=4){ ?>
            <li><a href="#">Operaciones</a>
                <ul>
                    <li><a href=../Plantilla/vistaPrincipal.php?pg=../vistas/paginador1.php&lista=../vistas/turnos_data.php&modulo=operaciones>Enturnamiento</a></li>
		<?php if ($_SESSION['nivel']!=3){ 
                if ($_SESSION['nivel']!=5){?>
                    <li><a href=../Plantilla/vistaPrincipal.php?pg=../vistas/paginador1.php&lista=../vistas/despachos_data.php&tipo=s&modulo=operaciones>Despachos</a></li>
                    <li><a href="#">Produccion</a>
                    	<ul>
                    		<li><a href=../Plantilla/vistaPrincipal.php?pg=../vistas/paginador1.php&lista=../vistas/ensaques_data.php>Ensaque de Simples</a></li>
                    	</ul>
                    </li>
                    <li><a href=../Plantilla/vistaPrincipal.php?pg=../vistas/paginador1.php&lista=../vistas/despachos_data.php&tipo=e&modulo=operaciones>Recibos de productos</a></li>				
                    <li><a href=../Plantilla/vistaPrincipal.php?pg=../vistas/paginador1.php&lista=../vistas/traslados_data.php&modulo=operaciones>Traslados</a></li>				
        <?php } ?>                
        <?php } ?>                
        		</ul>
            </li>

            <li><a href="#">Inventario</a>
                <ul>
		<?php if ($_SESSION['nivel']==1){ ?>
                    <li><a href=../Plantilla/vistaPrincipal.php?pg=../vistas/paginador1.php&lista=../vistas/saldos_iniciales_data.php&modulo=inventario&filtro=0>Saldos iniciales</a></li>
                    <li><a href=../Plantilla/vistaPrincipal.php?pg=../vistas/paginador1.php&lista=../vistas/ajustes_data.php&modulo=inventario&filtro=0>Ajustes</a></li>                    
        <?php } ?>
        <?php if ($_SESSION['nivel']==3){ ?>
                    <li><a href=../Plantilla/vistaPrincipal.php?pg=../vistas/paginador1.php&lista=../vistas/existencias_data.php&modulo=inventario&filtro=0>Existencias</a></li>
        <?php } else { ?>
                    <li><a href=../Plantilla/vistaPrincipal.php?pg=../vistas/paginador1.php&lista=../vistas/existencias_data.php&modulo=inventario&filtro=0>Existencias</a></li>			
		<?php } ?>
                </ul>
            </li>
            <li><a href="#">Maestros</a>
                <ul>
		<?php if ($_SESSION['nivel']==1){ ?>
                    <li><a href=../Plantilla/vistaPrincipal.php?pg=../vistas/paginador1.php&lista=../vistas/bodegas_data.php&modulo=maestros>Bodegas</a></li>
                    <li><a href=../Plantilla/vistaPrincipal.php?pg=../vistas/paginador1.php&lista=../vistas/productos_data1.php&modulo=maestros>Productos</a></li>
                    <li><a href=../Plantilla/vistaPrincipal.php?pg=../vistas/paginador1.php&lista=../vistas/clientes_data.php&modulo=maestros>Clientes</a></li>
                    <li><a href=../Plantilla/vistaPrincipal.php?pg=../vistas/paginador1.php&lista=../vistas/presentacion_data.php&modulo=maestros>Presentaciones</a></li>              

                    <li><a href=../Plantilla/vistaPrincipal.php?pg=../vistas/paginador1.php&lista=../vistas/motonaves_data.php&modulo=maestros>Motonaves</a></li>              
                    <li><a href=../Plantilla/vistaPrincipal.php?pg=../vistas/paginador1.php&lista=../vistas/consecutivos_data.php&modulo=maestros>Consecutivos</a></li>              
                    <li><a href=../Plantilla/vistaPrincipal.php?pg=../vistas/paginador1.php&lista=../vistas/usuarios_data.php&modulo=maestros>Usuarios</a></li>              
		<?php } 
			if ($_SESSION['nivel']==1 OR $_SESSION['nivel']==2){
		?>
                    <li><a href=../Plantilla/vistaPrincipal.php?pg=../vistas/paginador1.php&lista=../vistas/conductores_data.php&modulo=maestros>Conductores</a></li>                               
                    <li><a href=../Plantilla/vistaPrincipal.php?pg=../vistas/paginador1.php&lista=../vistas/transportadores_data.php&modulo=maestros>Transportadores</a></li>
        <?php } ?>
                </ul>
            </li>			

             <li><a href="#">Informes</a>
                <ul>
                    <li><a href="#">Despachos</a>
                    	<ul>
						<?php if ($_SESSION['nivel']!=3){ ?>
                    		<li><a href=../Plantilla/vistaPrincipal.php?pg=../vistas/paginador1.php&lista=../vistas/frmfiltrarinformes.php&opr=despachos&tipo=s&modulo=informes&criterio=general>General</a></li>
                    		<li><a href=../Plantilla/vistaPrincipal.php?pg=../vistas/paginador1.php&lista=../vistas/frmfiltrarinformes.php&opr=despachos&tipo=s&modulo=informes&criterio=producto>Por Producto</a></li>  
                    		<li><a href=../Plantilla/vistaPrincipal.php?pg=../vistas/paginador1.php&lista=../vistas/frmfiltrarinformes.php&opr=despachos&tipo=s&modulo=informes&criterio=bodega>Por Bodega</a></li>                    		                              	<?php } ?>                  		
                    		<li><a href=../Plantilla/vistaPrincipal.php?pg=../vistas/paginador1.php&lista=../vistas/frmfiltrarinformes.php&opr=despachos&tipo=s&modulo=informes&criterio=cliente>Por Cliente</a></li>
          		
                    	</ul>
					</li>
                    <li><a href="#">Recibos</a>
                    	<ul>
						<?php if ($_SESSION['nivel']!=3){ ?>
                    		<li><a href=../Plantilla/vistaPrincipal.php?pg=../vistas/paginador1.php&lista=../vistas/frmfiltrarinformes.php&opr=despachos&tipo=e&modulo=informes&criterio=general>General</a></li>
                    		<li><a href=../Plantilla/vistaPrincipal.php?pg=../vistas/paginador1.php&lista=../vistas/frmfiltrarinformes.php&opr=despachos&tipo=e&modulo=informes&criterio=producto>Por Producto</a></li>
                    		<li><a href=../Plantilla/vistaPrincipal.php?pg=../vistas/paginador1.php&lista=../vistas/frmfiltrarinformes.php&opr=despachos&tipo=e&modulo=informes&criterio=bodega>Por Bodega</a></li>                    	
                    	<?php } ?>                  		
                    		<li><a href=../Plantilla/vistaPrincipal.php?pg=../vistas/paginador1.php&lista=../vistas/frmfiltrarinformes.php&opr=despachos&tipo=e&modulo=informes&criterio=cliente>Por Cliente</a></li>
                    	</ul>
                    </li>
                    <li><a href="#">Ensaques</a>                    
                    	<ul>
						<?php if ($_SESSION['nivel']!=3){ ?>
                    		<li><a href=../Plantilla/vistaPrincipal.php?pg=../vistas/paginador1.php&lista=../vistas/frmfiltrarinformes.php&opr=ensaques&tipo=en&modulo=informes&criterio=general>General</a></li>
                    		<li><a href=../Plantilla/vistaPrincipal.php?pg=../vistas/paginador1.php&lista=../vistas/frmfiltrarinformes.php&opr=ensaques&tipo=en&modulo=informes&criterio=producto>Por Producto</a></li>
                    		<li><a href=../Plantilla/vistaPrincipal.php?pg=../vistas/paginador1.php&lista=../vistas/frmfiltrarinformes.php&opr=ensaques&tipo=en&modulo=informes&criterio=bodega>Por Bodega</a></li>
                    	<?php } ?>                  		
                    		<li><a href=../Plantilla/vistaPrincipal.php?pg=../vistas/paginador1.php&lista=../vistas/frmfiltrarinformes.php&opr=ensaques&tipo=en&modulo=informes&criterio=cliente>Por Cliente</a></li>                    	
                    	</ul>
                    </li>
                    <li><a href="../Plantilla/vistaPrincipal.php?pg=../vistas/paginador1.php&lista=../vistas/frmfiltrarinformes.php&opr=esgral&tipo=g&modulo=informes&criterio=general">General entre fechas</a>
                    </li>
                    <li><a href="#">Rotación</a>
                        <ul>
                        <?php if ($_SESSION['nivel']!=3){ ?>
                            <li><a href=../Plantilla/vistaPrincipal.php?pg=../vistas/paginador1.php&lista=../vistas/frmfiltrarinformes.php&opr=rotacion&tipo=r&modulo=informes&criterio=producto>Por Producto</a></li>  
                            <li><a href=../Plantilla/vistaPrincipal.php?pg=../vistas/paginador1.php&lista=../vistas/frmfiltrarinformes.php&opr=rotacion&tipo=r&modulo=informes&criterio=bodega>Por Bodega</a></li>            
                        <?php } ?>                          
                            <li><a href=../Plantilla/vistaPrincipal.php?pg=../vistas/paginador1.php&lista=../vistas/frmfiltrarinformes.php&opr=rotacion&tipo=r&modulo=informes&criterio=cliente>Por Cliente</a></li>

                        </ul>
                    </li>
                    <?php if ($_SESSION['nivel']==1){ ?>
                        <li><a href=../Plantilla/vistaPrincipal.php?pg=../vistas/paginador1.php&lista=../vistas/usu_actividad_data.php&mdulo=informes>Actividad por usuario</a></li>  
                    <?php } ?>                                                         </ul>
            </li>
            <li><a href="#">Utilidades</a>
                <ul>
					<?php if ($_SESSION['nivel']==1){ ?>
			            <li><a href=../Plantilla/vistaPrincipal.php?pg=../vistas/respaldar.html>Copia de seguridad</a></li>
					<?php } ?>
                </ul>
            </li>			
          <?php } ?>
            <div>
			    <li style="float: right"><a href="#"><?php echo $unombre ?></a>
				    <ul style="left:-80px;">
				    	<li><a href="../salir.php">Cerrar sesión</a></li>
				    	<li><a href="../password_change.php">Cambiar contraseña</a></li>
				    </ul>
				</li>
            </div>
        </ul>
    </div>
</div>
