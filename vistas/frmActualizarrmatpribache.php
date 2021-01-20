<?php
require "../clases/ConexionDatos.php";
extract ($_REQUEST);

//if (!isset($_SESSION['user']))
	//header("location:index.php?x=2");

$objConexion=Conectarse();
$ordena = $_REQUEST['progid'];

$topu = "select count(*) as nmp from gc_materia_prima_por_orden where orden =" . $ordena;
$rtopu = $objConexion->query($topu);
$topue = $rtopu->fetch_object();
$mpto = $topue->nmp;

$nuba = "select max(numero_de_bache) as nba from gc_materia_prima_por_bache where orden =" . $ordena;
$rnuba = $objConexion->query($nuba);
$bactual = $rnuba->fetch_object();

$topxb = "select count(*) as rxb from gc_materia_prima_por_bache where numero_de_bache =" . $bactual->nba;
$rtoxb = mysqli_query($objConexion,$topxb) or die('MySql Error' . mysql_error());
$rstoxb = mysqli_fetch_array($rtoxb);

//echo $mpto . "-" . $bactual->nba . "-" . $rstoxb['rxb'] . "-" . $ordena;
//exit;

if ($rstoxb['rxb'] < $mpto){
	$nubache = $bactual->nba;
	$puesto = $rstoxb['rxb'] +1;
} else {
	$nubache = $bactual->nba + 1;
	$puesto = 1;
}

$sql="select producto,cantidad from gc_materia_prima_por_orden where orden = '$_REQUEST[progid]' and puesto = '$puesto'";
$resultadoproduccion = mysqli_query($objConexion,$sql) or die('MySql Error' . mysql_error());
$produccion = mysqli_fetch_array($resultadoproduccion);

$productos = "select codigo, nombre from gc_productos where codigo = '$produccion[producto]' ";
$rsproductos = mysqli_query($objConexion,$productos) or die('MySql Error' . mysql_error());
$lpro = mysqli_fetch_array($rsproductos);

$sqlba = "select * from gc_materia_prima_por_bache where id ='$_REQUEST[id]'";
$rssqlba = mysqli_query($objConexion,$sqlba) or die('MySql Error' . mysql_error());
$rsbache = mysqli_fetch_array($rssqlba);
?>

<!DOCTYPE html>
<head>
<meta charset="utf-8" />
<title>Formulario Actualizar Materia Prima Por Bache</title>
<script type="text/javascript"  src="../javascript/acciones.js"></script>
<script type="text/javascript">
	function validar(numero)
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
<form id="form1" name="form1" method="post" action="../controlador/comatpribache.php" class="smart-small">
        <?php if ($_REQUEST[x]==1) { ?>
		<h1>Agregar bache : <?php echo $nubache?></h1>
        <input name="bache" type="hidden" id="bache" value="<?php echo $nubache?>" />
        <?php } else { ?>
		<h1>Actualizar bache : <?php echo $rsbache[numero_de_bache]?></h1>
        <input name="bache" type="hidden" id="bache" value="<?php echo $rsbache[numero_de_bache]?>"/>
        <?php } ?>
		<label>
	    	<span><?php echo $lpro['nombre']?></span>
            <input name="cantidad" type="number" id="cantidad" step="2" style="width:137px" onBlur="validar(this)"  value="<?php echo $rsbache[cantidad]?>" required/>
            <input name="producto" type="hidden" id="producto" value="<?php echo $lpro['codigo']?>"/>
        </label>
        <span>&nbsp;</span> 
        <?php if ($_REQUEST[x]==1) { ?>
        <input type="submit" name="boton" class="button" value="Agregar" /> 
        <?php } else { ?>
        <input type="submit" name="boton" class="button" value="Actualizar" /> 
        <?php } ?>
        <input type="button" name="cancelar" id="cancelar" class="button" value="Cancelar" onClick="window.history.go(-1); return false;" />
   </label>    
   <input name="Id" type="hidden" value="<?php echo $_REQUEST['Id'] ?>" />
   <input name="progid" type="hidden" value="<?php echo $ordena ?>"/>
   <?php if ($puesto <= $mpto){ ?>
   			<input name="puesto" type="hidden" value="<?php echo $_REQUEST['puesto'] ?>"/>
   <?php }else{?>
   			<input name="puesto" type="hidden" value="1"/>
   <?php }?>
</form>
</body>
</html>