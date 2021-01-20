<?php 
include"../clases/ConexionDatos.php";
$objConexion=Conectarse();
require_once("../clases/excel.php");
require_once("../clases/excel-ext.php");
$query_pag_data="SELECT  `gc_recibo_buques_contenedores`.`id` as id, `gc_recibo_buques_contenedores`.`programacionid` as proximo,  `gc_recibo_buques_contenedores`.`fecha` as fecha, `gc_recibo_buques_contenedores`.`producto` as producto,  `gc_productos`.`nombre` as prodnombre, `gc_presentacion`.`kilos` as kilos, `gc_bodegas`.`nombre` as bodnombre, `gc_recibo_buques_contenedores`.`tiquete` as tiquete,  `gc_recibo_buques_contenedores`.`placas` as placas,  `gc_recibo_buques_contenedores`.`peso_origen` as pesorigen,`gc_recibo_buques_contenedores`.`peso_bascula` as pesbascula FROM gc_recibo_buques_contenedores LEFT JOIN  `gc_productos` ON `gc_recibo_buques_contenedores`.`producto` =  `gc_productos`.`codigo` LEFT JOIN  `gc_presentacion` ON `gc_recibo_buques_contenedores`.`presentacion` =  `gc_presentacion`.`id` LEFT JOIN  `gc_bodegas` ON `gc_recibo_buques_contenedores`.`bodega` =  `gc_bodegas`.`id` ORDER BY `gc_recibo_buques_contenedores`.`fecha` desc";
$result_pag_data = mysqli_query($objConexion,$query_pag_data) or die('MySql Error' . mysql_error());
$assoc=array();
while ($row = mysqli_fetch_array($result_pag_data)){
	array_push($assoc, array("Producto"=>$row['producto'], "Presentacion"=>$row['kilos'], "Fecha"=>$row['fecha'], "Bodega"=>$row['bodnombre'], "Tiquete"=>$row['tiquete'], "Placas"=>$row['placas'], "Peso origen"=>$row['pesorigen'], "Peso bascula"=>$row['pesbascula']));
}
createExcel("excel-array.xls", $assoc);
exit;
?>