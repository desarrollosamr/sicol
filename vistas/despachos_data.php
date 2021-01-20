<?php
function calcular_tiempo_transc($hora1,$hora2){ 
    $separar[1]=explode(':',$hora1); 
    $separar[2]=explode(':',$hora2); 
if ($separar[1][0] < $separar[2][0]){
	$separar[1][0] = $separar[1][0] + 24;
}
$total_minutos_trasncurridos[1] = ($separar[1][0]*60)+$separar[1][1]; 
$total_minutos_trasncurridos[2] = ($separar[2][0]*60)+$separar[2][1]; 
$total_minutos_trasncurridos = $total_minutos_trasncurridos[1]-$total_minutos_trasncurridos[2]; 
if($total_minutos_trasncurridos<=59) return('00:'.$total_minutos_trasncurridos); 
elseif($total_minutos_trasncurridos>59){ 
$HORA_TRANSCURRIDA = floor($total_minutos_trasncurridos/60); 
if($HORA_TRANSCURRIDA<=9) $HORA_TRANSCURRIDA='0'.$HORA_TRANSCURRIDA; 
$MINUITOS_TRANSCURRIDOS = $total_minutos_trasncurridos%60; 
if($MINUITOS_TRANSCURRIDOS<=9) $MINUITOS_TRANSCURRIDOS='0'.$MINUITOS_TRANSCURRIDOS; 
return ($HORA_TRANSCURRIDA.':'.$MINUITOS_TRANSCURRIDOS); 
} }
session_start();
if (!isset($_SESSION['user'])){
	header("location:../index.php?x=2");
} else {
	$univel=$_SESSION['nivel'];
}
include"../clases/ConexionDatos.php";
extract($_REQUEST);
if(is_numeric($_REQUEST['page']))
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
$tipom=$_REQUEST['tipo'];
if(isset($_REQUEST['filtro']))
{
     $filtro = $_REQUEST['filtro'];
}else {
     $filtro = 0;
}
if($_REQUEST['filtrob'] != "null")
{
     $filtrob = $_REQUEST['filtrob'];
}
if(isset($_REQUEST['tipomov']))
{
     $tipomov = $_REQUEST['tipomov'];
}else {
     $tipomov = "1";
}
$objConexion=Conectarse();
if ($filtro=="null" or $filtro==""){
	if ($filtrob!="null" and $filtrob!=""){
		$where = "where 1=1 and tipo='" . $tipom . "' and fecha ='" . $filtrob . "'";
	} else {
		$where = "where 1=1 and tipo='" . $tipom . "'";
	}
} else {	
	$where .= "where 1=1 AND remision like '$filtro' and tipo='$tipom'";
}

if ($page === "t"){
	$query_pag_data = "SELECT gc_despachos.id as id,gc_despachos.fecha as fecha, gc_despachos.hora_inicio as inicio, gc_despachos.hora_final as final, gc_despachos.orden as orden, gc_despachos.tiquete as tiquete, gc_despachos.peso_carga as carga, gc_despachos.placas as placas, gc_despachos.observacion as observacion, gc_despachos.remision as remision, gc_conductores.nombre as conductor from gc_despachos left join gc_conductores on gc_despachos.conductor=gc_conductores.id " . $where . " order by gc_despachos.fecha desc, gc_despachos.remision desc";
}else{
	$query_pag_data = "SELECT gc_despachos.id as id,gc_despachos.fecha as fecha, gc_despachos.hora_inicio as inicio, gc_despachos.hora_final as final, gc_despachos.orden as orden, gc_despachos.tiquete as tiquete, gc_despachos.peso_carga as carga, gc_despachos.placas as placas, gc_despachos.observacion as observacion, gc_despachos.remision as remision, gc_conductores.nombre as conductor from gc_despachos left join gc_conductores on gc_despachos.conductor=gc_conductores.id " . $where . " order by gc_despachos.fecha desc, gc_despachos.remision desc LIMIT $start, $per_page";
}
$result_pag_data = mysqli_query($objConexion,$query_pag_data) or die('MySql Error' . mysqli_error($objConexion));
$msgheader = "";
$msg = "";
$msgfooter = "";
if ($tipom=="e"){
		$msgheader .= '<div class="jtable-title"><div class="jtable-title-text">Recibos de producto terminado ';
} elseif ($tipom=="s") {
		$msgheader .= '<div class="jtable-title"><div class="jtable-title-text">Despachos de producto terminado ';
}
$msgheader .= ' <input type="date" id="filtrobodega"><input type="text" id="filtroproducto" placeholder="Remision" />';
$msgheader .= '<img style="float:right;padding:8px;cursor:copy" src="../Imagenes/add.png" ';
$msgheader .= ' onclick="location.href=\'../Plantilla/vistaPrincipal.php?pg=../vistas/paginador4.php&lista=../vistas/frmActualizarrdespachos.php?x=1&progid=' . $_REQUEST[progid] . '&tipo=' . $tipom . '&modulo=despachos';
$msgheader .= '\'">';
$msgheader .= '</div></div>';

