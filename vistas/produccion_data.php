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

if(isset($_POST['criterio']))
{
     $criterio = $_POST['criterio'];
}else {
     $criterio = "";
}
$where = "WHERE 1=1";
 if($criterio!="") {
 	 $ano = date("Y");
     $where .= " AND year(fecha)='$ano' AND month(fecha)='$criterio'";
 }
include"../clases/ConexionDatos.php";
$objConexion=Conectarse();

$listaempaques = "select codigo, nombre from gc_empaques order by nombre";
$rs_empaques = mysqli_query($objConexion,$listaempaques) or die('MySql Error' . mysql_error());
$productos = "select productosId,codigo, nombre from gc_productos order by nombre";
$rsproductos = $objConexion->query($productos);


$query_pag_data = "SELECT gc_produccion.id as codigo, gc_produccion.fecha as fecha, gc_produccion.orden as orden, gc_produccion.acta as acta, gc_produccion.cantidad_a_reportar as tm, gc_produccion.turno as turno, gc_empaques.nombre as empaque, gc_productos.nombre as producto, gc_produccion.observaciones as observaciones, gc_produccion.empaques_cantidad as empaques from gc_produccion left join gc_empaques on gc_produccion.empaque = gc_empaques.codigo left join gc_productos on gc_produccion.producto = gc_productos.codigo " . $where . " order by gc_produccion.acta desc LIMIT $start, $per_page";
$result_pag_data = mysqli_query($objConexion,$query_pag_data) or die('MySql Error' . mysql_error());
$msg = "";

$msg .= '<div class="jtable-title"><div class="jtable-title-text">Actas de Produccion<select class="criterio">';
$msg .= '<option  value="01">Enero</option>';
$msg .= '<option  value="02">Febrero</option>';
$msg .= '<option  value="03">Marzo</option>';
$msg .= '<option  value="04">Abril</option>';
$msg .= '<option  value="05">Mayo</option>';
$msg .= '<option  value="06">Junio</option>';
$msg .= '<option  value="07">Julio</option>';
$msg .= '<option  value="08">Agosto</option>';
$msg .= '<option  value="09">Septiembre</option>';
$msg .= '<option  value="10">Octubre</option>';
$msg .= '<option  value="11">Noviembre</option>';
$msg .= '<option  value="12">Diciembre</option>';
$msg .= '</select><input type="button" id="filtrar" class="go_filtro" value="Buscar"/><input type="button"  onclick="location.href=\'../Plantilla/vistaPrincipal.php?pg=../vistas/paginador1.php&lista=../vistas/produccion_data.php?modulo=produccion';
$msg .= '\'" value="Resetear"/><img style="float:right;padding:8px;cursor:copy" src="../Imagenes/add.png" ';
$msg .= ' onclick="location.href=\'../Plantilla/vistaPrincipal.php?pg=../vistas/paginador1.php&lista=../vistas/frmActualizarractas.php?x=1&modulo=produccion';
$msg .= '\'"></div></div>';
$msg .= '<table class="jtable">';
$msg .= '<thead>';
$msg .= '<tr>';
$msg .= '<th class="jtable-column-header" style="width:10%;">';
$msg .= '<div class="jtable-column-header-container"><span class="jtable-column-header-text">';
$msg .= 'Fecha';
$msg .= '</span></div></th>';
$msg .= '<th class="jtable-column-header" style="width:10%;">';
$msg .= '<div class="jtable-column-header-container"><span class="jtable-column-header-text">';
$msg .= 'Orden';
$msg .= '</span></div></th>';
$msg .= '<th class="jtable-column-header" style="width:10%;">';
$msg .= '<div class="jtable-column-header-container"><span class="jtable-column-header-text">';
$msg .= 'Acta';
$msg .= '</span></div></th>';
$msg .= '<th class="jtable-column-header" style="width:29%;">';
$msg .= '<div class="jtable-column-header-container"><span class="jtable-column-header-text">';
$msg .= 'Producto';
$msg .= '</span></div></th>';
$msg .= '<th class="jtable-column-header" style="width:11%;">';
$msg .= '<div class="jtable-column-header-container"><span class="jtable-column-header-text">';
$msg .= 'Cantidad';
$msg .= '</span></div></th>';
$msg .= '<th class="jtable-column-header" style="width:29%;">';
$msg .= '<div class="jtable-column-header-container"><span class="jtable-column-header-text">';
$msg .= 'Empaque';
$msg .= '</span></div></th>';
$msg .= '<th class="jtable-command-column-header" style="width:1%;"></th></tr></thead><tbody>';

