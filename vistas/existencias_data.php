<?php
session_start();
$nivel=$_SESSION['nivel'];

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
if ($_REQUEST['filtrofecha']!="null" and $_REQUEST['filtrofecha']!="undefined") {
	$fecha = $_REQUEST['filtrofecha'];
} else {
	$fecha = date("Y-m-d");
}
$filtro = $_REQUEST['filtro'];
$filtrob = $_REQUEST['filtrob'];
if ($nivel==3){
	$filtroc=$_SESSION['userid'];
} else {
	$filtroc = $_REQUEST['filtroc'];
}
$filtrom = $_REQUEST['filtrom'];
$where = "WHERE 1=1";
 if($filtro!=0){
     $where.= " AND gc_tmpexistencias.producto LIKE '$filtro'" ;
 }
 if ($filtro!=0 and $filtrob!=0){
 	$where.= " AND gc_tmpexistencias.producto LIKE '$filtro' AND gc_tmpexistencias.bodega LIKE '$filtrob'" ;
 }
 if ($filtro!=0 and $filtrob!=0 and $filtroc!=0){
 	$where.= " AND gc_tmpexistencias.producto LIKE '$filtro' AND gc_tmpexistencias.bodega LIKE '$filtrob' AND gc_tmpexistencias.cliente LIKE '$filtroc'" ;	
 }
 if($filtrob!=0){
     $where.= " AND gc_tmpexistencias.bodega LIKE '$filtrob'" ;
	 if ($filtrob!=0 and $filtro!=0){
	 	$where.= " AND gc_tmpexistencias.bodega LIKE '$filtrob' AND gc_tmpexistencias.producto LIKE '$filtro'" ;
	 }
 	 if ($filtrob!=0 and $filtroc!=0) {
 		$where.= " AND gc_tmpexistencias.bodega LIKE '$filtrob' AND gc_tmpexistencias.cliente LIKE '$filtroc'" ;	
 	 }	 
 }
  if($filtroc!=0){
     $where.= " AND gc_tmpexistencias.cliente LIKE '$filtroc'" ;
	 if ($filtroc!=0 and $filtrob!=0){
	 	$where.= " AND gc_tmpexistencias.cliente LIKE '$filtroc' AND gc_tmpexistencias.bodega LIKE '$filtrob'" ;
	 }
 	 if ($filtrob!=0 and $filtroc!=0){
 		$where.= " AND gc_tmpexistencias.bodega LIKE '$filtrob' AND gc_tmpexistencias.cliente LIKE '$filtroc'" ;	
 	 }
 }
  if($filtrom!=0){
     $where.= " AND gc_tmpexistencias.motonave LIKE '$filtrom'" ;
 }
include"../clases/ConexionDatos.php";
$objConexion=Conectarse();
$vactmp="truncate gc_tmpexistencias";
$rvactmp=mysqli_query($objConexion,$vactmp) or die('MySql Error1' . mysql_error());
require_once"../clases/calcular_existencias1.php";
if ($page === "t"){
	$query_pag_data = "SELECT gc_tmpexistencias.producto as productoid, gc_productos.nombre as producto, gc_productos.cliente, (select gc_clientes.nombre from gc_clientes where gc_clientes.nit = gc_productos.cliente) as cliente, gc_motonaves.nombre as motonave, gc_bodegas.nombre as bodega,  gc_tmpexistencias.lote as lote, gc_tmpexistencias.cantidad_tm as saldotm, gc_tmpexistencias.cantidad_sacos as saldosa from gc_tmpexistencias left join gc_productos on gc_tmpexistencias.producto=gc_productos.productosId left join gc_bodegas on gc_tmpexistencias.bodega=gc_bodegas.Id left join gc_motonaves on gc_tmpexistencias.motonave =gc_motonaves.motonaveId " . $where . "  order by producto";
} else {
	$query_pag_data = "SELECT gc_tmpexistencias.producto as productoid, gc_productos.nombre as producto, gc_productos.cliente, (select gc_clientes.nombre from gc_clientes where gc_clientes.nit = gc_productos.cliente) as cliente, gc_motonaves.nombre as motonave, gc_bodegas.nombre as bodega,  gc_tmpexistencias.lote as lote, gc_tmpexistencias.cantidad_tm as saldotm, gc_tmpexistencias.cantidad_sacos as saldosa from gc_tmpexistencias left join gc_productos on gc_tmpexistencias.producto=gc_productos.productosId left join gc_bodegas on gc_tmpexistencias.bodega=gc_bodegas.Id left join gc_motonaves on gc_tmpexistencias.motonave =gc_motonaves.motonaveId " . $where . "  order by producto asc LIMIT $start, $per_page";
}
$result_pag_data = mysqli_query($objConexion,$query_pag_data) or die('MySql Error7' . mysqli_error($objConexion));
$topo = "select sum(cantidad_tm) as tkilos, sum(cantidad_sacos) as tsacos from gc_tmpexistencias " . $where;
$rstopo = mysqli_query($objConexion,$topo) or die('MySql Error' . mysqli_error($objConexion));
$rtopo = mysqli_fetch_object($rstopo);
$bore = "select distinct gc_tmpexistencias.producto as productoid, gc_productos.nombre as productonombre from gc_tmpexistencias left join gc_productos on gc_tmpexistencias.producto = gc_productos.productosId order by gc_productos.nombre";
$rbore = mysqli_query($objConexion,$bore) or die('Error2' . mysqli_error($objConexion));
$exbo = "select distinct gc_tmpexistencias.bodega as bodegaid, gc_bodegas.nombre as bodeganombre from gc_tmpexistencias left join gc_bodegas on gc_tmpexistencias.bodega = gc_bodegas.Id order by gc_bodegas.nombre";
$rexbo = mysqli_query($objConexion,$exbo) or die('Error3' . mysqli_error($objConexion));
$exmo = "select distinct gc_tmpexistencias.motonave as motonaveid, gc_motonaves.nombre as motonavenombre from gc_tmpexistencias left join gc_motonaves on gc_tmpexistencias.motonave = gc_motonaves.motonaveId order by gc_motonaves.nombre";
$rexmo = mysqli_query($objConexion,$exmo) or die('Error3' . mysqli_error($objConexion));
$clie = "select * from gc_clientes";
$rclie = mysqli_query($objConexion,$clie) or die('Error4' . mysqli_error($objConexion));
$msgheader = "";
$msgheader .= '<script type="text/javascript">$("#filtroproducto").chosen();$("#filtrobodega").chosen();$("#filtrocliente").chosen();$("#filtromoto").chosen();</script>';
$msg = "";
$msgfooter = "";
$msgheader .= '<div class="jtable-title"><div class="jtable-title-text">Existencias de producto terminado a fecha <input type="date" id="fechaexistencia" value="' . $fecha . '"> <img src="../Imagenes/resetear.png" id="resetear" style="float:right;padding:8px;cursor:copy;height:24px;width:24px"></div></div>';
$msg .= '<table class="jtable">';
$msg .= '<thead>';
$msg .= '<tr>';
$msg .= '<th class="jtable-column-header" style="width:18%;">';
$msg .= '<div class="jtable-column-header-container">';
$msg .= '<span class="jtable-column-header-text">';
$msg .= '<select class="criterio" id="filtroproducto" style="width:220px"><option value="0">Producto</option><option value="0" style="background-color:#FFCC66">Resetear</option>';
while ($rsbore = mysqli_fetch_array($rbore)) {
	$msg .= '<option  value="' . $rsbore["productoid"] . '">' . $rsbore["productonombre"] . '</option>';
	}
