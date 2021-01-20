<?php
extract($_REQUEST);
if ($_POST[x]==9) {
	echo "<font size=3px color=red><b>El acumulado de las materias primas debe ser menor o igual a 5000 Kls, revise por favor</b></font>";
}
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

if(isset($_POST['codigo']))
{
     $criterio = $_POST['codigo'];
}else {
     $criterio = "";
}
$where = "WHERE 1=1";
 if($criterio!="")
     $where.= " AND orden LIKE '$criterio'";
	 
include"../clases/ConexionDatos.php";
$objConexion=Conectarse();
$totalbache = "select sum(cantidad) as total from gc_materia_prima_por_orden where orden=" . $criterio;
$rstotalbache = mysqli_query($objConexion,$totalbache) or die('MySql Error' . mysqli_error());
$acumulado = $rstotalbache->fetch_array();

$productos = "select productosId,codigo, nombre from gc_productos order by nombre";
$rsproductos = $objConexion->query($productos);


$query_pag_data = "SELECT gc_materia_prima_por_orden.id as id, gc_materia_prima_por_orden.orden as orden, gc_productos.nombre as producto, gc_materia_prima_por_orden.cantidad as cantidad from gc_materia_prima_por_orden left join gc_productos on gc_materia_prima_por_orden.producto = gc_productos.codigo " . $where . " LIMIT $start, $per_page";
$result_pag_data = mysqli_query($objConexion,$query_pag_data) or die('MySql Error' . mysql_error());
$msg = "";

$msg .= '<div class="jtable-title"><div class="jtable-title-text">Materia prima para la orden No. ' . $criterio;
if ($acumulado['total'] < 5000) {
	$msg .= '<img style="float:right;padding:8px;cursor:copy" src="../Imagenes/add.png" ';
	$msg .= ' onclick="location.href=\'../Plantilla/vistaPrincipal.php?pg=../vistas/paginador4.php&lista=../vistas/frmActualizarrmatpriorden.php?x=1&modulo=produccion&progid=' . $criterio . '&idprog=' . $_REQUEST['idprog'];
	$msg .= '\'">';
} else {
	$msg .= '<img style="float:right;padding:8px;cursor:copy" src="../Imagenes/baches.png" ';
	$msg .= ' onclick="location.href=\'../Plantilla/vistaPrincipal.php?pg=../vistas/paginador5.php&progid=' . $criterio;
	$msg .= '&modulo=produccion&x=1';
	$msg .= '\'">';
}
$msg .= '</div></div>';
$msg .= '<table class="jtable">';
$msg .= '<thead>';
$msg .= '<tr>';
$msg .= '<th class="jtable-column-header" style="width:88%;">';
$msg .= '<div class="jtable-column-header-container"><span class="jtable-column-header-text">';
$msg .= 'Producto';
$msg .= '</span></div></th>';
$msg .= '<th class="jtable-column-header" style="width:10%;">';
$msg .= '<div class="jtable-column-header-container"><span class="jtable-column-header-text">';
$msg .= 'Cantidad';
$msg .= '</span></div></th>';
$msg .= '<th class="jtable-command-column-header" style="width:1%;"></th><th class="jtable-command-column-header" style="width:1%;"></th></tr></thead><tbody>';

$u=0;
while ($row = mysqli_fetch_array($result_pag_data)) {
		   if ($u%2==0){ 
	$msg .= '<tr class="jtable-row-even">';
		} else {
	$msg .= '<tr class="jtable-row-evenf">';
		}
	$msg .= '<td>';
	$msg .= $row['producto'];
	$msg .= '</td>';
	$msg .= '<td align="right">' ;
	$msg .= $row['cantidad'];
	$msg .= '</td>';
	$msg .= '<td class="jtable-command-column">';
	$msg .= '<button title="Actualizar registro" class="jtable-command-button jtable-edit-command-button"';
	$msg .= ' onclick="location.href=\'../Plantilla/vistaPrincipal.php?pg=../vistas/paginador4.php&lista=../vistas/frmActualizarrmatpriorden.php?Id=';
	$msg .= $row['id'];
	$msg .= '&progid=';
	$msg .= $criterio;
	$msg .= '&modulo=produccion';
	$msg .= '&idprog=';
	$msg .= $_REQUEST['idprog'];
	$msg .= '\'">';
	$msg .= ' <span>Actualizar registro</span>';
	$msg .= '</button>';
	$msg .= '</td>';
	$msg .= '<td class="jtable-command-column">';
	$msg .= '<button title="Eliminar" class="jtable-command-button jtable-delete-command-button"';
	$msg .= ' onclick="location.href=\'../controlador/comatpriorden.php?boton=eliminar&amp;Id=';
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
//$query_pag_num = "SELECT COUNT(*) AS count FROM gc_productos " . $where;
//$result_pag_num = mysqli_query($objConexion,$query_pag_num);
//$row = mysqli_fetch_assoc($result_pag_num);
//$count = $row['count'];
$count = mysqli_num_rows($result_pag_data);
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
$goto = "<input type='text' class='goto' size='1' style='margin-top:-1px;margin-left:60px;'/><input type='button' id='go_btn' class='go_button' value='Ir'/>";
$total_string = "<span class='total' a='$no_of_paginations'>Pagina <b>" . $cur_page . "</b> de <b>$no_of_paginations</b></span>";
$msg = $msg . "</ul>" . $goto . $total_string . "</div>";  // Content for pagination
echo $msg;
}

