<?php
session_start();
if (!isset($_SESSION['user'])){
	header("location:../index.php?x=2");
} else {
	$univel=$_SESSION['nivel'];
}
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
}
$filtro = $_REQUEST['filtro'];
$filtrob = $_REQUEST['filtrob'];
$filtroc = $_REQUEST['filtroc'];
$where = "WHERE 1=1";
 if($filtro!=0){
     $where.= " AND gc_saldos_iniciales.producto LIKE '$filtro'" ;
 }
 if ($filtro!=0 and $filtrob!=0){
 	$where.= " AND gc_saldos_iniciales.producto LIKE '$filtro' AND gc_saldos_iniciales.bodega LIKE '$filtrob'" ;
 }
 if ($filtro!=0 and $filtrob!=0 and $filtroc!=0){
 	$where.= " AND gc_saldos_iniciales.producto LIKE '$filtro' AND gc_saldos_iniciales.bodega LIKE '$filtrob' AND gc_saldos_iniciales.cliente LIKE '$filtroc'" ;	
 }
 if($filtrob!=0){
     $where.= " AND gc_saldos_iniciales.bodega LIKE '$filtrob'" ;
	 if ($filtrob!=0 and $filtro!=0){
	 	$where.= " AND gc_saldos_iniciales.bodega LIKE '$filtrob' AND gc_saldos_iniciales.producto LIKE '$filtro'" ;
	 }
 	 if ($filtrob!=0 and $filtroc!=0) {
 		$where.= " AND gc_saldos_iniciales.bodega LIKE '$filtrob' AND gc_saldos_iniciales.cliente LIKE '$filtroc'" ;	
 	 }	 
 }
  if($filtroc!=0){
     $where.= " AND gc_saldos_iniciales.cliente LIKE '$filtroc'" ;
	 if ($filtroc!=0 and $filtrob!=0){
	 	$where.= " AND gc_saldos_iniciales.cliente LIKE '$filtroc' AND gc_saldos_iniciales.bodega LIKE '$filtrob'" ;
	 }
 	 if ($filtrob!=0 and $filtroc!=0){
 		$where.= " AND gc_saldos_iniciales.bodega LIKE '$filtrob' AND gc_saldos_iniciales.cliente LIKE '$filtroc'" ;	
 	 }
 }
include"../clases/ConexionDatos.php";
$objConexion=Conectarse();

$query_pag_data = "SELECT gc_saldos_iniciales.id as codigo,gc_productos.nombre as producto, gc_bodegas.nombre as bodega, gc_saldos_iniciales.saldo_inicial as saldo, gc_saldos_iniciales.saldo_inicial_sacos as sacos,gc_saldos_iniciales.fecha as fecha, gc_saldos_iniciales.lote as lote, gc_productos.cliente, (select gc_clientes.nombre from gc_clientes where gc_clientes.nit = gc_productos.cliente) as cliente from gc_saldos_iniciales left join gc_productos on gc_saldos_iniciales.producto=gc_productos.productosId left join gc_bodegas on gc_saldos_iniciales.bodega=gc_bodegas.Id " . $where . " order by gc_productos.nombre asc LIMIT $start, $per_page";
$result_pag_data = mysqli_query($objConexion,$query_pag_data) or die('MySql Error' . mysql_error($objConexion));

$bore = "select distinct gc_saldos_iniciales.producto as productoid, gc_productos.nombre as productonombre from gc_saldos_iniciales left join gc_productos on gc_saldos_iniciales.producto = gc_productos.productosId order by gc_productos.nombre";
$rbore = mysqli_query($objConexion,$bore) or die('Error2' . mysql_error());
$exbo = "select distinct gc_saldos_iniciales.bodega as bodegaid, gc_bodegas.nombre as bodeganombre from gc_saldos_iniciales left join gc_bodegas on gc_saldos_iniciales.bodega = gc_bodegas.Id order by gc_bodegas.nombre";
$rexbo = mysqli_query($objConexion,$exbo) or die('Error3' . mysql_error());
$clie = "select * from gc_clientes";
$rclie = mysqli_query($objConexion,$clie) or die('Error4' . mysql_error());

