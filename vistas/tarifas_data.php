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
     $where.= " AND proveedor LIKE '$criterio'";
	 
include"../clases/ConexionDatos.php";
$objConexion=Conectarse();

$listaproveedores = "select nit, razon_social from gc_proveedores order by razon_social";
$rs_proveedores = mysqli_query($objConexion,$listaproveedores) or die('MySql Error' . mysql_error());

$query_pag_data="SELECT `gc_tarifas`.`id` as codigo,`gc_tarifas`.`nombre` as nombre, `gc_proveedores`.`nit` as nit, `gc_proveedores`.`razon_social` as proveedor,`gc_tarifas`.`valor` as valor,`gc_tarifas`.`unidad` as unidad FROM `gc_tarifas`
LEFT JOIN `gc_proveedores` ON `gc_tarifas`.`proveedor` = `gc_proveedores`.`nit` " . $where . " order by gc_tarifas.nombre asc LIMIT $start, $per_page";

$result_pag_data = mysqli_query($objConexion,$query_pag_data) or die('MySql Error' . mysql_error());
$msg = "";
$msg .= '<div class="jtable-title"><div class="jtable-title-text">Tarifas<select class="criterio">';
	while($lista=mysqli_fetch_array($rs_proveedores)) {
$msg .= '<option  value="' . $lista["nit"] . '">' . $lista["razon_social"] . '</option>';
	}
$msg .= '</select><input type="button" id="filtrar" class="go_filtro" value="Filtrar"/><input type="button"  onclick="location.href=\'../Plantilla/vistaPrincipal.php?pg=../vistas/paginador1.php&lista=../vistas/tarifas_data.php&modulo=ordenes';
$msg .= '\'" value="Resetear"/><img style="float:right;padding:8px;cursor:copy" src="../Imagenes/add.png" ';
$msg .= ' onclick="location.href=\'../Plantilla/vistaPrincipal.php?pg=../vistas/frmActualizarrtarifas.php&x=1';
$msg .= '\'"></div></div>';
$msg .= '<table class="jtable">';
$msg .= '<thead>';
$msg .= '<tr>';
$msg .= '<th class="jtable-column-header" style="width:39%;">';
$msg .= '<div class="jtable-column-header-container">';
$msg .= '<span class="jtable-column-header-text">Nombre</span>';
$msg .= '</div></th><th class="jtable-column-header" style="width:39%;"><div class="jtable-column-header-container"><span class="jtable-column-header-text">Proveedor</span></div></th><th class="jtable-column-header" style="width:12%;"><div class="jtable-column-header-container"><span class="jtable-column-header-text">Valor</span></div></th><th class="jtable-column-header" style="width:8%;"><div class="jtable-column-header-container"><span class="jtable-column-header-text">Unidad</span></div></th><th class="jtable-command-column-header" style="width:1%;"></th><th class="jtable-command-column-header" style="width:1%;"></th></tr></thead><tbody>';
$u=0;
while ($row = mysqli_fetch_array($result_pag_data)) {
	  if ($u%2==0){ 
			 $msg .= '<tr class="jtable-row-even">';
		  }else{
			 $msg .= '<tr class="jtable-row-evenf">';
	  }
	$msg .= '<td>';
	$msg .= $row['nombre'] ;
	$msg .= '</td>';
	$msg .= '<td>';
	$msg .= $row['proveedor'] ;
	$msg .= '</td>';
	$msg .= '<td>' ;
	$msg .= $row['valor'];
	$msg .= '</td>';
	$msg .= '<td>' ;
	$msg .= $row['unidad'];
	$msg .= '</td>';
	$msg .= '<td class="jtable-command-column">';
	$msg .= '<button title="Actualizar registro" class="jtable-command-button jtable-edit-command-button"';
	$msg .= ' onclick="location.href=\'../Plantilla/vistaPrincipal.php?pg=../vistas/frmActualizarrtarifas.php&amp;Id=';
	$msg .= $row['codigo'];
	$msg .= '\'">';
	$msg .= ' <span>Actualizar registro</span>';
	$msg .= '</button>';
	$msg .= '</td>';
	$msg .= '<td class="jtable-command-column">';
	$msg .= '<button title="Eliminar" class="jtable-command-button jtable-delete-command-button"';
	$msg .= ' onclick="location.href=\'../controlador/cotarifas.php?boton=eliminar&amp;Id=';
	$msg .= $row['codigo'];
	$msg .= '\'">';
	$msg .= ' <span>Eliminar registro</span>';
	$msg .= '</button></td></tr>';
		$u++;
}
$msg .= '</tbody></table></div>';

/* --------------------------------------------- */
$query_pag_num = "SELECT COUNT(*) AS count FROM gc_tarifas " . $where;
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

