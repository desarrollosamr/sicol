<?php
session_start();
if (!isset($_SESSION['user'])){
	header("location:../guest.php?x=2");
} else {
	$nivel=$_SESSION['nivel'];
}
//$nivel=$_SESSION['nivel'];
include"../clases/ConexionDatos.php";
$objConexion=Conectarse();
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
date_default_timezone_set('America/Bogota');
$total = mysqli_num_rows(mysqli_query($objConexion,"SELECT id FROM gc_turnos WHERE despachado=0"));
if ($total>0){
	$buid="select max(id) as ultimo from gc_turnos where despachado=0 ";
	$rbuid=mysqli_query($objConexion,$buid) or die('MySql Error' . mysqli_error($objConexion));
	$rsbuid=mysqli_fetch_object($rbuid);

	$buf="select fecha from gc_turnos where id=" . $rsbuid->ultimo;
	$rbuf=mysqli_query($objConexion,$buf) or die('MySql Error' . mysqli_error($objConexion));
	$rsbuf=mysqli_fetch_object($rbuf);
	if ($rsbuf->fecha!=date("Y-m-d") and $nivel!=4){
		echo '<script language="javascript">alert("Hay turnos vigentes de fecha anterior.\n Por favor actualice la fecha de esos turnos antes de comenzar");</script>'; 
	}		
}

$where = "WHERE 1=1";
/*
if ($_REQUEST['filtrofecha']!="null" and $_REQUEST['filtrofecha']!="undefined") {
	$fecha = $_REQUEST['filtrofecha'];
} else {
	$fecha = date("Y-m-d");
}
where .= " AND gc_turnos.fecha LIKE '$fecha'";
*/
if ($_REQUEST['filtrofecha']!="null" and $_REQUEST['filtrofecha']!="undefined") {
	$fecha = $_REQUEST['filtrofecha'];
	$where .= " AND gc_turnos.fecha LIKE '$fecha'";
} else {
	$fecha = date("Y-m-d");
}
if ($_REQUEST['filtro']!="0" AND $_REQUEST['filtro']!="NULL"){
		$filtro = $_REQUEST['filtro'];
} else	{
	$filtro="";	
}
$filtrob = $_REQUEST['filtrob'];

if ($nivel==3){
	$filtroc=$_SESSION['userid'];
} else {
	$filtroc = $_REQUEST['filtroc'];
}
$filtrom = $_REQUEST['filtrom'];
 if($filtro!="" and $filtro!="null"){
     $where.= " AND gc_turnos.clase_movimiento LIKE '$filtro'" ;
 }
 if ($filtro!="" and $filtro!="null" and $filtrob!=0){
 	$where.= " AND gc_turnos.clase_movimiento LIKE '$filtro' AND gc_turnos.bodega LIKE '$filtrob'" ;
 }
 
 if ($filtro!=0 and $filtrob!=0 and $filtroc!=0){
 	$where.= " AND gc_turnos.clase_movimiento LIKE '$filtro' AND gc_turnos.bodega LIKE '$filtrob' AND gc_turnos.cliente LIKE '$filtroc'" ;
 }
 if($filtrob!=0){
     $where.= " AND gc_turnos.bodega LIKE '$filtrob'" ;
	 if ($filtrob!=0 and $filtro!="" and $filtro!="null"){
	 	$where.= " AND gc_turnos.bodega LIKE '$filtrob' AND gc_turnos.clase_movimiento LIKE '$filtro'" ;
	 }
 	 if ($filtrob!=0 and $filtroc!=0) {
 		$where.= " AND gc_turnos.bodega LIKE '$filtrob' AND gc_turnos.cliente LIKE '$filtroc'" ;	
 	 }	 
 }
  if($filtroc!=0){
     $where.= " AND gc_turnos.cliente LIKE '$filtroc'" ;
	 if ($filtroc!=0 and $filtrob!=0){
	 	$where.= " AND gc_turnos.cliente LIKE '$filtroc' AND gc_turnos.bodega LIKE '$filtrob'" ;
	 }
 	 if ($filtroc!=0 and $filtro!="" and $filtro!="null"){
 		$where.= " AND gc_turnos.bodega LIKE '$filtrob' AND gc_turnos.clase_movimiento LIKE '$filtro'" ;	
 	 }
 }