$u=0;
while ($row = mysqli_fetch_array($result_pag_data)) {
		   if ($u%2==0){ 
	$msg .= '<tr class="jtable-row-even">';
		} else {
	$msg .= '<tr class="jtable-row-evenf">';
		}
	$msg .= '<td>';
	$msg .= $row['fecha'] ;
	$msg .= '</td>';
	$msg .= '<td>' ;
	$msg .= $row['orden'];
	$msg .= '</td>';
	$msg .= '<td>' ;
	$msg .= $row['acta'];
	$msg .= '</td>';
	$msg .= '<td>' ;
	$msg .= $row['producto'];
	$msg .= '</td>';
	$msg .= '<td align="right">' ;
	$msg .= $row['tm'];
	$msg .= '</td>';
	$msg .= '<td>' ;
	$msg .= $row['empaque'];
	$msg .= '</td>';
	$msg .= '<td class="jtable-command-column">';
	$msg .= '<button title="Actualizar registro" class="jtable-command-button jtable-edit-command-button"';
	$msg .= ' onclick="location.href=\'../Plantilla/vistaPrincipal.php?pg=../vistas/paginador1.php&lista=../vistas/frmActualizarractas.php?Id=';
	$msg .= $row['codigo'];
	$msg .= '&modulo=produccion';
	$msg .= '\'">';
	$msg .= ' <span>Actualizar registro</span>';
	$msg .= '</button>';
	$msg .= '</td></tr>';
	$u++;
}
$msg .= '</tbody></table></div>';

/* --------------------------------------------- */
$query_pag_num = "SELECT COUNT(*) AS count FROM gc_produccion " . $where;
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
    $msg .= "<li p='$pre' class='active'>Anterior</li>";
} else if ($previous_btn) {
    $msg .= "<li class='inactive'>Anterior</li>";
}
for ($i = $start_loop; $i <= $end_loop; $i++) {

    if ($cur_page == $i)
        $msg .= "<li p='$i' style='color:#fff;background-color:#006699;' class='active'>{$i}</li>";
    else
        $msg .= "<li p='$i' class='active'>{$i}</li>";
}

// TO ENABLE THE NEXT BUTTON
if ($next_btn && $cur_page < $no_of_paginations) {
    $nex = $cur_page + 1;
    $msg .= "<li p='$nex' class='active'>Siguiente</li>";
} else if ($next_btn) {
    $msg .= "<li class='inactive'>Siguiente</li>";
}

// TO ENABLE THE END BUTTON
if ($last_btn && $cur_page < $no_of_paginations) {
    $msg .= "<li p='$no_of_paginations' class='active'>Ultima</li>";
} else if ($last_btn) {
    $msg .= "<li p='$no_of_paginations' class='inactive'>Ultima</li>";
}
$exportar = '<button class="go_button" style="position:relative;" onclick="location.href=\'../vistas/informes_excel.php?modulo=produccion\'" title="Exportar">Exportar</button>';
$resumen = '<button class="go_button" style="position:relative;" onclick="location.href=\'../Plantilla/vistaPrincipal.php?pg=../vistas/produccion_resumen_data.php\'" title="Resumen">Resumen</button>';
$goto = "<input type='text' class='goto' size='1' style='margin-top:-1px;margin-left:60px;'/><input type='button' id='go_btn' class='go_button' value='Ir'/>";
$total_string = "<span class='total' a='$no_of_paginations'>Pagina <b>" . $cur_page . "</b> de <b>$no_of_paginations</b></span>";
$msg = $msg . "</ul>" . $exportar . $resumen . $goto . $total_string . "</div>";  // Content for pagination
echo $msg;
}

