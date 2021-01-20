<?php
session_start();
include"../clases/ConexionDatos.php";
extract($_REQUEST);
$objConexion=Conectarse();

$tipom=$_REQUEST['tipo'];
$fechai=$_REQUEST['fechai'];
$fechaf=$_REQUEST['fechaf'];
if ($_SESSION['nivel']==3){
	$cli=$_SESSION['userid'];
}else{
	$cli=$_REQUEST['cliente'];
}
$ncli="select nombre from gc_clientes where nit=" . $cli;
$rncli=mysqli_query($objConexion,$ncli);
$rsncli=mysqli_fetch_object($rncli);
$nombrecliente=$rsncli->nombre;
$criterio=$_REQUEST['criterio'];

$msg = "";
$msg .= '<div id="jtable-main-container"  class="jtable-main-container">';
$msg .= '<div class="jtable-title"><div class="jtable-title-text">Entradas y salidas generales entre fechas en kilos</div> </div>';

if ($fechai != $fechaf){
	$where = "WHERE gc_despachos_producto.fecha between '" . $fechai . "' and '" . $fechaf . "'";
}else{
	$where = "WHERE gc_despachos_producto.fecha = '" . $fechai . "'";				
}
$whereegral = $where . " and tipo='e' and (clase_movimiento='rp' or clase_movimiento='aj') ";
$wheresgral = $where . " and tipo='s' and (clase_movimiento='dp' or clase_movimiento='aj')";
$egral="SELECT sum(cantidad_tm) as tokilos, sum(cantidad_sacos) as tosacos FROm gc_despachos_producto " . $whereegral;
$regral=mysqli_query($objConexion,$egral) or die('MySql Error4' . mysqli_error($objConexion));
$rsegral=mysqli_fetch_object($regral);
$sgral="SELECT sum(cantidad_tm) as tokilos, sum(cantidad_sacos) as tosacos FROm gc_despachos_producto " . $wheresgral;
$rsgral=mysqli_query($objConexion,$sgral) or die('MySql Error4' . mysqli_error($objConexion));
$rssgral=mysqli_fetch_object($rsgral);
$msg .= '<table class="jtable"><thead class="ppal"><tr>';
$msg .= '<th class="jtable-column-header" style="width:50%;">';
$msg .= '<div class="jtable-column-header-container">';
$msg .= '<span class="jtable-column-header-text">Entradas</span>';
$msg .= '</div></th><th class="jtable-column-header" style="width:50%;"><div class="jtable-column-header-container"><span class="jtable-column-header-text">Salidas</span></div></th></tr></thead><tbody>';	
$msg .= '<tr><td>';
$msg .= number_format($rsegral->tokilos,0,"",",");
$msg .= '</td><td>';
$msg .= number_format($rssgral->tokilos,0,"",",");
$msg .= '</td></tr>';
$msg .= '</tbody></table></div>';

echo $msg;
?>