$where .= " and despachado=0";

if ($page === "t"){
	$query_pag_data = "SELECT gc_turnos.Id as id, gc_turnos.fecha as fecha, gc_turnos.placas as placas, gc_turnos.clase_movimiento as tipo, gc_turnos.orden as orden, gc_turnos.hora_registro as registro, gc_turnos.hora_atencion as atencion, gc_turnos.conductor as conductorid, (select gc_conductores.nombre from gc_conductores where gc_conductores.id = gc_turnos.conductor) as conductor, (select gc_conductores.celular from gc_conductores where gc_conductores.id = gc_turnos.conductor) as celular, gc_turnos.productos as productos, gc_turnos.peso_carga as pesocarga, gc_turnos.cliente as cliente,(select gc_clientes.nombre from gc_clientes where gc_clientes.nit = gc_turnos.cliente) as cliente, gc_turnos.turno as turno, gc_bodegas.nombre as bodega  from gc_turnos   left join gc_bodegas on gc_turnos.bodega=gc_bodegas.Id " . $where . " order by gc_turnos.turno";
} else {
	$query_pag_data = "SELECT gc_turnos.Id as id, gc_turnos.fecha as fecha, gc_turnos.placas as placas, gc_turnos.clase_movimiento as tipo, gc_turnos.orden as orden, gc_turnos.hora_registro as registro, gc_turnos.hora_atencion as atencion, gc_turnos.conductor as conductor, (select gc_conductores.nombre from gc_conductores where gc_conductores.id = gc_turnos.conductor) as conductor, (select gc_conductores.celular from gc_conductores where gc_conductores.id = gc_turnos.conductor) as celular, gc_turnos.productos as productos, gc_turnos.peso_carga as pesocarga, gc_turnos.cliente as cliente,(select gc_clientes.nombre from gc_clientes where gc_clientes.nit = gc_turnos.cliente) as cliente, gc_turnos.turno as turno, gc_bodegas.nombre as bodega from gc_turnos  left join gc_bodegas on gc_turnos.bodega=gc_bodegas.Id  " . $where . " order by gc_turnos.turno LIMIT $start, $per_page";
}
$result_pag_data = mysqli_query($objConexion,$query_pag_data) or die('MySql Error' . mysqli_error($objConexion));
$topo = "select sum(peso_carga) as tkilos from gc_turnos " . $where;
$rstopo = mysqli_query($objConexion,$topo) or die('MySql Error' . mysqli_error($objConexion));
$rtopo = mysqli_fetch_object($rstopo);
$bore = "select distinct gc_turnos.fecha as fechaid from gc_turnos";
$rbore = mysqli_query($objConexion,$bore) or die('Error2' . mysqli_error($objConexion));
$exmo = "select distinct gc_turnos.cliente as clienteid, gc_clientes.nombre as clientenombre from gc_turnos left join gc_clientes on gc_turnos.cliente = gc_clientes.nit order by gc_clientes.nombre";
$rexmo = mysqli_query($objConexion,$exmo) or die('Error3' . mysqli_error($objConexion));
$clie = "select * from gc_clientes";
$rclie = mysqli_query($objConexion,$clie) or die('Error4' . mysqli_error($objConexion));
$exbo = "select distinct gc_turnos.bodega as bodegaid, gc_bodegas.nombre as bodeganombre from gc_turnos left join gc_bodegas on gc_turnos.bodega = gc_bodegas.Id where gc_turnos.fecha='" . $fecha . "' order by gc_bodegas.nombre";
$rexbo = mysqli_query($objConexion,$exbo) or die('Error3' . mysqli_error($objConexion));
$msgheader = "";
$msgheader .= '<script type="text/javascript">$("#filtroproducto").chosen();$("#filtrofecha").chosen();$("#filtrocliente").chosen();$("#filtrobodega").chosen();</script>';
$msg = "";
$msgfooter = "";
$msgheader .= '<div class="jtable-title"><div class="jtable-title-text">Relación de turnos en fecha ';
if ($nivel==4) {
	$msgheader .= $fecha;
} else {
	$msgheader .= '<input type="date" id="fechaexistencia" value="' . $fecha . '"> <img src="../Imagenes/resetear.png" id="resetear" style="float:right;padding:8px;cursor:copy;height:24px;width:24px">';	
	$msgheader .= '<img style="float:right;padding:8px;cursor:copy" src="../Imagenes/add.png" ';
	$msgheader .= ' onclick="location.href=\'../Plantilla/vistaPrincipal.php?pg=../vistas/paginador4.php&lista=../vistas/frmActualizarrturnos.php?x=1&modulo=turnos&fecha=' . date("Y-m-d");
	$msgheader .= '\'">';	
}
$msgheader .= '</div></div>';

