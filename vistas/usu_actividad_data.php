<?php
session_start();
if (!isset($_SESSION['user'])){
	header("location:../index.php?x=2");
} else {
	$univel=$_SESSION['nivel'];
}
extract($_REQUEST);
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
$filtro = $_REQUEST['filtro'];

$where = "WHERE 1=1";
 if($filtro!=0 and $filtro!="null"){
     $where.= " AND gc_usuario_actividad.usuario LIKE '$filtro'";
 }
include"../clases/ConexionDatos.php";
$objConexion=Conectarse();

$query_pag_data = "SELECT gc_usuario_actividad.id as id, gc_usuarios.usuLogin as usuario, gc_usuario_actividad.accion as accion, gc_usuario_actividad.contenido as acciones from gc_usuario_actividad left join gc_usuarios on gc_usuario_actividad.usuario=gc_usuarios.usuid " . $where . "  order by usuario LIMIT $start, $per_page ";
$result_pag_data = mysqli_query($objConexion,$query_pag_data) or die('MySql Error show' . mysql_error());
$bore = "select distinct gc_usuario_actividad.usuario as usuarioid, gc_usuarios.usuLogin as usuario from gc_usuario_actividad left join gc_usuarios on gc_usuario_actividad.usuario = gc_usuarios.usuid order by usuario";
$rbore = mysqli_query($objConexion,$bore) or die('Error2' . mysqli_error($objConexion));
$msg = "";
$msg .= '<script type="text/javascript">$("#filtroproducto").chosen();</script>';
$msg .= '<div class="jtable-title"><div class="jtable-title-text">Bitácora por usuario <select class="criterio" id="filtroproducto" style="width:220px"><option value="0">Usuario</option><option value="0" style="background-color:#FFCC66">Resetear</option>';
while ($rsbore = mysqli_fetch_array($rbore)) {
	$msg .= '<option  value="' . $rsbore["usuarioid"] . '">' . $rsbore["usuario"] . '</option>';
}
$msg .= '</select></div></div>';
$msg .= '<table class="jtable">';
$msg .= '<thead>';
$msg .= '<tr>';
$msg .= '<th class="jtable-column-header" style="width:20%;">';
$msg .= '<div class="jtable-column-header-container">';
$msg .= '<span class="jtable-column-header-text">Usuario</span></div></th><th class="jtable-column-header" style="width:12%;"><div class="jtable-column-header-container"><span class="jtable-column-header-text">Acción</span></div></th><th class="jtable-column-header" style="width:68%;"><div class="jtable-column-header-container"><span class="jtable-column-header-text">Contenido</span></div></th>';
/*if ($_SESSION['nivel']==1){
	$msg .= '<th class="jtable-command-column-header" style="width:1%;"></th><th class="jtable-command-column-header" style="width:1%;"></th>';	
}*/
$msg .= '</tr></thead><tbody>';

$u=0;
while ($row = mysqli_fetch_array($result_pag_data)) {
		   if ($u%2==0){ 
	$msg .= '<tr class="jtable-row-even">';
		} else {
	$msg .= '<tr class="jtable-row-evenf">';
		}
	$msg .= '<td>' ;
	$msg .= $row['usuario'];
	$msg .= '</td>';
	$msg .= '<td>' ;
	$msg .= $row['accion'];
	$msg .= '</td>';
	$msg .= '<td width="68%"><div style="width:680px;"><p style="width:670px;word-wrap:break-word;">' ;
	$msg .= $row['acciones'];
	$msg .= '</p></div></td>';
	/*
	if ($_SESSION['nivel']==1){
		$msg .= '<td class="jtable-command-column">';
		$msg .= '<button title="Actualizar registro" class="jtable-command-button jtable-edit-command-button"';
		$msg .= ' onclick="location.href=\'../Plantilla/vistaPrincipal.php?pg=../vistas/paginador4.php&lista=../vistas/frmActualizarrajustes.php?Id=';
		$msg .= $row['id'];
		$msg .= '&x=0&tipo=';
		$msg .= $_POST[tipo];
		$msg .= '\'">';
		$msg .= ' <span>Actualizar registro</span>';
		$msg .= '</button>';
		$msg .= '</td>';
		$msg .= '<td class="jtable-command-column">';
		$msg .= '<button title="Eliminar" class="jtable-command-button jtable-delete-command-button"';
		$msg .= ' onclick="location.href=\'../controlador/coajustes.php?boton=eliminar&Id=';
		$msg .= $row['id'];
		$msg .= '\'">';
		$msg .= ' <span>Eliminar registro</span>';
		$msg .= '</button></td>';		
	}
	*/
	$msg .= '</tr>';
		$u++;
}
$msg .= '</tbody></table></div>';

/* --------------------------------------------- */
$query_pag_num = "SELECT COUNT(*) AS count FROM gc_usuario_actividad " . $where ;
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
$exportar = '<button class="go_button" style="position:relative;" onclick="location.href=\'../vistas/informes_excel.php?criterio=' . $criterio . '&modulo=despachos\'" title="Exportar">Exportar</button>';
$goto = "<input type='text' class='goto' size='1' style='margin-top:-1px;margin-left:60px;'/><input type='button' id='go_btn' class='go_button' value='Ir'/>";
$total_string = "<span class='total' a='$no_of_paginations'>Pagina <b>" . $cur_page . "</b> de <b>$no_of_paginations</b></span>";
$msg = $msg . "</ul>" . $exportar . $goto . $total_string . "</div>";  // Content for pagination
echo $msg;
}