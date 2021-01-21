<?php
extract($_REQUEST);
if ($_POST[x]==9) {
	echo "<font size=3px color=red><b>El acumulado de las materias primas debe ser menor o igual a 5000 Kls, revise por favor</b></font>";
}
$progid=$_REQUEST['progid'];
$where = "WHERE 1=1 AND orden LIKE '$progid'";
	 
include"../clases/ConexionDatos.php";
$objConexion=Conectarse();
//$totalbache = "select sum(cantidad) as total from gc_materia_prima_por_orden where orden=" . $criterio;
//$rstotalbache = mysqli_query($objConexion,$totalbache) or die('MySql Error' . mysqli_error());
//$acumulado = $rstotalbache->fetch_array();

$deltmpbaches="truncate gc_tmpbaches";
$borra=mysqli_query($objConexion,$deltmpbaches) or die('MySql Error' . mysql_error());

$query_pag_data = "insert into gc_tmpbaches(orden,bache,producto,cantidad,bacheid) select gc_materia_prima_por_bache.orden, gc_materia_prima_por_bache.numero_de_bache as bache, gc_productos.nombre as producto, gc_materia_prima_por_bache.cantidad as cantidad, gc_materia_prima_por_bache.id as bacheid from gc_materia_prima_por_bache left join gc_productos on gc_materia_prima_por_bache.materia_prima=gc_productos.codigo " . $where ;

$result_pag_data = mysqli_query($objConexion,$query_pag_data) or die('MySql Error' . mysql_error());

$nuba="select distinct bache as bache from gc_tmpbaches where orden like '$progid'";
$rnuba=mysqli_query($objConexion,$nuba) or die('MySql Error' . mysql_error());

$ntba="select count(distinct bache) as ntbaches from gc_tmpbaches where orden like '$progid'";
$rntba=mysqli_query($objConexion,$ntba) or die('MySql Error' . mysql_error());
$nutoba=mysqli_fetch_array($rntba);
$ntbaches=$nutoba['ntbaches'];
$ntablas=ceil($ntbaches/10);
?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta charset="utf-8" />
<title>Formulario Actualizar Materia Prima Por Bache</title>
<script type="text/javascript">
$(document).ready(function(){
   $("#todo").click(function(evento){
	  if($("#todo").is(':checked')) { 
	  	 $("input[name='seleccion[]']").attr('checked','checked');
	  }else{
		 $("input[name='seleccion[]']").attr('checked',false); 
	  }
   });
   $("#acta").click(function(evento){
	  var $checkboxes = $("input[name='seleccion[]']");
	  alert ("Hay " + $checkboxes.size() + " párrafos en la página");
   });
});
</script>

</head>
<body>
<div id="jtable-main-container"  class="jtable-main-container">
<div class="jtable-title"><div class="jtable-title-text">Baches para la orden No. <?php echo $progid ?><div style="float:right"><input type="checkbox" id="todo">Seleccionar todo<img id="acta" style="float:right;padding:8px;cursor:copy" src="../Imagenes/acta.png"></div></div></div>
<table class="jtable">
<thead>
<tr><th class="jtable-column-header" style="width:27%;"><div class="jtable-column-header-container"><span class="jtable-column-header-text">Producto</span></div></th>
<?php while ($idbaches = mysqli_fetch_array($rnuba)) {
	$idchk="chk".$idbaches['bache']?>
	<th class="jtable-column-header" style="width:6%; text-align:center"><input type="checkbox" name="seleccion[]" id="<?php echo $idchk?>" value="<?php echo $idbaches['bache']?>"/><?php echo $idbaches['bache'] ?></th>
<?php }?>
</tr></thead>
<tbody>
<?php
$matpri="select distinct producto from gc_tmpbaches";
$rmatpri = mysqli_query($objConexion,$matpri) or die('MySql Error' . mysql_error());
$t=0;
while ($row = mysqli_fetch_array($rmatpri)) {
	if ($t%2==0){ ?>
		<tr class="jtable-row-even">
	<?php } else {?>		
		<tr class="jtable-row-evenf">
	<?php
    }
	$prod = $row['producto'];
	$cxmp="select bache, cantidad, bacheid from gc_tmpbaches where producto='" . $prod . "'";
	$rcxmp=mysqli_query($objConexion,$cxmp) or die('MySql Error' . mysql_error());
	$baches = array();
	while ($rowsxp = mysqli_fetch_array($rcxmp)) {
		array_push($baches, array($rowsxp['cantidad'],$rowsxp['bacheid']));
	} ?>
	<td><?php echo $row['producto']?></td>
    <?php
	foreach($baches as list($cxbache,$bacheid))
 	{ ?>
		<td align="right"><a href="../Plantilla/vistaPrincipal.php?pg=../vistas/paginador5.php&progid=<?php echo $progid?>&modulo=produccion&id=<?php echo $bacheid?>&x=2"><?php echo $cxbache ?></a></td>
 	<?php }?>	
	</tr>
<?php 		$u++;
}?>
</tbody></table></div></div>
</body>
</html>