$u=0;
while ($row = mysqli_fetch_array($result_pag_data)) {
	$msg .= '<table class="jtable"><thead class="ppal"><tr>';
	$msg .= '<th class="jtable-column-header" style="width:8%;">';
	$msg .= '<div class="jtable-column-header-container">';
	$msg .= '<span class="jtable-column-header-text">Fecha</span>';
	$msg .= '</div></th><th class="jtable-column-header" style="width:6%;"><div class="jtable-column-header-container"><span class="jtable-column-header-text">Inicio</span></div></th><th class="jtable-column-header" style="width:5%;"><div class="jtable-column-header-container"><span class="jtable-column-header-text">Final</span></div></th><th class="jtable-column-header" style="width:5%;"><div class="jtable-column-header-container"><span class="jtable-column-header-text">Tiempo</span></div></th><th class="jtable-column-header" style="width:6%;"><div class="jtable-column-header-container"><span class="jtable-column-header-text">Remision</span></div></th><th class="jtable-column-header" style="width:6%;"><div class="jtable-column-header-container"><span class="jtable-column-header-text">Orden</span></div></th><th class="jtable-column-header" style="width:6%;"><div class="jtable-column-header-container"><span class="jtable-column-header-text">Tiquete</span></div></th><th class="jtable-column-header" style="width:6%;"><div class="jtable-column-header-container"><span class="jtable-column-header-text">Peso</span></div></th><th class="jtable-column-header" style="width:6%;"><div class="jtable-column-header-container"><span class="jtable-column-header-text">Placas</span></div></th><th class="jtable-column-header" style="width:14%;"><div class="jtable-column-header-container"><span class="jtable-column-header-text">Conductor</span></div></th><th class="jtable-column-header" style="width:30%;"><div class="jtable-column-header-container"><span class="jtable-column-header-text">Observacion</span></div></th><th class="jtable-command-column-header" style="width:8%;">Productos</th><th class="jtable-command-column-header" style="width:1%;"></th>';
	if ($_SESSION['nivel']==1){
		$msg .= '<th class="jtable-command-column-header" style="width:1%;"></th><th class="jtable-command-column-header" style="width:1%;"></th>';		
	}
	$msg .= '</tr></thead><tbody>';	
	$spxd="SELECT gc_despachos_producto.id as id, gc_productos.nombre as producto, gc_productos.cliente, (select gc_clientes.nombre from gc_clientes where gc_clientes.nit = gc_productos.cliente) as cliente, gc_despachos_producto.cantidad_tm as ton, gc_despachos_producto.cantidad_sacos as sacos,  gc_despachos_producto.lote as lote, gc_bodegas.nombre as bodega from gc_despachos_producto left join gc_productos on gc_despachos_producto.producto = gc_productos.productosId left join gc_bodegas on gc_despachos_producto.bodega = gc_bodegas.Id where gc_despachos_producto.remisionid=" . $row['remision'] . " and tipo='" . $tipom . "'";
	$rspxd=mysqli_query($objConexion,$spxd) or die('MySqldp Error' . mysqli_error($objConexion));
	$fspxd=mysqli_num_rows($rspxd);
	$tides=calcular_tiempo_transc($row['final'],$row['inicio']);
		   if ($u%2==0){ 
	$msg .= '<tr class="jtable-row-even">';
		} else {
	$msg .= '<tr class="jtable-row-evenf">';
		}
	$msg .= '<td>';
	$msg .= $row['fecha'] ;
	$msg .= '</td>';
	$msg .= '<td>';
	$msg .= strftime("%H:%M", strtotime($row['inicio'])) ;
	$msg .= '</td>';
	$msg .= '<td>';
	$msg .= strftime("%H:%M", strtotime($row['final'])) ;
	$msg .= '</td>';
	$msg .= '<td>';
	$msg .= $tides ;
	$msg .= '</td>';

	$msg .= '<td>' ;
	$msg .= $row['remision'];
	$msg .= '</td>';
	$msg .= '<td>' ;
	$msg .= $row['orden'];
	$msg .= '</td>';
	$msg .= '<td>' ;
	$msg .= $row['tiquete'];
	$msg .= '</td>';
	$msg .= '<td>' ;
	$msg .= $row['carga'];
	$msg .= '</td>';
	$msg .= '<td>' ;
	$msg .= $row['placas'];
	$msg .= '</td>';
	$msg .= '<td>' ;
	$msg .= $row['conductor'];
	$msg .= '</td>';
	$msg .= '<td>' ;
	$msg .= $row['observacion'];
	$msg .= '</td>';
	$msg .= '<td class="jtable-command-column">' ;
	$msg .= '<button title="Productos" class="jtable-command-button jtable-product-command-button"';
	$variables = 'Id=' . $row['id'] . '&progid=' . $row['remision'] . '&modulo=despachos' . '&tipo=' . $tipom . '&fecha=' . $row['fecha'];
	$msg .= ' onclick="location.href=\'../Plantilla/vistaPrincipal.php?pg=../vistas/paginador4.php&lista=../vistas/despachos_productos_data.php&' . $variables;
	$msg .= '\'">';
	$msg .= ' <span>Agregar productos</span>';
	$msg .= '</button>';
	$msg .= '</td>';
	if ($fspxd > 0){
		$msg .= '<td class="jtable-command-column">' ;
		$msg .= '<button title="Productos" class="jtable-command-button jtable-more-command-button"';
		$msg .= '</td>';
	}
	if ($_SESSION['nivel']==1){
		$msg .= '<td class="jtable-command-column">';
		$msg .= '<button title="Actualizar registro" class="jtable-command-button jtable-edit-command-button"';
		$msg .= ' onclick="location.href=\'../Plantilla/vistaPrincipal.php?pg=../vistas/paginador4.php&lista=../vistas/frmActualizarrdespachos.php?Id=';
		$msg .= $row['id'];
		$msg .= '&tipo=';
		$msg .= $_REQUEST['tipo'];
		$msg .= '&modulo=despachos';
		$msg .= '\'">';
		$msg .= ' <span>Actualizar registro</span>';
		$msg .= '</button>';
		$msg .= '</td>';		
	}
	$msg .= '<td class="jtable-command-column">';
	$msg .= '<button title="Imprimir" class="jtable-command-button jtable-print-command-button"';
	$msg .= ' onclick="window.open(\'../vistas/formato_remision.php?Id=';
	$msg .= $row['remision'] . '&tipo=' . $tipom;
	$msg .= '\',\'_blank\')">';
	$msg .= ' <span>Imprimir registro</span>';
	$msg .= '</button></td></tr>';
	if ($fspxd > 0){
		$msg .= '<table class="jtable">';
		$msg .= '<thead>';
		$msg .= '<tr>';
		$msg .= '<th class="jtable-column-header" style="width:28%;">';
		$msg .= '<div class="jtable-column-header-container">';
		$msg .= '<span class="jtable-column-header-text" style="float:right">Producto</span></div></th><th class="jtable-column-header" style="width:6%;"><div class="jtable-column-header-container"><span class="jtable-column-header-text">Kilos</span></div></th><th class="jtable-column-header" style="width:6%;"><div class="jtable-column-header-container"><span class="jtable-column-header-text">Sacos</span></div></th><th class="jtable-column-header" style="width:8%;"><div class="jtable-column-header-container"><span class="jtable-column-header-text">Bodega</span></div></th><th class="jtable-column-header" style="width:15%;"><div class="jtable-column-header-container"><span class="jtable-column-header-text">Cliente</span></div></th><th class="jtable-column-header" style="width:29%;"><div class="jtable-column-header-container"><span class="jtable-column-header-text">Observacion</span></div></th><th class="jtable-column-header" style="width:6%;"><div class="jtable-column-header-container"><span class="jtable-column-header-text">Lote</span></div></th></tr></thead><tbody>';
		$g=0;
		while ($rowp = mysqli_fetch_array($rspxd)){
		   	if ($g%2==0){ 
				$msg .= '<tr class="jtable-rowp-even">';
			} else {
				$msg .= '<tr class="jtable-rowp-evenf">';
			}
			$msg .= '<td align="right">' ;
			$msg .= $rowp['producto'];
			$msg .= '</td>';
			$msg .= '<td>' ;
			$msg .= $rowp['ton'];
			$msg .= '</td>';
			$msg .= '<td>' ;
			$msg .= $rowp['sacos'];
			$msg .= '</td>';
			$msg .= '<td>' ;
			$msg .= $rowp['bodega'];
			$msg .= '</td>';
			$msg .= '<td>' ;
			$msg .= $rowp['cliente'];
			$msg .= '</td>';
			$msg .= '<td>' ;
			$msg .= $rowp['observacion'];
			$msg .= '</td>';
			$msg .= '<td>' ;
			$msg .= $rowp['lote'];
			$msg .= '</td></tr>';			
			$g++;
		}
		//$msg .= '</tbody></table></div>';
		$msg .= '</tbody></table>';
	}
	$u++;
	$msg .= '</tbody></table></div>';
}
/* --------------------------------------------- */
$query_pag_num = "SELECT COUNT(*) AS count FROM gc_despachos " . $where;
$result_pag_num = mysqli_query($objConexion,$query_pag_num);
$row = mysqli_fetch_assoc($result_pag_num);
$count = $row['count'];
//$count = mysqli_num_rows($result_pag_data);
if ($per_page){
	$no_of_paginations = ceil($count / $per_page);
}

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
$msgfooter .= "<div class='pagination' id='divpagination'><ul>";