$msg .= '<table class="jtable">';
$msg .= '<thead>';
$msg .= '<tr>';
$msg .= '<th class="jtable-column-header" style="width:6%;">';
$msg .= '<div class="jtable-column-header-container">';
$msg .= '<span class="jtable-column-header-text">Fecha</span></div></th>';
$msg .= '<th class="jtable-column-header" style="width:4%;">';
$msg .= '<div class="jtable-column-header-container">';
$msg .= '<span class="jtable-column-header-text">Turno</span></div></th>';
$msg .= '<th class="jtable-column-header" style="width:12%;"><div class="jtable-column-header-container"><span class="jtable-column-header-text">';
$msg .= '<select class="criterio" id="filtroproducto" style="width:110px"><option value="0">Tipo</option>';
$msg .= '<option  value="cs">Cargue ensacado</option>';
$msg .= '<option  value="cg">Cargue granel</option>';
$msg .= '<option  value="ds">Descargue ensacado</option>';
$msg .= '<option  value="dg">Descargue granel</option>';
$msg .= '<option  value="cb">Cargue de big bags</option>';
$msg .= '<option  value="db">Descargue de big bags</option>';

$msg .= '</select></span></div></th>';
$msg .= '<th class="jtable-column-header" style="width:6%;">';
$msg .= '<div class="jtable-column-header-container">';
$msg .= '<span class="jtable-column-header-text">Placas</span></div></th>';
$msg .= '<th class="jtable-column-header" style="width:12%;">';
$msg .= '<div class="jtable-column-header-container">';
$msg .= '<span class="jtable-column-header-text">Conductor</span></div></th>';
$msg .= '<th class="jtable-column-header" style="width:15%;">';
$msg .= '<div class="jtable-column-header-container">';
$msg .= '<select class="criterio" id="filtrocliente" style="width:140px"><option value="0">Cliente</option>';
while ($rsclie = mysqli_fetch_array($rclie)) {
	$msg .= '<option  value="' . $rsclie["nit"] . '">' . $rsclie["nombre"] . '</option>';
	}
$msg .= '</select></span></div></th>';	
$msg .= '<th class="jtable-column-header" style="width:14%;">';
$msg .= '<div class="jtable-column-header-container">';
$msg .= '<select class="criterio" id="filtrobodega" style="width:110px"><option value="0">Bodega</option>';
while ($rsexbo = mysqli_fetch_array($rexbo)) {
	$msg .= '<option  value="' . $rsexbo["bodegaid"] . '">' . $rsexbo["bodeganombre"] . '</option>';
	}
