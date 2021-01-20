<?php
require_once("../clases/excel.php");
require_once("../clases/excel-ext.php");
$assoc = array(
 array("Nombre"=>"Mattias", "Edad"=>40),
 array("Nombre"=>"Tony", "Edad"=>15),
 array("Nombre"=>"Peter", "Edad"=>30),
 array("Nombre"=>"Edvard", "Edad"=>20)
 );
createExcel("excel-array.xls", $assoc);
exit;
?>