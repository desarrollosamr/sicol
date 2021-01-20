<?php
if($_POST['page'])
{
$page = $_POST['page'];
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
$prove=$_POST['proveedor'];
$criterio = $_POST['orden'];
$sqlcriterio = "select aprobada from gc_ordenes where id=" . $criterio;
$rs_sqlcriterio = mysqli_query($objConexion,$sqlcriterio) or die('MySql Error' . mysql_error());
$ordenaprobada = mysqli_fetch_array($rs_sqlcriterio);
$where = "WHERE 1=1";
 if($criterio!="")
     $where.= " AND orden LIKE '$criterio%'";
	 
$query_pag_data="SELECT  `gc_ordenes_detalle`.`id` as id,  `gc_ordenes_detalle`.`orden` as orden,  `gc_ordenes_detalle`.`tarifa` as tarifa,  `gc_tarifas`.`nombre` as tarifanombre,  `gc_tarifas`.`valor` as valor,  `gc_ordenes_detalle`.`cantidad` as cantidad, `gc_programacion_recibo`.`motonave` as nave,  `gc_motonaves`.`nombre` as navenombre,  `gc_ordenes_detalle`.`detalle` as detalle 
FROM gc_ordenes_detalle
LEFT JOIN  `gc_programacion_recibo` ON  `gc_ordenes_detalle`.`operacion` =  `gc_programacion_recibo`.`id` 
LEFT JOIN  `gc_tarifas` ON  `gc_ordenes_detalle`.`tarifa` =  `gc_tarifas`.`id` 
LEFT JOIN  `gc_motonaves` ON  `gc_programacion_recibo`.`motonave` =  `gc_motonaves`.`motonaveId` " . $where . " ORDER BY `gc_programacion_recibo`.`motonave` LIMIT $start, $per_page";


$result_pag_data = mysqli_query($objConexion,$query_pag_data) or die('MySql Error' . mysql_error());
$msg = "";
$msg .= '</script>';
$msg .= '<div class="jtable-title"><div class="jtable-title-text">Detalle de solicitud de orden de compra No. ' . $_POST['orden'];
$msg .= '<img style="float:right;padding:8px;cursor:copy" src="../Imagenes/add.png" ';
$msg .= ' onclick="location.href=\'../Plantilla/vistaPrincipal.php?pg=../vistas/paginador2.php&lista=../vistas/frmActualizarrordenesdetalle.php?x=1&proveedor=' . $prove .'&modulo=ordenes';
$msg .= '\'"></div></div>';
$msg .= '<table class="jtable">';
$msg .= '<thead>';
$msg .= '<tr>';
$msg .= '<th class="jtable-column-header" style="width:20%;">';
$msg .= '<div class="jtable-column-header-container">';
$msg .= '<span class="jtable-column-header-text">Tarifa</span>';
$msg .= '</div></th><th class="jtable-column-header" style="width:25%;"><div class="jtable-column-header-container"><span class="jtable-column-header-text">Operacion</span></div></th><th class="jtable-column-header" style="width:25%;"><div class="jtable-column-header-container"><span class="jtable-column-header-text">Detalle</span></div></th><th class="jtable-column-header" style="width:9%;"><div class="jtable-column-header-container"><span class="jtable-column-header-text">Cantidad</span></div></th><th class="jtable-column-header" style="width:9%;"><div class="jtable-column-header-container"><span class="jtable-column-header-text">Vr Unitario</span></div></th><th class="jtable-column-header" style="width:10%;"><div class="jtable-column-header-container"><span class="jtable-column-header-text">Vr Total</span></div></th><th class="jtable-command-column-header" style="width:1%;"></th><th class="jtable-command-column-header" style="width:1%;"></th></tr></thead><tbody>';
$u=0;
while ($row = mysqli_fetch_array($result_pag_data)) {
		   if ($u%2==0){ 
	$msg .= '<tr class="jtable-row-even">';
		} else {
	$msg .= '<tr class="jtable-row-evenf">';
		}
	$msg .= '<td>';
	$msg .= $row['tarifanombre'] ;
	$msg .= '</td>';
	$msg .= '<td>' ;
	$msg .= $row['navenombre'];
	$msg .= '</td>';
	$msg .= '<td>' ;
	$msg .= $row['detalle'];
	$msg .= '</td>';
	$msg .= '<td align="right">' ;
	$msg .= number_format($row['cantidad'],2);
	$msg .= '</td>';
	$msg .= '<td align="right">' ;
	$msg .= number_format($row['valor']);
	$msg .= '</td>';
	$msg .= '<td align="right">' ;
	$msg .= number_format($row['cantidad']*$row['valor']);
	$msg .= '</td>';
	$msg .= '<td class="jtable-command-column">';
	$msg .= '<button title="Actualizar registro" class="jtable-command-button jtable-edit-command-button"';
	$msg .= ' onclick="location.href=\'../Plantilla/vistaPrincipal.php?pg=../vistas/paginador2.php&lista=../vistas/frmActualizarrordenesdetalle.php?Id=';
	$msg .= $row['id'];
	$msg .= '&proveedor=';
	$msg .= $prove;
	$msg .= '&modulo=ordenes';
	$msg .= '\'">';
	$msg .= ' <span>Actualizar registro</span>';
	$msg .= '</button>';
	$msg .= '</td>';
	$msg .= '<td class="jtable-command-column">';
	$msg .= '<button title="Eliminar" class="jtable-command-button jtable-delete-command-button"';
	$msg .= ' onclick="location.href=\'../controlador/coordenesdetalle.php?boton=eliminar&amp;Id=';
	$msg .= $row['id'];
	$msg .= '\'">';
	$msg .= ' <span>Eliminar registro</span>';
	$msg .= '</button></td></tr>';
		$u++;
}
$msg .= '</tbody></table></div>';

/* --------------------------------------------- */
$query_pag_num = "SELECT COUNT(*) AS count FROM gc_ordenes_detalle " . $where;
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
if ($ordenaprobada['aprobada']==0 && $count > 0) {
	$solauto = '<button class="go_button" style="position:relative;" onclick="location.href=\'../vistas/ordenes_sol_autorizacion.php?orden=' . $criterio . '&proveedor=' . $prove . '\'" title="Exportar">Exportar</button>';
} else {
	$solauto = "";
}
$goto = "<input type='text' class='goto' size='1' style='margin-top:-1px;margin-left:60px;'/><input type='button' id='go_btn' class='go_button' value='Ir'/>";
$total_string = "<span class='total' a='$no_of_paginations'>Pagina <b>" . $cur_page . "</b> de <b>$no_of_paginations</b></span>";
$msg = $msg . "</ul>" . $solauto . $goto . $total_string . "</div>";  // Content for pagination
echo $msg;
}