$msg .= '</select></span></div></th>';	
$msg .= '<th class="jtable-column-header" style="width:12%;">';
$msg .= '<div class="jtable-column-header-container">';
$msg .= '<span class="jtable-column-header-text">Productos</span></div></th>';
$msg .= '<th class="jtable-column-header" style="width:6%"';
$msg .= '<span class="jtable-column-header-text">Kilos</span></div></th>';
$msg .= '<th class="jtable-column-header" style="width:8%;">';
$msg .= '<div class="jtable-column-header-container">';
$msg .= '<span class="jtable-column-header-text">Enturnado</span></div></th>';
$msg .= '<th class="jtable-column-header" style="width:8%;">';
$msg .= '<div class="jtable-column-header-container">';
$msg .= '<span class="jtable-column-header-text">Atendido</span></div></th>';
if ($_SESSION['nivel']==1){
	$msg .= '<th class="jtable-command-column-header" style="width:1%;"></th><th class="jtable-command-column-header" style="width:1%;"></th><th class="jtable-command-column-header" style="width:1%;"></th>';	
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
	$msg .= $row['fecha'] ;
	$msg .= '</td>';
	$msg .= '<td>';
	$msg .= $row['turno'] ;
	$msg .= '</td>';
	$msg .= '<td>' ;
	switch ($row['tipo']){
		case "cs":
			$msg .= 'Cargue sacos';
			$tipom = "s";
			break;
		case "cg":
			$msg .= 'Cargue granel';
			$tipom = "s";
			break;		
		case "ds":
			$msg .= 'Descargue sacos';
			$tipom = "e";
			break;		
		case "dg":
			$msg .= 'Descargue granel';
			$tipom = "e";
			break;	
		case "cb":
			$msg .= 'Cargue de big bags';
			$tipom = "s";
			break;			
		case "db":
			$msg .= 'Descargue de big bags';
			$tipom = "e";
			break;		}
	$msg .= '</td>';
	$msg .= '<td>' ;
	$msg .= $row['placas'];
	$msg .= '</td>';		
	$msg .= '<td>' ;
	$msg .= $row['conductor'];
	$msg .= '</td>';
if ($nivel!=3){
	$msg .= '<td align="right">' ;
	$msg .= $row['cliente'];
	$msg .= '</td>';
}
	$msg .= '<td align="right">' ;
	$msg .= $row['bodega'];
	$msg .= '</td>';
	$msg .= '<td align="right">' ;
	$msg .= $row['productos'];
	$msg .= '</td>';
	$msg .= '<td align="right">' ;
	$msg .= number_format($row['pesocarga'],0,"",",");
	$msg .= '</td>';
	$msg .= '<td align="right">' ;
	$msg .= strftime("%H:%M", strtotime($row['registro'])) ;
	$msg .= '</td>';
	$msg .= '<td align="right">' ;
	$msg .= strftime("%H:%M", strtotime($row['atencion'])) ;
	$msg .= '</td>';
	if ($_SESSION['nivel']==1 or $_SESSION['nivel']==2){
		$msg .= '<td class="jtable-command-column">';
		$msg .= '<button title="Actualizar registro" class="jtable-command-button jtable-edit-command-button"';
		$msg .= ' onclick="location.href=\'../Plantilla/vistaPrincipal.php?pg=../vistas/paginador4.php&lista=../vistas/frmActualizarrturnos.php?Id=';
		$msg .= $row['id'];
		$msg .= '&modulo=turnos';
		$msg .= '\'">';
		$msg .= ' <span>Actualizar registro</span>';
		$msg .= '</button>';
		$msg .= '</td>';

		$msg .= '<td class="jtable-command-column">';
		$msg .= '<button title="Agregar movimiento" class="jtable-command-button jtable-volcos-command-button" id="despachar"';
		$msg .= ' onclick="location.href=\'../Plantilla/vistaPrincipal.php?pg=../vistas/paginador4.php&lista=../vistas/frmActualizarrdespachos.php?x=1&tipo=' . $tipom . '&modulo=despachos&turno=' . $row['turno'] . '&fecha=' . $row['fecha'];
		$msg .= '\'">';				
		$msg .= ' <span>Eliminar registro</span>';
		$msg .= '</button></td>';		
		$msg .= '<td class="jtable-command-column">';
		$msg .= '<button title="Eliminar turno" class="jtable-command-button jtable-delete-command-button" id="eliminar"';
		$msg .= ' onclick="location.href=\'../controlador/coturnos.php?boton=eliminar&Id=' . $row['id'];
		$msg .= '\'">';				
		$msg .= ' <span>Eliminar turno</span>';
		$msg .= '</button></td>';		
	}
	$msg .= '</tr>';
		$u++;
}
$msg .= '<tr class="jtable-row-total"><td colspan="7">Totales</td><td align="right">' . number_format($rtopo->tkilos,0,"",",") . '</td><td colspan="4">&nbsp;</td></tr>';
$msg .= '</tbody></table></div>';

/* --------------------------------------------- */
$query_pag_num = "SELECT COUNT(*) AS count FROM gc_turnos " . $where;
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