<?php
session_start();
extract ($_REQUEST);
if (!isset($_SESSION['user'])){
	header("location:../index.php?x=2");
}
if (!isset($_REQUEST['pg']))
	$pg='pgInicial.php';
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>empresa ADSI - VIRTUAL</title>
<link rel="stylesheet" type="text/css" href="../css/actividades.css" />
<link rel="stylesheet" type="text/css" href="../css/jtable.css" />
<link href="../css/formularios.css" rel="stylesheet" type="text/css" />
<link rel="stylesheet" type="text/css" href="../css/chosen.min.css"/>
<script type="text/javascript" src="../javascript/jquery-1.7.2.min.js"></script> 
<script type="text/javascript" src="../javascript/chosen.jquery.min.js"></script>
</head>

<body>
<div id="divContenedor">
	<?php include "../Plantilla/encabezado.php";?>
      <div id="contentwrap">
        <?php include "../Plantilla/".$pg; ?>
      </div>
    <div> <?php include "../Plantilla/piePagina.php";?></div>    
</div>
</body>
</html>