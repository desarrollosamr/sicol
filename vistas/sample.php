<?php
require_once '../clases/anysqltoexcel.php';

$sqlSheetList = array(
	WorkbookUtil::createSqlSheet('Sheet 1', "select * from gc_productos"),
);

// Anda dapat membuat implementasi SqlExplorer sendiri misalnya untuk oracle. 
// Kami hanya menyediakan contoh implementasi SqlExplorer untuk mysql
$sqlExplorer = new MysqlSqlExplorer('mysql.hostinger.es','u981758803_titob','the_reborn','u981758803_activ'); 
$writerCallback = new DefaultWriterCallback();

$pearWorkBookHelper = new PearWorkBookHelper();
$pearWorkBookHelper->setSqlExplorer($sqlExplorer);
$pearWorkBookHelper->setWriterCallback($writerCallback);
$workbook =& $pearWorkBookHelper->createWorkBook($sqlSheetList);
$workbook->send('relacion.xls');
$workbook->close();