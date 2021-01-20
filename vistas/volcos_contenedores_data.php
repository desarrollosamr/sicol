<?php 
extract($_REQUEST);
if($_REQUEST['page'])
{
$page = $_REQUEST['page'];
$cur_page = $page;
$page -= 1;
$per_page = 10;
$previous_btn = true;
$next_btn = true;
$first_btn = true;
$last_btn = true;
$start = $page * $per_page;

include"../clases/ConexionDatos.php";
$objConexion=Conectarse();
$criterio = $_REQUEST['codigo'];
$filtro = $_REQUEST['filtro'];
if ($_REQUEST['campo']!=""){
	$campo = $_REQUEST['campo'];
}else{
	$campo="";
}

$where = "WHERE 1=1";
 if($filtro==0){
     $where.= " AND gc_recibo_buques_contenedores.programacionid LIKE '$criterio'";
 }else{
     $where.= " AND gc_recibo_buques_contenedores.programacionid LIKE '$criterio' AND gc_recibo_buques_contenedores.bodega LIKE '$filtro'";
 }
	 
	 
$query_pag_data="SELECT  `gc_recibo_buques_contenedores`.`id` as id, `gc_recibo_buques_contenedores`.`programacionid` as proximo,  `gc_recibo_buques_contenedores`.`fecha` as fecha, `gc_recibo_buques_contenedores`.`producto` as producto,  `gc_productos`.`nombre` as prodnombre, `gc_presentacion`.`kilos` as kilos, `gc_bodegas`.`nombre` as bodnombre, `gc_recibo_buques_contenedores`.`tiquete` as tiquete,  `gc_recibo_buques_contenedores`.`placas` as placas,  `gc_recibo_buques_contenedores`.`peso_origen` as pesorigen,`gc_recibo_buques_contenedores`.`peso_bascula` as pesbascula FROM gc_recibo_buques_contenedores LEFT JOIN  `gc_productos` ON `gc_recibo_buques_contenedores`.`producto` =  `gc_productos`.`codigo` LEFT JOIN  `gc_presentacion` ON `gc_recibo_buques_contenedores`.`presentacion` =  `gc_presentacion`.`id` LEFT JOIN  `gc_bodegas` ON `gc_recibo_buques_contenedores`.`bodega` =  `gc_bodegas`.`id` " . $where . " ORDER BY `gc_recibo_buques_contenedores`.`fecha` desc LIMIT $start, $per_page";
$result_pag_data = mysqli_query($objConexion,$query_pag_data) or die('MySql Error' . mysql_error());

$topo = "select sum(peso_origen) as tpo from gc_recibo_buques_contenedores " . $where;
$rstopo = mysqli_query($objConexion,$topo) or die('MySql Error' . mysql_error());
$rtopo = mysqli_fetch_array($rstopo);
$topb = "select sum(peso_bascula) as tpb from gc_recibo_buques_contenedores " . $where;
$rstopb = mysqli_query($objConexion,$topb) or die('MySql Error' . mysql_error());
$rtopb = mysqli_fetch_array($rstopb);
$bore = "select distinct gc_recibo_buques_contenedores.bodega as bodegaid, gc_bodegas.nombre as bodeganombre from gc_recibo_buques_contenedores left join gc_bodegas on gc_recibo_buques_contenedores.bodega = gc_bodegas.id where gc_recibo_buques_contenedores.programacionid=" . $criterio . " order by gc_bodegas.nombre";
$rbore = mysqli_query($objConexion,$bore) or die('Error' . mysql_error());


$msg = "";
$msg .= '</script>';
$msg .= '<div class="jtable-title"><div class="jtable-title-text">Detalle de recibo operacion No. ' . $criterio;
$msg .= '<img style="float:right;padding:8px;cursor:copy" src="../Imagenes/add.png" ';
$msg .= ' onclick="location.href=\'../Plantilla/vistaPrincipal.php?pg=../vistas/paginador4.php&lista=../vistas/frmActualizarrvolcos.php?x=1&progid=' . $criterio .'&modulo=recibo';
$msg .= '\'"></div></div>';
$msg .= '<table class="jtable">';
$msg .= '<thead>';
$msg .= '<tr>';
$msg .= '<th class="jtable-column-header" style="width:29%;"><div class="jtable-column-header-container">';
$msg .= '<span class="jtable-column-header-text">Producto</span></div></th>';
$msg .= '<th class="jtable-column-header" style="width:11%;"><div class="jtable-column-header-container">';
$msg .= '<span class="jtable-column-header-text">Present-Kls</span></div></th>';
$msg .= '<th class="jtable-column-header" style="width:10%;"><div class="jtable-column-header-container">';
$msg .= '<span class="jtable-column-header-text">Fecha</span></div></th>';
$msg .= '<th class="jtable-column-header" style="width:11%;"><div class="jtable-column-header-container">';
$msg .= '<span class="jtable-column-header-text">';
$msg .= '<select class="criterio" id="filtrobodega"><option value="0">Bodega</option><option value="0" style="background-color:#FFCC66">Resetear</option>';
while ($rsbore = mysqli_fetch_array($rbore)) {
	$msg .= '<option  value="' . $rsbore["bodegaid"] . '">' . $rsbore["bodeganombre"] . '</option>';
	}
$msg .= '</select></span></div></th>';
$msg .= '<th class="jtable-column-header" style="width:7%;"><div class="jtable-column-header-container">';
$msg .= '<span class="jtable-column-header-text">Tiquete</span></div></th>';
$msg .= '<th class="jtable-column-header" style="width:7%;"><div class="jtable-column-header-container">';
$msg .= '<span class="jtable-column-header-text">Placas</span></div></th>';
$msg .= '<th class="jtable-column-header" style="width:11%;"><div class="jtable-column-header-container">';
$msg .= '<span class="jtable-column-header-text">Peso Origen</span></div></th>';
$msg .= '<th class="jtable-column-header" style="width:12%;"><div class="jtable-column-header-container">';
$msg .= '<span class="jtable-column-header-text">Peso Bascula</span></div></th>';
$msg .= '<th class="jtable-command-column-header" style="width:1%;"></th>';
$msg .= '<th class="jtable-command-column-header" style="width:1%;"></th></tr></thead><tbody>';
$msg .= '<tr class="jtable-row-total"><td colspan="6" align="center">Totales generales</td><td align="right">' . $rtopo['tpo'] . '</td><td align="right">' . $rtopb['tpb'] . '</td></tr>';
$u=0;
while ($row = mysqli_fetch_array($result_pag_data)) {
		   if ($u%2==0){ 
	$msg .= '<tr class="jtable-row-even">';
		} else {
	$msg .= '<tr class="jtable-row-evenf">';
		}
	$msg .= '<td>';
	$msg .= $row['prodnombre'] ;
	$msg .= '</td>';
	$msg .= '<td align="right">' ;
	$msg .= $row['kilos'];
	$msg .= '</td>';
	$msg .= '<td>';
	$msg .= $row['fecha'] ;
	$msg .= '</td>';
	$msg .= '<td>';
	$msg .= $row['bodnombre'] ;
	$msg .= '</td>';
	$msg .= '<td align="right">' ;
	$msg .= $row['tiquete'];
	$msg .= '</td>';
	$msg .= '<td>';
	$msg .= $row['placas'] ;
	$msg .= '</td>';
	$msg .= '<td align="right">' ;
	$msg .= $row['pesorigen'];
	$msg .= '</td>';
	$msg .= '<td align="right">' ;
	$msg .= $row['pesbascula'];
	$msg .= '</td>';
	$msg .= '<td class="jtable-command-column">';
	$msg .= '<button title="Recibo de carros" class="jtable-command-button jtable-edit-command-button"';
	$msg .= ' onclick="location.href=\'../Plantilla/vistaPrincipal.php?pg=../vistas/paginador4.php&lista=../vistas/frmActualizarrvolcos.php?Id=';
	$msg .= $row['id'];
	$msg .= '&progid=';
	$msg .= $criterio;
	$msg .= '&modulo=recibo';
	$msg .= '\'">';
	$msg .= ' <span>Actualizar registro</span>';
	$msg .= '</button>';
	$msg .= '</td>';
	$msg .= '<td class="jtable-command-column">';
	$msg .= '<button title="Eliminar" class="jtable-command-button jtable-delete-command-button"';
	$msg .= ' onclick="location.href=\'../controlador/covolcos.php?boton=eliminar&amp;Id=';
	$msg .= $row['id'];
	$msg .= '&progid=';
	$msg .= $criterio;
	$msg .= '\'">';
	$msg .= ' <span>Eliminar registro</span>';
	$msg .= '</button></td></tr>';
		$u++;
}
$msg .= '</tbody></table></div>';

/* --------------------------------------------- */
$query_pag_num = "SELECT COUNT(*) AS count FROM gc_recibo_buques_contenedores " . $where;
$result_pag_num = mysqli_query($objConexion,$query_pag_num);
$row = mysqli_fetch_assoc($result_pag_num);
$count = $row['count'];
//$count = mysqli_num_rows($result_pag_data);
$no_of_paginations = ceil($count / $per_page);

/* ---------------Calculating the starting and endign values for the loop----------------------------------- */
if ($cur_page >= 7) {
    $start_loop = $cur_page - 3;
    if ($no_of_paginations > $cur_page + 3)
        $end_loop = $cur_page + 3;
    else if ($cur_page <= $no_of_paginations && $cur_page > $no_of_paginations - 6) {
        $start_loop = $no_of_paginations - 6;
        $end_loop = $no_of_paginations;
    } else {
        $end_loop = $no_of_paginations;
    }
} else {
    $start_loop = 1;
    if ($no_of_paginations > 7)
        $end_loop = 7;
    else
        $end_loop = $no_of_paginations;
}
/* ----------------------------------------------------------------------------------------------------------- */
$msg .= "<div class='pagination' id='divpagination'><ul>";

// FOR ENABLING THE FIRST BUTTON
if ($first_btn && $cur_page > 1) {
    $msg .= "<li p='1' class='active'>Primera</li>";
} else if ($first_btn) {
    $msg .= "<li p='1' class='inactive'>Primera</li>";
}

// FOR ENABLING THE PREVIOUS BUTTON
if ($previous_btn && $cur_page > 1) {
    $pre = $cur_page - 1;
    $msg .= '<li p=' . $pre . ' class="active">Anterior</li>';
} else if ($previous_btn) {
    $msg .= '<li class="inactive">Anterior</li>';
}
for ($i = $start_loop; $i <= $end_loop; $i++) {

    if ($cur_page == $i)
        $msg .= '<li p=' . $i . ' style="color:#fff;background-color:#006699;" class="active">' . $i . '</li>';
    else
        $msg .= '<li p=' . $i . ' class="active">' . $i . '</li>';
}

// TO ENABLE THE NEXT BUTTON
if ($next_btn && $cur_page < $no_of_paginations) {
    $nex = $cur_page + 1;
    $msg .= '<li p=' . $nex . ' class="active">Siguiente</li>';
} else if ($next_btn) {
    $msg .= '<li class="inactive">Siguiente</li>';
}

// TO ENABLE THE END BUTTON
if ($last_btn && $cur_page < $no_of_paginations ) {
    $msg .= '<li p=' . $no_of_paginations . ' class="active">Ultima</li>';
} else if ($last_btn) {
    $msg .= '<li p=' . $no_of_paginations . ' class="inactive">Ultima</li>';
}
$exportar = '<button class="go_button" style="position:relative;" onclick="location.href=\'../vistas/informes_excel.php?criterio=' . $criterio . '&modulo=recibos\'" title="Exportar">Exportar</button>';
$goto = "<input type='text' class='goto' size='1' style='margin-top:-1px;margin-left:60px;'/><input type='button' id='go_btn' class='go_button' value='Ir'/>";
$total_string = "<span class='total' a='$no_of_paginations'>Pagina <b>" . $cur_page . "</b> de <b>$no_of_paginations</b></span>";
$msg = $msg . "</ul>" . $exportar . $goto . $total_string . "</div>";  // Content for pagination
echo $msg;
}

