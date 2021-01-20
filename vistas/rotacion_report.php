<?php
session_start();
include"../clases/ConexionDatos.php";
extract($_REQUEST);
$objConexion = Conectarse();
$vactmp = "truncate gc_tmpexistencias";
$rvactmp = mysqli_query($objConexion,$vactmp) or die('MySql Error1' . mysql_error());
$tipom  = $_REQUEST['tipo'];
$fecha = $_REQUEST['fechai'];
$fechaf = $_REQUEST['fechaf'];
require_once"../clases/calcular_existencias1.php";
if ($_SESSION['nivel'] == 3){
	$cli = $_SESSION['userid'];
}else{
	$cli = $_REQUEST['cliente'];
}
$ncli   = "select nombre from gc_clientes where nit=" . $cli;
$rncli  = mysqli_query($objConexion,$ncli);
$rsncli = mysqli_fetch_object($rncli);
$nombrecliente = $rsncli->nombre;
$pro    = $_REQUEST['producto'];
$bod    = $_REQUEST['bodega'];
$nbod   = "select nombre from gc_bodegas where Id=" . $bod;
$rnbod  = mysqli_query($objConexion,$nbod);
$rsnbod = mysqli_fetch_object($rnbod);
$nombrebodega = $rsnbod->nombre;
$criterio = $_REQUEST['criterio'];

$msg = "";
$msg .= '<div id="jtable-main-container"  class="jtable-main-container">';
$msg .= '<div class="jtable-title"><div class="jtable-title-text">Indices de rotación de inventario por ' . $criterio . ' entre ' . $fecha . ' y ' . $fechaf . '</div> </div>';
$msg .= '<table class="jtable">';
$msg .= '<thead class="ppal"><tr>';
$msg .= '<th class="jtable-column-header" style="width:40%;">';
$msg .= '<div class="jtable-column-header-container">';
$msg .= '<span class="jtable-column-header-text">';
$msg .= ucfirst($criterio); 
$ms .= '</span>';
$msg .= '</div></th><th class="jtable-column-header" style="width:14%;"><div class="jtable-column-header-container"><span class="jtable-column-header-text">Existencia inicial</span></div></th><th class="jtable-column-header" style="width:14%;"><div class="jtable-column-header-container"><span class="jtable-column-header-text">Entradas</span></div></th><th class="jtable-column-header" style="width:14%;"><div class="jtable-column-header-container"><span class="jtable-column-header-text">Salidas</span></div></th><th class="jtable-column-header" style="width:18%;"><div class="jtable-column-header-container"><span class="jtable-column-header-text">Kilos en bodega por dia</span></div></th></tr></thead><tbody>';	

