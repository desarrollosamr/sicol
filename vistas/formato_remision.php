<?php
include"../clases/ConexionDatos.php";
$objConexion=Conectarse();

extract($_REQUEST);
$rem=$_REQUEST[Id];
$tipom=$_REQUEST[tipo];
$des="SELECT gc_despachos.id as id,gc_despachos.fecha as fecha, gc_despachos.orden as orden, gc_despachos.placas as placas, gc_despachos.observacion as observacion, gc_despachos.remision as remision, gc_transportadores.razon_social as transportador, gc_transportadores.nit as nit, gc_conductores.cedula as cconductor, gc_conductores.nombre as conductor, gc_despachos.destino as destino, gc_clientes.nombre as cnombre, gc_clientes.nit as cnit from gc_despachos left join gc_transportadores on gc_despachos.transportador = gc_transportadores.nit left join gc_conductores on gc_despachos.conductor = gc_conductores.id left join gc_clientes on gc_despachos.cliente_destino = gc_clientes.nit where gc_despachos.remision=" . $rem . "  and gc_despachos.tipo='" . $tipom . "'";
$rdes=mysqli_query($objConexion,$des) or die("Error" . mysqli_error($objConexion));
$rsdes=mysqli_fetch_object($rdes);
$desp="SELECT gc_despachos_producto.id as id, gc_productos.nombre as producto, gc_productos.cliente, (select gc_clientes.nombre from gc_clientes where gc_clientes.nit = gc_productos.cliente) as cliente, gc_productos.codigo as codigo, gc_despachos_producto.cantidad_tm as ton, gc_despachos_producto.cantidad_sacos as sacos,  gc_despachos_producto.lote as lote, gc_bodegas.nombre as bodega, gc_motonaves.nombre as motonave from gc_despachos_producto left join gc_productos on gc_despachos_producto.producto = gc_productos.productosId left join gc_bodegas on gc_despachos_producto.bodega = gc_bodegas.Id left join gc_motonaves on gc_despachos_producto.motonave = gc_motonaves.motonaveId where gc_despachos_producto.remisionid=" . $rem . "  and gc_despachos_producto.tipo='" . $tipom . "'";
$rdesp=mysqli_query($objConexion,$desp) or die("Error" . mysqli_error($objConexion));
$frdesp=mysqli_num_rows($rdesp);
?>
<!DOCTYPE HTML>
<html>
<head>
<title></title>
<meta charset="utf-8">
</head>
<body>
<style type="text/css">
.tg  {border-collapse:collapse;border-spacing:0;}
.tg td{font-family:Arial, sans-serif;font-size:14px;padding:10px 5px;border-style:solid;border-width:1px;overflow:hidden;word-break:normal;}
.tg th{font-family:Arial, sans-serif;font-size:14px;font-weight:normal;padding:10px 5px;border-style:solid;border-width:1px;overflow:hidden;word-break:normal;}
.tg .tg-baqh{text-align:center;vertical-align:top;height:10px;padding:0px;margin:0px}
.tg .tg-yw4l{vertical-align:top;height:10px;padding:0px;margin:0px}
.tg .dtl{vertical-align:top;height:10px;padding:0px;margin:0px;font-size: 10px}
.tg .tg-24i8{font-size:24px;vertical-align:top}

