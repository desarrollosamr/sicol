<?php
require "../clases/ConexionDatos.php";
extract ($_REQUEST);

//if (!isset($_SESSION['user']))
	//header("location:index.php?x=2");

$objConexion=Conectarse();

$productos = "select productosId, codigo, nombre from gc_productos order by nombre";
$rsproductos = $objConexion->query($productos);

$sql="select * from gc_materia_prima_por_orden where orden = '$_REQUEST[progid]'";
$resultadoproduccion = mysqli_query($objConexion,$sql) or die('MySql Error' . mysql_error());

$ordena = $_REQUEST['progid'];

$nuba = "select count(distinct numero_de_bache) as nba from gc_materia_prima_por_bache where orden =" . $ordena;
$rnuba = $objConexion->query($nuba);
$bactual = $rnuba->fetch_object();
$basi = $bactual->nba + 1;


?>

<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta charset="utf-8" />
<title>Formulario Actualizar Materia Prima Por Bache</title>
<script type="text/javascript">
	function siespar(numero)
	{
		var resto = numero.value%2;
		if (resto != 0) {
			alert("La cantidad no puede ser impar");
			return false;
			numero.focus();
		}
	}
</script>
</head>

<body>
<form id="form1" name="form1" method="post" action="../controlador/comatpribache.php">
        <?php if ($_REQUEST[x]==1) { ?>
		Agregar bache : <input name="bache" type="number" step="1" id="bache" value="<?php echo $basi?>"  style="width:145px" readonly="readonly" required/>
        <?php } else { ?>
		Actualizar bache : <input name="bache" type="number" step="1" id="bache" value="<?php echo $produccion->bache?>"  style="width:145px" readonly="readonly" required/>
        <?php } 
		$i=0;
		while ($produccion = mysqli_fetch_array($resultadoproduccion)) {
			$i = $i + 1;
			$inputnumber="matpri" . $i;?>
	    	<span><?php echo $produccion['producto']?></span>
            <input name="<?php echo $inputnumber ?>" type="number" id="<?php echo $inputnumber ?>" step="2" style="width:145px" onblur="siespar(this)" required/>
        <?php }?>
        <span>&nbsp;</span> 
        <?php if ($_REQUEST[x]==1) { ?>
        <input type="submit" name="boton" id="boton" class="button" value="Agregar" /> 
        <?php } else { ?>
        <input type="submit" name="boton" id="boton" class="button" value="Actualizar" /> 
        <?php } ?>
        <input type="button" name="cancelar" id="cancelar" class="button" value="Cancelar" onclick="window.history.go(-1); return false;" />
   </label>    
   <input name="Id" type="hidden" value="<?php echo $_REQUEST['Id'] ?>" />
   <input name="progid" type="hidden" value="<?php echo $ordena ?>"/>
   <input name="idprog" type="hidden" value="<?php echo $_REQUEST['idprog'] ?>"/>
</form>
</body>
</html>