<?php
session_start();
if (!isset($_SESSION['user'])){
	header("location:../index.php?x=2");
} else {
	$univel=$_SESSION['nivel'];
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
}
include"../clases/ConexionDatos.php";
$objConexion=Conectarse();
if(isset($_POST['filtro']))
{
     $filtro = $_POST['filtro'];
}else {
     $filtro = 0;
}
$where = "WHERE 1=1";
 if($filtro!=0) {
      $where.= " AND gc_ensaque.granel LIKE '$filtro'";
 }
$bore = "select distinct gc_ensaque.granel as granelid, gc_productos.nombre as granelnombre from gc_ensaque left join gc_productos on gc_ensaque.granel = gc_productos.productosId order by gc_productos.nombre";
$rbore = mysqli_query($objConexion,$bore) or die('Error2' . mysqli_error($objConexion));
$query_pag_data = "SELECT gc_ensaque.id as id, gc_ensaque.consecutivo as consecutivo, gc_ensaque.fecha as fecha, (select gc_productos.nombre from gc_productos where gc_productos.productosId=gc_ensaque.granel) as granel, (select gc_productos.nombre from gc_productos where gc_productos.productosId=gc_ensaque.producto) as producto, (select gc_clientes.nombre from gc_clientes where gc_productos.cliente = gc_clientes.nit) as cliente, gc_ensaque.cantidad_tm as ton, gc_ensaque.cantidad_sacos as sacos, gc_ensaque.lote as lote, gc_bodegas.nombre as bodega from gc_ensaque left join gc_productos on gc_ensaque.producto = gc_productos.productosId left join gc_bodegas on gc_ensaque.bodega = gc_bodegas.id " . $where . " order by fecha desc LIMIT $start, $per_page";
$result_pag_data = mysqli_query($objConexion,$query_pag_data) or die('MySql Error' . mysql_error());

$msg = "";
$msg .= '<script type="text/javascript">$("#filtroproducto").chosen();</script>';	 
$msg .= '<div class="jtable-title"><div class="jtable-title-text">Ensaques&nbsp;&nbsp;';
$msg .= '<select class="criterio" id="filtroproducto" style="width:220px"><option value="0">Granel</option><option value="0" style="background-color:#FFCC66">Resetear</option>';
while ($rsbore = mysqli_fetch_array($rbore)) {
	$msg .= '<option  value="' . $rsbore["granelid"] . '">' . $rsbore["granelnombre"] . '</option>';
	}
$msg .= '</select>';
$msg .= '<img style="float:right;padding:8px;cursor:copy" src="../Imagenes/add.png" ';
$msg .= ' onclick="location.href=\'../Plantilla/vistaPrincipal.php?pg=../vistas/paginador4.php&lista=../vistas/frmActualizarrensaques.php?x=1&modulo=ensaques';
$msg .= '\'">';	

$msg .= '</div></div>';
$msg .= '<table class="jtable">';
$msg .= '<thead>';
$msg .= '<tr>';
$msg .= '<th class="jtable-column-header" style="width:10%;"><div class="jtable-column-header-container"><span class="jtable-column-header-text">Fecha</span></div></th><th class="jtable-column-header" style="width:17%;"><div class="jtable-column-header-container"><span class="jtable-column-header-text">Granel</span></div></th><th class="jtable-column-header" style="width:17%;"><div class="jtable-column-header-container"><span class="jtable-column-header-text">Producto</span></div></th><th class="jtable-column-header" style="width:6%;"><div class="jtable-column-header-container"><span class="jtable-column-header-text">Kilos</span></div></th><th class="jtable-column-header" style="width:6%;"><div class="jtable-column-header-container"><span class="jtable-column-header-text">Sacos</span></div></th><th class="jtable-column-header" style="width:18%;"><div class="jtable-column-header-container"><span class="jtable-column-header-text">Cliente</span></div></th><th class="jtable-column-header" style="width:24%;"><div class="jtable-column-header-container"><span class="jtable-column-header-text">Bodega</span></div></th><th class="jtable-column-header" style="width:6%;"><div class="jtable-column-header-container"><span class="jtable-column-header-text">Lote</span></div></th>';
if ($_SESSION['nivel']==1){
	$msg .= '<th class="jtable-command-column-header" style="width:1%;"></th><th class="jtable-command-column-header" style="width:1%;"></th>';	
}

$msg .= '</tr></thead><tbody>';

$u=0;
while ($row = mysqli_fetch_array($result_pag_data)) {
		   if ($u%2==0){ 
	$msg .= '<tr class="jtable-row-even">';
		} else {
	$msg .= '<tr class="jtable-row-evenf">';
		}
	$msg .= '<td>' ;
	$msg .= $row['fecha'];
	$msg .= '</td>';
	$msg .= '<td>' ;
	$msg .= $row['granel'];
	$msg .= '</td>';	
	$msg .= '<td>' ;
	$msg .= $row['producto'];
	$msg .= '</td>';
	$msg .= '<td>' ;
	$msg .= $row['ton'];
	$msg .= '</td>';
	$msg .= '<td>' ;
	$msg .= $row['sacos'];
	$msg .= '</td>';
	$msg .= '<td>' ;
	$msg .= $row['cliente'];
	$msg .= '</td>';
	$msg .= '<td>' ;
	$msg .= $row['bodega'];
	$msg .= '</td>';

	$msg .= '<td>' ;
	$msg .= $row['lote'];
	$msg .= '</td>';
	if ($_SESSION['nivel']==1){
		$msg .= '<td class="jtable-command-column">';
		$msg .= '<button title="Actualizar registro" class="jtable-command-button jtable-edit-command-button"';
		$msg .= ' onclick="location.href=\'../Plantilla/vistaPrincipal.php?pg=../vistas/paginador4.php&lista=../vistas/frmActualizarrensaques.php?Id=';
		$msg .= $row['consecutivo'];
		$msg .= '&modulo=ensaques';
		$msg .= '\'">';
		$msg .= ' <span>Actualizar registro</span>';
		$msg .= '</button>';
		$msg .= '</td>';
		/*
		$msg .= '<td class="jtable-command-column">';
		$msg .= '<button title="Eliminar" class="jtable-command-button jtable-delete-command-button"';
		$msg .= ' onclick="location.href=\'../controlador/coensaques.php?boton=eliminar?Id=';
		$msg .= $row['id'];
		$msg .= '\'">';
		$msg .= ' <span>Eliminar registro</span>';
		$msg .= '</button></td>';		
		*/
	}

	$msg .= '</tr>';
		$u++;
}
$msg .= '</tbody></table></div>';

/* --------------------------------------------- */
$query_pag_num = "SELECT COUNT(*) AS count FROM gc_ensaque " . $where;
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
$goto = "<input type='text' class='goto' size='1' style='margin-top:-1px;margin-left:60px;'/><input type='button' id='go_btn' class='go_button' value='Ir'/>";
$total_string = "<span class='total' a='$no_of_paginations'>Pagina <b>" . $cur_page . "</b> de <b>$no_of_paginations</b></span>";
$msg = $msg . "</ul>" . $goto . $total_string . "</div>";  // Content for pagination
echo $msg;
?>