switch ($criterio){
	case "producto":
		$pro = "select * from gc_productos order by nombre";
		$rpro = mysqli_query($objConexion,$pro) or die('MySql Error1' . mysql_error());
		$toei = 0;
		$toen = 0;
		$tosa = 0;
		$u = 0;
		while ($rspro = mysqli_fetch_array($rpro)){
		    if ($u%2==0){ 
				$msg .= '<tr class="jtable-row-even">';
					} else {
				$msg .= '<tr class="jtable-row-evenf">';
			}			
			$proa = $rspro[productosId];
			$npro = "select nombre from gc_productos where productosId=" . $proa;
			$rnpro = mysqli_query($objConexion,$npro);
			$rsnpro = mysqli_fetch_object($rnpro);
			$nombreproducto = $rsnpro->nombre;
			
			$exin="select sum(cantidad_tm) as kilos from gc_tmpexistencias where producto=" . $proa;
			$rexin = mysqli_query($objConexion,$exin);
			$rsexin = mysqli_fetch_object($rexin);
			$existi = $rsexin->kilos;
			
			$where = "WHERE producto=" . $proa . " and fecha between '" . $fecha . "' and '" . $fechaf . "'";
			$whereegral = $where . " and tipo='e' and (clase_movimiento='rp' or clase_movimiento='aj') ";
			$wheresgral = $where . " and tipo='s' and (clase_movimiento='dp' or clase_movimiento='aj')";
			$egral = "SELECT sum(cantidad_tm) as tokilos, sum(cantidad_sacos) as tosacos from gc_despachos_producto " . $whereegral;
			$regral = mysqli_query($objConexion,$egral) or die('MySql Error4' . mysqli_error($objConexion));
			$rsegral = mysqli_fetch_object($regral);
			$sgral = "SELECT sum(cantidad_tm) as tokilos, sum(cantidad_sacos) as tosacos from gc_despachos_producto " . $wheresgral;
			$rsgral = mysqli_query($objConexion,$sgral) or die('MySql Error4' . mysqli_error($objConexion));
			$rssgral = mysqli_fetch_object($rsgral);
			$saldo = abs($rsegral->tokilos - $rssgral->tokilos);
			$tiempo_en_bodega = date_diff(date_create($fecha),date_create($fechaf));
			$prodia = floor($saldo/$tiempo_en_bodega->days);
			$msg .= '<td>';
			$msg .= $nombreproducto;
			$msg .= '</td><td align="right">';
			$msg .= number_format($existi,0,"",",");
			$msg .= '</td><td align="right">';			
			$msg .= number_format($rsegral->tokilos,0,"",",");
			$msg .= '</td><td align="right">';
			$msg .= number_format($rssgral->tokilos,0,"",",");
			$msg .= '</td><td align="right">';
			$msg .= number_format($prodia,0,"",",");
			$msg .= '</td></tr>';
			$toei = $toei + $existi;
			$toen = $toen+$rsegral->tokilos;
			$tosa = $tosa+$rssgral->tokilos;
			$sage = abs($toen-$tosa);
			$proge = floor($sage/$tiempo_en_bodega->days);
			$u++;
		}
		$msg .= '<tr class="jtable-row-total"><td>Totales</td>';
		$msg .= '<td align="right">' . number_format($toei,0,"",",") . '</td><td align="right">' . number_format($toen,0,"",",") . '</td><td align="right">' . number_format($tosa,0,"",",") . '</td><td align="right">' . number_format($proge,0,"",",") . '</td></tr>';
		break;

	case "bodega":
		$bod = "select * from gc_bodegas order by nombre";
		$rbod = mysqli_query($objConexion,$bod) or die('MySql Error1' . mysql_error());
		$toei = 0;
		$toen = 0;
		$tosa = 0;
		$u = 0;
		while ($rsbod = mysqli_fetch_array($rbod)){
		    if ($u%2==0){ 
				$msg .= '<tr class="jtable-row-even">';
					} else {
				$msg .= '<tr class="jtable-row-evenf">';
			}
			$boda = $rsbod[Id];
			$nbod = "select nombre from gc_bodegas where Id=" . $boda;
			$rnbod = mysqli_query($objConexion,$nbod);
			$rsnbod = mysqli_fetch_object($rnbod);
			$nombrebodega = $rsnbod->nombre;
			
			$exin="select sum(cantidad_tm) as kilos from gc_tmpexistencias where bodega=" . $boda;
			$rexin = mysqli_query($objConexion,$exin);
			$rsexin = mysqli_fetch_object($rexin);
			$existi = $rsexin->kilos;
			
			$where = "WHERE bodega=" . $boda . " and fecha between '" . $fecha . "' and '" . $fechaf . "'";
			$whereegral = $where . " and tipo='e' and (clase_movimiento='rp' or clase_movimiento='aj') ";
			$wheresgral = $where . " and tipo='s' and (clase_movimiento='dp' or clase_movimiento='aj')";
			$egral = "SELECT sum(cantidad_tm) as tokilos, sum(cantidad_sacos) as tosacos from gc_despachos_producto " . $whereegral;
			$regral = mysqli_query($objConexion,$egral) or die('MySql Error4' . mysqli_error($objConexion));
			$rsegral = mysqli_fetch_object($regral);
			$sgral = "SELECT sum(cantidad_tm) as tokilos, sum(cantidad_sacos) as tosacos from gc_despachos_producto " . $wheresgral;
			$rsgral = mysqli_query($objConexion,$sgral) or die('MySql Error4' . mysqli_error($objConexion));
			$rssgral = mysqli_fetch_object($rsgral);
			$saldo = abs($rsegral->tokilos - $rssgral->tokilos);
			$tiempo_en_bodega = date_diff(date_create($fecha),date_create($fechaf));
			$prodia = floor($saldo/$tiempo_en_bodega->days);
			$msg .= '<td>';
			$msg .= $nombrebodega;
			$msg .= '</td><td align="right">';
			$msg .= number_format($existi,0,"",",");
			$msg .= '</td><td align="right">';			
			$msg .= number_format($rsegral->tokilos,0,"",",");
			$msg .= '</td><td align="right">';
			$msg .= number_format($rssgral->tokilos,0,"",",");
			$msg .= '</td><td align="right">';
			$msg .= number_format($prodia,0,"",",");
			$msg .= '</td></tr>';
			$toei = $toei + $existi;
			$toen = $toen+$rsegral->tokilos;
			$tosa = $tosa+$rssgral->tokilos;
			$sage = abs($toen-$tosa);
			$proge = floor($sage/$tiempo_en_bodega->days);
			$u++;
		}
		$msg .= '<tr class="jtable-row-total"><td>Totales</td>';
		$msg .= '<td align="right">' . number_format($toei,0,"",",") . '</td><td align="right">' . number_format($toen,0,"",",") . '</td><td align="right">' . number_format($tosa,0,"",",") . '</td><td align="right">' . number_format($proge,0,"",",") . '</td></tr>';			
		break;

	case "cliente":
		$cli = "select * from gc_clientes order by nombre";
		$rcli = mysqli_query($objConexion,$cli) or die('MySql Error1' . mysql_error());
		$toei = 0;
		$toen = 0;
		$tosa = 0;
		$u = 0;
		while ($rscli = mysqli_fetch_array($rcli)){
		    if ($u%2==0){ 
				$msg .= '<tr class="jtable-row-even">';
					} else {
				$msg .= '<tr class="jtable-row-evenf">';
			}
			$clia = $rscli[nit];
			$ncli = "select nombre from gc_clientes where nit=" . $clia;
			$rncli = mysqli_query($objConexion,$ncli);
			$rsncli = mysqli_fetch_object($rncli);
			$nombrecliega = $rsncli->nombre;
			
			$exin="select sum(cantidad_tm) as kilos from gc_tmpexistencias where cliente=" . $clia;
			$rexin = mysqli_query($objConexion,$exin);
			$rsexin = mysqli_fetch_object($rexin);
			$existi = $rsexin->kilos;
			
			$where = "WHERE cliente=" . $clia . " and fecha between '" . $fecha . "' and '" . $fechaf . "'";
			$whereegral = $where . " and tipo='e' and (clase_movimiento='rp' or clase_movimiento='aj') ";
			$wheresgral = $where . " and tipo='s' and (clase_movimiento='dp' or clase_movimiento='aj')";
			$egral = "SELECT sum(cantidad_tm) as tokilos, sum(cantidad_sacos) as tosacos from gc_despachos_producto " . $whereegral;
			$regral = mysqli_query($objConexion,$egral) or die('MySql Error4' . mysqli_error($objConexion));
			$rsegral = mysqli_fetch_object($regral);
			$sgral = "SELECT sum(cantidad_tm) as tokilos, sum(cantidad_sacos) as tosacos from gc_despachos_producto " . $wheresgral;
			$rsgral = mysqli_query($objConexion,$sgral) or die('MySql Error4' . mysqli_error($objConexion));
			$rssgral = mysqli_fetch_object($rsgral);
			$saldo = abs($rsegral->tokilos - $rssgral->tokilos);
			$tiempo_en_bodega = date_diff(date_create($fecha),date_create($fechaf));
			$prodia = floor($saldo/$tiempo_en_bodega->days);
			$msg .= '<td>';
			$msg .= $nombrecliega;
			$msg .= '</td><td align="right">';
			$msg .= number_format($existi,0,"",",");
			$msg .= '</td><td align="right">';			
			$msg .= number_format($rsegral->tokilos,0,"",",");
			$msg .= '</td><td align="right">';
			$msg .= number_format($rssgral->tokilos,0,"",",");
			$msg .= '</td><td align="right">';
			$msg .= number_format($prodia,0,"",",");
			$msg .= '</td></tr>';
			$toei = $toei + $existi;
			$toen = $toen+$rsegral->tokilos;
			$tosa = $tosa+$rssgral->tokilos;
			$sage = abs($toen-$tosa);
			$proge = floor($sage/$tiempo_en_bodega->days);
			$u++;
		}
		$msg .= '<tr class="jtable-row-total"><td>Totales</td>';
		$msg .= '<td align="right">' . number_format($toei,0,"",",") . '</td><td align="right">' . number_format($toen,0,"",",") . '</td><td align="right">' . number_format($tosa,0,"",",") . '</td><td align="right">' . number_format($proge,0,"",",") . '</td></tr>';					
	break;
}
$msg .= '</tbody></table></div>';
echo $msg;
?>