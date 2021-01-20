<?php
extract ($_REQUEST);
$menu=$_REQUEST['modulo'];
switch ($menu) {
	Case 'empaque':
		echo '<div id="navigationleft">
		<div id="titulo">Empaque</div>
			<ul id="nav">
				<li><a href=../Plantilla/vistaPrincipal.php?pg=../vistas/paginador1.php&lista=../vistas/emp_entradas_data.php&modulo=empaque>Entradas</a></li>
				<li><a href=../Plantilla/vistaPrincipal.php?pg=../vistas/paginador1.php&lista=../vistas/emp_salidas_data.php&modulo=empaque>Salidas</a></li>
				<li><hr></li>
				<li><a href=../Plantilla/vistaPrincipal.php?pg=../vistas/paginador1.php&lista=../vistas/empaques_data.php&modulo=empaque>Grados</a></li>
				<li><a href=../Plantilla/vistaPrincipal.php?pg=../vistas/paginador1.php&lista=../vistas/productos_data1.php&modulo=empaque>Productos</a></li>
				<li><a href=../Plantilla/vistaPrincipal.php?pg=../vistas/paginador1.php&lista=../vistas/bodegas_data.php&modulo=empaque>Bodegas</a></li>
			</ul>
        </div>';
		break;

	Case 'traslados':
		echo '<div id="navigationleft">
		<div id="titulo">Traslados</div>
			<ul id="nav">
				<li><a href=../Plantilla/vistaPrincipal.php?pg=../vistas/paginador1.php&lista=../vistas/traslados_data.php&modulo=traslados>Traslados</a></li>
				<li><hr></li>
				<li><a href=../Plantilla/vistaPrincipal.php?pg=../vistas/paginador1.php&lista=../vistas/productos_data1.php&modulo=empaque>Productos</a></li>
				<li><a href=../Plantilla/vistaPrincipal.php?pg=../vistas/paginador1.php&lista=../vistas/bodegas_data.php&modulo=empaque>Bodegas</a></li>
			</ul>
        </div>';
		break;


	Case 'operaciones':
		echo '<div id="navigationleft">
		<div id="titulo">Operaciones</div>
			<ul id="nav">
                    <li><a href=../Plantilla/vistaPrincipal.php?pg=../vistas/paginador1.php&lista=../vistas/despachos_data.php&tipo=s&modulo=operaciones>Despachos</a></li>
                    <li class="vertical"><a href=../Plantilla/vistaPrincipal.php?pg=../vistas/paginador1.php&lista=../vistas/ensaques_data.php&modulo=operaciones>Ensaques</a></li>
                    <li><a href=../Plantilla/vistaPrincipal.php?pg=../vistas/paginador1.php&lista=../vistas/despachos_data.php&tipo=e&modulo=operaciones>Recibos de productos</a></li>				
			</ul>
		</div>';
		break;

	Case 'produccion':
		echo '<div id="navigationleft">
		<div id="titulo">Produccion</div>
			<ul id="nav">
				<li><a href=../Plantilla/vistaPrincipal.php?pg=../vistas/paginador1.php&lista=../vistas/produccion_data.php&modulo=produccion>Produccion</a></li>
				<li><a href=../Plantilla/vistaPrincipal.php?pg=../vistas/paginador1.php&lista=../vistas/consumos_data.php&modulo=produccion>Consumos</a></li>
				<li><a href=../Plantilla/vistaPrincipal.php?pg=../vistas/paginador1.php&lista=../vistas/productos_data1.php&modulo=recibo>Productos</a></li>
				<li><a href=../Plantilla/vistaPrincipal.php?pg=../vistas/paginador1.php&lista=../vistas/empaques_data.php&modulo=empaque>Grados</a></li>
			</ul>
		</div>';
		break;

	Case 'recibo':
		echo '<div id="navigationleft">
		<div id="titulo">Recibo</div>
			<ul id="nav">
				<li><a href=../Plantilla/vistaPrincipal.php?pg=../vistas/paginador1.php&lista=../vistas/programacion_data.php&modulo=recibo>Programacion</a></li>
				<li><a href=../Plantilla/vistaPrincipal.php?pg=../vistas/paginador1.php&lista=../vistas/productos_data1.php&modulo=recibo>Productos</a></li>
				<li><a href=../Plantilla/vistaPrincipal.php?pg=../vistas/paginador1.php&lista=../vistas/motonaves_data.php&modulo=recibo>Motonaves</a></li>
				<li><a href=../Plantilla/vistaPrincipal.php?pg=../vistas/paginador1.php&lista=../vistas/bodegas_data.php&modulo=recibo>Bodegas</a></li>
			</ul>
		</div>';
		break;

	Case 'despachos':
		echo '<div id="navigationleft">
		<div id="titulo">Despachos</div>
			<ul id="nav">
				<li><a href=../Plantilla/vistaPrincipal.php?pg=../vistas/paginador1.php&lista=../vistas/despachos_data.php&modulo=despachos>Producto</a></li>
				<li><a href=../Plantilla/vistaPrincipal.php?pg=../vistas/paginador1.php&lista=../vistas/productos_data1.php&modulo=despachos>Productos</a></li>
				<li><a href=../Plantilla/vistaPrincipal.php?pg=../vistas/paginador1.php&lista=../vistas/bodegas_data.php&modulo=despachos>Bodegas</a></li>
			</ul>
		</div>';
		break;

Case 'varios':
		echo '<div id="navigationleft">
		<div id="titulo">Varios</div>
			<ul id="nav">
				<li><a href=../Plantilla/vistaPrincipal.php?pg=../vistas/paginador1.php&lista=../vistas/varios_data.php&modulo=varios>Varios</a></li>
			</ul>
		</div>';
		break;
		
	Case 'ensaques':
		echo '<div id="navigationleft">
		<div id="titulo">Ensaques</div>
			<ul id="nav">
				<li><a href=../Plantilla/vistaPrincipal.php?pg=../vistas/paginador1.php&lista=../vistas/ensaques_data.php&modulo=ensaques>Ensaque</a></li>
				<li><a href=../Plantilla/vistaPrincipal.php?pg=../vistas/paginador1.php&lista=../vistas/productos_data1.php&modulo=ensaques>Productos</a></li>
				<li><a href=../Plantilla/vistaPrincipal.php?pg=../vistas/paginador1.php&lista=../vistas/bodegas_data.php&modulo=ensaques>Bodegas</a></li>
			</ul>
		</div>';
		break;
		
	Case 'inventario':
		echo '<div id="navigationleft">
		<div id="titulo">Inventario</div>
			<ul id="nav">
				<li><a href=../Plantilla/vistaPrincipal.php?pg=../vistas/paginador1.php&lista=../vistas/saldos_iniciales_data.php&modulo=inventario&filtro=0>Saldos iniciales</a></li>
                    <li><a href=../Plantilla/vistaPrincipal.php?pg=../vistas/paginador1.php&lista=../vistas/existencias_data.php&modulo=inventario&filtro=0>Existencias</a></li>				
			</ul>
		</div>';
		break;
		
	Case 'maestros':
		echo '<div id="navigationleft">
		<div id="titulo">Maestros</div>
		    <ul id="nav">
                <li><a href=../Plantilla/vistaPrincipal.php?pg=../vistas/paginador1.php&lista=../vistas/bodegas_data.php&modulo=maestros>Bodegas</a></li>
                <li><a href=../Plantilla/vistaPrincipal.php?pg=../vistas/paginador1.php&lista=../vistas/productos_data1.php&modulo=maestros>Productos</a></li>
                <li><a href=../Plantilla/vistaPrincipal.php?pg=../vistas/paginador1.php&lista=../vistas/clientes_data.php&modulo=maestros>Clientes</a></li>
                <li><a href=../Plantilla/vistaPrincipal.php?pg=../vistas/paginador1.php&lista=../vistas/transportadores_data.php&modulo=maestros>Transportadores</a></li>
                <li><a href=../Plantilla/vistaPrincipal.php?pg=../vistas/paginador1.php&lista=../vistas/presentacion_data.php&modulo=maestros>Presentaciones</a></li>
            </ul>
        </div>';
        break;
      
	Case 'informes':
		echo '<div id="navigationleft">
		<div id="titulo">Informes</div>
		    <ul id="nav">
                    <li><a href=../Plantilla/vistaPrincipal.php?pg=../vistas/frmfiltrarinformes.php&opr=despachos&tipo=s&modulo=informes>Despachos</a></li>
                    <li><a href=../Plantilla/vistaPrincipal.php?pg=../vistas/frmfiltrarinformes.php&opr=despachos&tipo=e&modulo=informes>Recibos</a></li>
                    <li><a href=../Plantilla/vistaPrincipal.php?pg=../vistas/frmfiltrarinformes.php&opr=ensaques&modulo=informes>Ensaques</a></li>
            </ul>
        </div>';
        break;
}
?>