$msg .= '</select></span></div></th>';
$msg .= '<th class="jtable-column-header" style="width:18%;"><div class="jtable-column-header-container"><span class="jtable-column-header-text">';
$msg .= '<select class="criterio" id="filtrobodega" style="width:170px"><option value="0">Bodega</option><option value="0" style="background-color:#FFCC66">Resetear</option>';
while ($rsexbo = mysqli_fetch_array($rexbo)) {
	$msg .= '<option  value="' . $rsexbo["bodegaid"] . '">' . $rsexbo["bodeganombre"] . '</option>';
	}
$msg .= '</select></span></div></th>';
if ($nivel!=3){
	$msg .= '<th class="jtable-column-header" style="width:18%;"><div class="jtable-column-header-container"><span class="jtable-column-header-text">';
	$msg .= '<select class="criterio" id="filtrocliente" style="width:170px"><option value="0">Cliente</option><option value="0" style="background-color:#FFCC66">Resetear</option>';
	while ($rsclie = mysqli_fetch_array($rclie)) {
		$msg .= '<option  value="' . $rsclie["nit"] . '">' . $rsclie["nombre"] . '</option>';
		}
	$msg .= '</select></span></div></th>';	
}
$msg .= '<th class="jtable-column-header" style="width:15%;"><div class="jtable-column-header-container"><span class="jtable-column-header-text">';
$msg .= '<select class="criterio" id="filtromoto" style="width:170px"><option value="0">Motonave</option><option value="0" style="background-color:#FFCC66">Resetear</option>';
while ($rsexmo = mysqli_fetch_array($rexmo)) {
	$msg .= '<option  value="' . $rsexmo["motonaveid"] . '">' . $rsexmo["motonavenombre"] . '</option>';
	}
$msg .= '</select></span></div></th>';

$msg .= '<th class="jtable-column-header" style="width:10%;"><div class="jtable-column-header-container"><span class="jtable-column-header-text">Lote</span></div></th><th class="jtable-column-header" style="width:8%;"><div class="jtable-column-header-container"><span class="jtable-column-header-text">Saldo TM</span></div></th><th class="jtable-column-header" style="width:9%;"><div class="jtable-column-header-container"><span class="jtable-column-header-text">Saldo Sacos</span></div></th></tr></thead><tbody>';

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
if ($nivel!=3){
	$msg .= '<td>' ;
	$msg .= $row['cliente'];
	$msg .= '</td>';		
}
	$msg .= '<td>' ;
	$msg .= $row['motonave'];
	$msg .= '</td>';
	$msg .= '<td align="right">' ;
	$msg .= $row['lote'];
	$msg .= '</td>';
	$msg .= '<td align="right">' ;
	$msg .= number_format($row['saldotm'],0,"",",");
	$msg .= '</td>';
	$msg .= '<td align="right">' ;
	$msg .= number_format($row['saldosa'],0,"",",");
	$msg .= '</td></tr>';
		$u++;
}
$msg .= '<tr class="jtable-row-total"><td colspan="5">Totales</td><td align="right">' . number_format($rtopo->tkilos,0,"",",") . '</td><td align="right">' . number_format($rtopo->tsacos,0,"",",") . '</td></tr>';
$msg .= '</tbody></table></div>';

/* --------------------------------------------- */
$query_pag_num = "SELECT COUNT(*) AS count FROM gc_tmpexistencias " . $where;
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