// FOR ENABLING THE FIRST BUTTON
if ($first_btn && $cur_page > 1) {
    $msgfooter .= "<li p='1' class='active'>Primera</li>";
} else if ($first_btn) {
    $msgfooter .= "<li p='1' class='inactive'>Primera</li>";
}

// FOR ENABLING THE PREVIOUS BUTTON
if ($previous_btn && $cur_page > 1) {
    $pre = $cur_page - 1;
    $msgfooter .= "<li p='$pre' class='active'>Anterior</li>";
} else if ($previous_btn) {
    $msgfooter .= "<li class='inactive'>Anterior</li>";
}
for ($i = $start_loop; $i <= $end_loop; $i++) {

    if ($cur_page == $i)
        $msgfooter .= "<li p='$i' style='color:#fff;background-color:#006699;' class='active'>{$i}</li>";
    else
        $msgfooter .= "<li p='$i' class='active'>{$i}</li>";
}

// TO ENABLE THE NEXT BUTTON
if ($next_btn && $cur_page < $no_of_paginations) {
    $nex = $cur_page + 1;
    $msgfooter .= "<li p='$nex' class='active'>Siguiente</li>";
} else if ($next_btn) {
    $msgfooter .= "<li class='inactive'>Siguiente</li>";
}

// TO ENABLE THE END BUTTON
if ($last_btn && $cur_page < $no_of_paginations) {
    $msgfooter .= "<li p='$no_of_paginations' class='active'>Ultima</li>";
} else if ($last_btn) {
    $msgfooter .= "<li p='$no_of_paginations' class='inactive'>Ultima</li>";
}
if ($per_page){
	$msgfooter .= "<li p='t' class='active'>Todos</li>";
	$goto = "<input type='text' class='goto' size='1' style='margin-top:-1px;margin-left:60px;'/><input type='button' id='go_btn' class='go_button' value='Ir'/>";
	$total_string = "<span class='total' a='$no_of_paginations'>Pagina <b>" . $cur_page . "</b> de <b>$no_of_paginations</b></span>";
	$msgfooter = $msgfooter . "</ul>" . $goto . $total_string . "</div>";  // Content for pagination
}
echo $msgheader . $msg . $msgfooter;