</style>
<table class="tg">
  <tr>
    <th width="137" rowspan="5" class="tg-031e"><img src="../Imagenes/logo_nuevo.jpg" alt="" width="100" height="100" /></th>
    <th class="tg-yw4l" colspan="4" style="height: 10px; padding: 0px; margin: 0px" >I-LOGISTICA S.A.S.</th>
    <th class="tg-24i8" colspan="4" rowspan="2">GUIA DE REMISION</th>
  </tr>
  <tr>
    <td class="tg-yw4l" colspan="4" style="height: 10px; padding: 0px; margin: 0px" >Calle 16 No. 18B-180 Bodegas 5 y 6</td>
  </tr>
  <tr>
    <td class="tg-yw4l" colspan="4" style="height: 10px; padding: 0px; margin: 0px" >Tel. 236 1547 Ext. 106</td>
    <td class="tg-yw4l" colspan="4"><?php echo $rsdes->remision ?></td>
  </tr>
  <tr>
    <td class="tg-yw4l" colspan="4" style="height: 10px; padding: 0px; margin: 0px" >www.i-logistica.co</td>
    <th class="tg-24i8" colspan="4" >ORDEN</th>
  </tr>
  <tr>
    <td class="tg-yw4l" colspan="4" style="height: 10px; padding: 0px; margin: 0px" >operaciones@i-logistica.co</td>
    <td class="tg-yw41" colspan="4" ><?php echo $rsdes->orden ?></td>
  </tr>
  <tr>
    <td width="130" class="tg-yw4l" style="height: 10px; padding: 0px; margin: 0px" >FECHA EMISION</td>
    <td class="tg-yw4l" colspan="3" style="height: 10px; padding: 0px; margin: 0px" ><?php echo $rsdes->fecha ?></td>
    <td width="180" class="tg-yw4l" style="height: 10px; padding: 0px; margin: 0px" >PUNTO DE LLEGADA</td>
    <td class="tg-yw4l" colspan="4"><?php echo $rsdes->destino ?></td>
  </tr>
  <tr>
    <td class="tg-yw4l" style="height: 10px; padding: 0px; margin: 0px" >PUNTO DE SALIDA</td>
    <td class="tg-yw4l" colspan="3" style="height: 10px; padding: 0px; margin: 0px" >I-LOGISTICA SAS</td>
    <td class="tg-yw4l" style="height: 10px; padding: 0px; margin: 0px" >CLIENTE</td>
    <td class="tg-yw4l" colspan="4" style="height: 10px; padding: 0px; margin: 0px" ><?php echo $rsdes->cnombre ?></td>
  </tr>
  <tr>
    <td class="tg-yw4l" style="height: 10px; padding: 0px; margin: 0px" >FECHA INICIO</td>
    <td class="tg-yw4l" colspan="3" style="height: 10px; padding: 0px; margin: 0px" ><?php echo $rsdes->fecha ?></td>
    <td class="tg-yw4l" style="height: 10px; padding: 0px; margin: 0px" >NIT</td>
    <td class="tg-yw4l" colspan="4" style="height: 10px; padding: 0px; margin: 0px" ><?php echo $rsdes->cnit ?></td>
  </tr>
  <tr>
    <td class="tg-baqh" colspan="4" style="height: 10px; padding: 0px; margin: 0px" >DATOS VEHICULO Y CONDUCTOR</td>
    <td class="tg-baqh" colspan="5" style="height: 10px; padding: 0px; margin: 0px" >EMPRESA DE TRANSPORTES</td>
  </tr>
  <tr>
    <td class="tg-yw4l" style="height: 10px; padding: 0px; margin: 0px" >CONDUCTOR</td>
    <td class="tg-yw4l" colspan="3" style="height: 10px; padding: 0px; margin: 0px" ><?php echo $rsdes->conductor ?></td>
    <td class="tg-yw4l" style="height: 10px; padding: 0px; margin: 0px" >RAZON SOCIAL</td>
    <td class="tg-yw4l" colspan="4" style="height: 10px; padding: 0px; margin: 0px" ><?php echo $rsdes->transportador ?></td>
  </tr>
  <tr>
    <td class="tg-yw4l" style="height: 10px; padding: 0px; margin: 0px" >PLACA</td>
    <td width="73" class="tg-yw4l" style="height: 10px; padding: 0px; margin: 0px" ><?php echo $rsdes->placas ?></td>
    <td width="130" class="tg-yw4l" style="height: 10px; padding: 0px; margin: 0px" >CEDULA</td>
    <td width="33" class="tg-yw4l" style="height: 10px; padding: 0px; margin: 0px" ><?php echo $rsdes->cconductor ?></td>
    <td class="tg-yw4l" style="height: 10px; padding: 0px; margin: 0px" >NIT</td>
    <td class="tg-yw4l" colspan="4" style="height: 10px; padding: 0px; margin: 0px" <?php echo $rsdes->nit ?></td>
  </tr>
  <tr>
    <td class="tg-baqh" style="height: 10px; padding: 0px; margin: 0px" >CODIGO</td>
    <td class="tg-baqh" colspan="3" style="height: 10px; padding: 0px; margin: 0px" >DESCRIPCION</td>
    <td class="tg-baqh" style="height: 10px; padding: 0px; margin: 0px" >BODEGA</td>
    <td class="tg-baqh" style="height: 10px; padding: 0px; margin: 0px" >LOTE</td>
    <td class="tg-baqh" style="height: 10px; padding: 0px; margin: 0px" >MOTONAVE</td>
    <td  class="tg-baqh" style="height: 10px; padding: 0px; margin: 0px" >KILOS</td>
    <td  class="tg-baqh" style="height: 10px; padding: 0px; margin: 0px" >SACOS</td>
  </tr>
<?php
 if ($frdesp>0){
 	while($rsdesp=mysqli_fetch_array($rdesp)){ ?>
	 	  <tr>
		    <td class="dtl"><?php echo $rsdesp[codigo] ?></td>
		    <td class="dtl" colspan="3"><?php echo $rsdesp[producto] ?></td>
		    <td class="dtl"><?php echo $rsdesp[bodega] ?></td>		    
		    <td class="dtl"><?php echo $rsdesp[lote] ?></td>		    
		    <td class="dtl"><?php echo $rsdesp[motonave] ?></td>
		    <td class="dtl"><?php echo $rsdesp[ton] ?></td>
		    <td class="dtl"><?php echo $rsdesp[sacos] ?></td>
		  </tr>
<?php
	 }
 }
 ?>

  <tr>
    <td class="tg-baqh" colspan="9" style="height: 10px; padding: 0px; margin: 0px" >MOTIVO DEL TRASLADO</td>
  </tr>
  <tr>
    <td class="tg-yw4l">VENTA</td>
    <td class="tg-yw4l">X</td>
    <td class="tg-yw4l">CONSIGNACION</td>
    <td class="tg-yw4l"></td>
    <td class="tg-yw4l">GARANTIA</td>
    <td width="0" class="tg-yw4l"></td>
    <td class="tg-yw4l" colspan="2" rowspan="2">TRASLADO ENTRE<br> ALMACENES DE<br> LA MISMA EMPRESA</td>
    <td class="tg-yw4l" rowspan="2"></td>
  </tr>
  <tr>
    <td class="tg-yw4l">COMPRA</td>
    <td class="tg-yw4l"></td>
    <td class="tg-yw4l">DEVOLUCION</td>
    <td class="tg-yw4l"></td>
    <td class="tg-yw4l">TRASLADO A TERCEROS</td>
    <td class="tg-yw4l"></td>
  </tr>
  <tr><td colspan="2">OBSERVACIÓN</td><td colspan="7"><?php echo $rsdes->observacion ?></td></tr>
  <tr>
    <td colspan="9" style="height: 25px"></td>
  </tr>
  <tr>
    <td class="tg-baqh" colspan="4">I-LOGISTICA SAS</td>
    <td class="tg-baqh" colspan="5">RECIBI CONFORME</td>
  </tr>
</table>

</body>
</html>