$msg = "";
$msg .= '<div class="jtable-title"><div class="jtable-title-text">Saldos iniciales de producto terminado  <img style="float:right;padding:8px;cursor:copy" src="../Imagenes/add.png" ';
$msg .= ' onclick="location.href=\'../Plantilla/vistaPrincipal.php?pg=../vistas/paginador4.php&lista=../vistas/frmActualizarrsaldosiniciales.php?x=1&modulo=inventario';
$msg .= '\'"></div></div>';
$msg .= '<table class="jtable">';
$msg .= '<thead>';
$msg .= '<tr>';
$msg .= '<th class="jtable-column-header" style="width:23%;">';
$msg .= '<div class="jtable-column-header-container">';
$msg .= '<span class="jtable-column-header-text">';
$msg .= '<select class="criterio" id="filtroproducto" style="width:170px"><option value="0">Producto</option><option value="0" style="background-color:#FFCC66">Resetear</option>';
while ($rsbore = mysqli_fetch_array($rbore)) {
	$msg .= '<option  value="' . $rsbore["productoid"] . '">' . $rsbore["productonombre"] . '</option>';
	}
$msg .= '</select></span></div></th>';
$msg .= '<th class="jtable-column-header" style="width:21%;"><div class="jtable-column-header-container"><span class="jtable-column-header-text">';
$msg .= '<select class="criterio" id="filtrobodega" style="width:170px"><option value="0">Bodega</option><option value="0" style="background-color:#FFCC66">Resetear</option>';
while ($rsexbo = mysqli_fetch_array($rexbo)) {
	$msg .= '<option  value="' . $rsexbo["bodegaid"] . '">' . $rsexbo["bodeganombre"] . '</option>';
	}
$msg .= '</select></span></div></th>';
$msg .= '<th class="jtable-column-header" style="width:22%;"><div class="jtable-column-header-container"><span class="jtable-column-header-text">';
$msg .= '<select class="criterio" id="filtrocliente" style="width:170px;"><option value="0">Cliente</option><option value="0" style="background-color:#FFCC66">Resetear</option>';
while ($rsclie = mysqli_fetch_array($rclie)) {
	$msg .= '<option  value="' . $rsclie["nit"] . '">' . $rsclie["nombre"] . '</option>';
	}
$msg .= '</select></span></div></th>';
$msg .= '<th class="jtable-column-header" style="width:9%;"><div class="jtable-column-header-container"><span class="jtable-column-header-text">Fecha</span></div></th><th class="jtable-column-header" style="width:9%;"><div class="jtable-column-header-container"><span class="jtable-column-header-text">Lote</span></div></th><th class="jtable-column-header" style="width:7%;"><div class="jtable-column-header-container"><span class="jtable-column-header-text">Kilos</span></div></th><th class="jtable-column-header" style="width:7%;"><div class="jtable-column-header-container"><span class="jtable-column-header-text">Sacos</span></div></th>';
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
	$msg .= '<td>';
	$msg .= $row['producto'] ;
	$msg .= '</td>';
	$msg .= '<td>' ;
	$msg .= $row['bodega'];
	$msg .= '</td>';
	$msg .= '<td>' ;
	$msg .= $row['cliente'];
	$msg .= '</td>';	
	$msg .= '<td align="right">' ;
	$msg .= $row['fecha'];
	$msg .= '</td>';
	$msg .= '<td align="right">' ;
	$msg .= $row['lote'];
	$msg .= '</td>';
	$msg .= '<td align="right">' ;
	$msg .= $row['saldo'];
	$msg .= '</td>';
	$msg .= '<td align="right">' ;
	$msg .= $row['sacos'];
	$msg .= '</td>';	
	if ($_SESSION['nivel']==1){
		$msg .= '<td class="jtable-command-column">';
		$msg .= '<button title="Actualizar registro" class="jtable-command-button jtable-edit-command-button"';
		$msg .= ' onclick="location.href=\'../Plantilla/vistaPrincipal.php?pg=../vistas/paginador4.php&lista=../vistas/frmActualizarrsaldosiniciales.php?Id=';
		$msg .= $row['codigo'];
		$msg .= '&modulo=inventario';
		$msg .= '\'">';
		$msg .= ' <span>Actualizar registro</span>';
		$msg .= '</button>';
		$msg .= '</td>';
		$msg .= '<td class="jtable-command-column">';
		$msg .= '<button title="Eliminar" class="jtable-command-button jtable-delete-command-button"';
		$msg .= ' onclick="location.href=\'../controlador/cosaldosiniciales.php?boton=eliminar&Id=';
		$msg .= $row['codigo'];
		$msg .= '\'">';
		$msg .= ' <span>Eliminar registro</span>';
		$msg .= '</button></td>';		
	}
	$msg .= '</tr>';
		$u++;
}
$msg .= '</tbody></table></div>';

/* --------------------------------------------- */
$query_pag_num = "SELECT COUNT(*) AS count FROM gc_saldos_iniciales " . $where;
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

