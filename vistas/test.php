<?php
 require_once ('../clases/jpgraph.php');
 require_once ('../clases/jpgraph_line.php');

include"../clases/ConexionDatos.php";
$objConexion=Conectarse();
$sql = "SELECT fecha as fecha, SUM( cantidad_a_reportar ) as tm FROM  `gc_produccion` WHERE 1 GROUP BY fecha";
$resultado = mysqli_query($objConexion,$sql) or die('MySql Error' . mysql_error());
?>
<div style="float:left"><table border="0" cellpadding="0" cellspacing="0" class="tabla">
<tr><th>Fecha</td><th>Dia</td></tr>
<?php
$u=0;
while ($row = mysqli_fetch_array($result_pag_data)) {
?>
<tr class="modo1"><th><?php echo $row['fecha'] ?></td><td style="text-align:right"><?php echo $row['tm'] ?></td></tr>
<?php
	$u++;
}
?>
</table></div>
?>