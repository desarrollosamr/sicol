<?php
include"../clases/ConexionDatos.php";
$objConexion=Conectarse();
extract($_REQUEST);
$criterio = $_REQUEST['criterio'];
$modulo = $_REQUEST['modulo'];
$xls_filename = $modulo.'_'.date('Y-m-d').'.xls'; // Define Excel (.xls) file name
switch ($modulo) {
	Case 'recibos':

		$query_pag_data="SELECT  `gc_recibo_buques_contenedores`.`id` as id, `gc_recibo_buques_contenedores`.`programacionid` as proximo,  `gc_recibo_buques_contenedores`.`fecha` as fecha, `gc_recibo_buques_contenedores`.`producto` as producto,  `gc_productos`.`nombre` as prodnombre, `gc_presentacion`.`kilos` as kilos, `gc_bodegas`.`nombre` as bodnombre, `gc_recibo_buques_contenedores`.`tiquete` as tiquete,  `gc_recibo_buques_contenedores`.`placas` as placas,  `gc_recibo_buques_contenedores`.`peso_origen` as pesorigen,`gc_recibo_buques_contenedores`.`peso_bascula` as pesbascula FROM gc_recibo_buques_contenedores LEFT JOIN  `gc_productos` ON `gc_recibo_buques_contenedores`.`producto` =  `gc_productos`.`codigo` LEFT JOIN  `gc_presentacion` ON `gc_recibo_buques_contenedores`.`presentacion` =  `gc_presentacion`.`id` LEFT JOIN  `gc_bodegas` ON `gc_recibo_buques_contenedores`.`bodega` =  `gc_bodegas`.`id` WHERE programacionid=" . $criterio . " ORDER BY `gc_recibo_buques_contenedores`.`fecha` desc";
		$result = mysqli_query($objConexion,$query_pag_data) or die('MySql Error' . mysql_error());
		break;
		
	Case 'emp_entradas':
		$query_pag_data = "SELECT gc_emp_recibo.id as codigo, gc_emp_recibo.fecha as fecha, gc_empaques.nombre as grado, gc_emp_recibo.cantidad as cantidad, gc_bodegas.nombre as bodega, gc_emp_recibo.origen as origen, gc_emp_recibo.observacion as observacion from gc_emp_recibo left join gc_empaques on gc_emp_recibo.grado = gc_empaques.codigo left join gc_bodegas on gc_emp_recibo.bodega = gc_bodegas.id order by gc_emp_recibo.fecha desc";
		$result = mysqli_query($objConexion,$query_pag_data) or die('MySql Error' . mysql_error());
		break;
		
	Case 'emp_salidas':
		$query_pag_data="SELECT `gc_emp_despacho`.`id` as id,`gc_emp_despacho`.`fecha` as fecha,`gc_empaques`.`nombre` as grado,`gc_emp_despacho`.`cantidad` as cantidad,`gc_emp_actividades`.`nombre` as motivo,`gc_recibo_buques_contenedores`.`programacionid` as operacion,`gc_emp_despacho`.`orden` as orden,`gc_productos`.`nombre` as producto FROM gc_emp_despacho
LEFT JOIN `gc_empaques` ON `gc_emp_despacho`.`grado` = `gc_empaques`.`codigo` 
LEFT JOIN `gc_emp_actividades` ON `gc_emp_despacho`.`motivo` = `gc_emp_actividades`.`id` 
LEFT JOIN `gc_recibo_buques_contenedores` ON `gc_emp_despacho`.`operacion` = `gc_recibo_buques_contenedores`.`Id` 
LEFT JOIN `gc_productos` ON `gc_emp_despacho`.`producto` = `gc_productos`.`productosId` order by gc_emp_despacho.fecha desc";
		$result = mysqli_query($objConexion,$query_pag_data) or die('MySql Error' . mysql_error());
		break;
		
	Case 'produccion':
		$query_pag_data = "SELECT gc_produccion.id as codigo, gc_produccion.fecha as fecha, gc_produccion.orden as orden, gc_produccion.acta as acta, gc_produccion.cantidad_a_reportar as tm, gc_produccion.turno as turno, gc_empaques.nombre as empaque, gc_productos.nombre as producto, gc_produccion.observaciones as observaciones, gc_produccion.empaques_cantidad as empaques from gc_produccion left join gc_empaques on gc_produccion.empaque = gc_empaques.codigo left join gc_productos on gc_produccion.producto = gc_productos.codigo order by gc_produccion.acta desc";
		$result = mysqli_query($objConexion,$query_pag_data) or die('MySql Error' . mysql_error());
		break;

	Case 'despachos':
		$query_pag_data = "SELECT gc_despachos_producto.id as id, gc_despachos_producto.remisionid as remision, gc_productos.nombre as producto, gc_despachos_producto.cantidad_tm as ton, gc_despachos_producto.cantidad_sacos as sacos,  gc_despachos_producto.lote as lote, gc_despachos_producto.motonave as nave, gc_bodegas.nombre as bodega from gc_despachos_producto left join gc_productos on gc_despachos_producto.producto = gc_productos.productosId left join gc_bodegas on gc_despachos_producto.bodega = gc_bodegas.Id order by gc_productos.nombre desc";
		$result = mysqli_query($objConexion,$query_pag_data) or die('MySql Error' . mysql_error());
		break;
}

// Header info settings
header("Content-Type: application/vnd.ms-excel");
header("Content-Disposition: attachment; filename=$xls_filename");
header("Pragma: no-cache");
header("Expires: 0");
 
/***** Start of Formatting for Excel *****/
// Define separator (defines columns in excel &amp; tabs in word)
$sep = "\t"; // tabbed character
 
// Start of printing column names as names of MySQL fields
for ($i = 0; $i<mysqli_num_fields($result); $i++) {
  echo $fieldName = mysqli_fetch_field_direct($result, $i)->name . "\t";
}
print("\n");
// End of printing column names
 
// Start while loop to get data
while($row = mysqli_fetch_row($result))
{
  $schema_insert = "";
  for($j=0; $j<mysqli_num_fields($result); $j++)
  {
    if(!isset($row[$j])) {
      $schema_insert .= "NULL".$sep;
    }
    elseif ($row[$j] != "") {
      $schema_insert .= "$row[$j]".$sep;
    }
    else {
      $schema_insert .= "".$sep;
    }
  }
  $schema_insert = str_replace($sep."$", "", $schema_insert);
  $schema_insert = preg_replace("/\r\n|\n\r|\n|\r/", " ", $schema_insert);
  $schema_insert .= "\t";
  print(trim($schema_insert));
  print "\n";
}
?>