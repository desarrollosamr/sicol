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
 if($criterio!="")
     $where.= " AND nombre LIKE '$criterio%'";
	 
include"../clases/ConexionDatos.php";
$objConexion=Conectarse();

$query_pag_data = "SELECT * from gc_bodegas " . $where . " order by nombre LIMIT $start, $per_page";
$result_pag_data = mysqli_query($objConexion,$query_pag_data) or die('MySql Error' . mysql_error());
$msg = "";
$msg .= '<div class="jtable-title"><div class="jtable-title-text">Bodegas<input type="text" class="criterio" /><input type="button" id="filtrar" class="go_filtro" value="Filtrar"/><img style="float:right;padding:8px;cursor:copy" src="../Imagenes/add.png" ';
$msg .= ' onclick="location.href=\'../Plantilla/vistaPrincipal.php?pg=../vistas/paginador1.php&lista=../vistas/frmActualizarrbodegas.php?x=1&modulo=recibo';
$msg .= '\'"></div></div>';
$msg .= '<table class="jtable" id="inedit">';
$msg .= '<thead>';
$msg .= '<tr>';
$msg .= '<th class="jtable-column-header" style="width:5%;">';
$msg .= '<div class="jtable-column-header-container">';
$msg .= '<span class="jtable-column-header-text">Id</span>';
$msg .= '</div>';
$msg .= '<th class="jtable-column-header" style="width:93%;">';
$msg .= '<div class="jtable-column-header-container">';
$msg .= '<span class="jtable-column-header-text">Nombre</span>';
$msg .= '</div></th><th class="jtable-command-column-header" style="width:1%;"></th><th class="tabledit-toolbar-column" style="width:1%;"></th></tr></thead><tbody>';

$u=0;
while ($row = mysqli_fetch_array($result_pag_data)) {
		   if ($u%2==0){ 
	$msg .= '<tr class="jtable-row-even" id="' . $row['Id'] . '">';
		} else {
	$msg .= '<tr class="jtable-row-evenf" id="' . $row['Id'] . '">';
		}
	$msg .= '<td>' ;
	$msg .= '<span class="tabledit-span tabledit-identifier">' . $row['Id'] . '</span>';
	$msg .= '<input class="tabledit-input tabledit-identifier" type="hidden" name="id" value="' . $row['Id'] . '" disabled="disabled">';
	$msg .= '</td>';
	$msg .= '<td class="tabledit-view-mode">' ;
	$msg .= '<span class="tabledit-span">' . $row['nombre'] . '</span>';
	$msg .= '<input class="tabledit-input form-control input-sm" type="text" name="nombre" value="' . $row['nombre'] . '" style="display: none;" disabled="disabled">';
	$msg .= '</td>';
	$msg .= '<td style="white-space: nowrap; width: 1%;">';
	$msg .= '<div class="tabledit-toolbar btn-toolbar" style="text-align: left;">
<div class="btn-group btn-group-sm" style="float: none;"><button type="button" class="tabledit-edit-button btn btn-sm btn-default" style="float: none;">
  <span class="glyphicon glyphicon-pencil"></span>
 </button>
 <button type="button" class="tabledit-delete-button btn btn-sm btn-default" style="float: none;">
  <span class="glyphicon glyphicon-trash"></span>
 </button></div>
 <button type="button" class="tabledit-save-button btn btn-sm btn-success" style="display: none; float: none;">Save</button>
<button type="button" class="tabledit-confirm-button btn btn-sm btn-danger" style="display: none; float: none;">Confirm</button>
<button type="button" class="tabledit-restore-button btn btn-sm btn-warning" style="display: none; float: none;">Restore</button></div>';
	$msg .= '</td></tr>';
		$u++;
}
$msg .= '</tbody></table></div>';
$msg .= '<script>
    $(document).ready(function() {
        // Example #3
        $("#inedit").Tabledit({
            url: "../vistas/example.php",
            editButton: false,
            deleteButton: false,
            hideIdentifier: true,
            columns: {
                identifier: [0, "id"],
                editable: [[1, "nombre"]]
            }
        });
    });</script>';
/* --------------------------------------------- */
$query_pag_num = "SELECT COUNT(*) AS count FROM gc_bodegas " . $where;
$result_pag_num = mysqli_query($objConexion,$query_pag_num);
$row = mysqli_fetch_assoc($result_pag_num);
$count = $row['count'];
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

