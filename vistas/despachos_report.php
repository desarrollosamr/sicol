<?php
session_start();
include"../clases/ConexionDatos.php";
extract($_REQUEST);
$objConexion=Conectarse();

$tipom=$_REQUEST['tipo'];
$fechai=$_REQUEST['fechai'];
$fechaf=$_REQUEST['fechaf'];
if ($_SESSION['nivel']==3){
	$cli=$_SESSION['userid'];
}else{
	$cli=$_REQUEST['cliente'];
}
$ncli="select nombre from gc_clientes where nit=" . $cli;
$rncli=mysqli_query($objConexion,$ncli);
$rsncli=mysqli_fetch_object($rncli);
$nombrecliente=$rsncli->nombre;
$pro=$_REQUEST['producto'];
$npro="select nombre from gc_productos where productosId=" . $pro;
$rnpro=mysqli_query($objConexion,$npro);
$rsnpro=mysqli_fetch_object($rnpro);
$nombreproducto=$rsnpro->nombre;
$bod=$_REQUEST['bodega'];
$nbod="select nombre from gc_bodegas where Id=" . $bod;
$rnbod=mysqli_query($objConexion,$nbod);
$rsnbod=mysqli_fetch_object($rnbod);
$nombrebodega=$rsnbod->nombre;
$criterio=$_REQUEST['criterio'];
$msg = "";
$msg .= '<div id="jtable-main-container"  class="jtable-main-container">';
if ($tipom=="s"){
	$msg .= '<div class="jtable-title"><div class="jtable-title-text">Despachos de producto terminado</div> </div>';
} elseif ($tipom=="e"){
	$msg .= '<div class="jtable-title"><div class="jtable-title-text">Recibos de producto terminado</div></div>';
}
switch ($criterio){
	case "general":
		if ($tipom=="s"){
			if ($fechai != $fechaf){
				$where = "WHERE tipo='s' and fecha between '" . $fechai . "' and '" . $fechaf . "'";
			}else{
				$where = "WHERE tipo='s' and fecha = '" . $fechai . "'";				
			}
	    	$query_pag_data = "SELECT gc_despachos.id as id,gc_despachos.fecha as fecha, gc_despachos.orden as orden, gc_despachos.tiquete as tiquete, gc_despachos.placas as placas, gc_despachos.observacion as observacion, gc_despachos.remision as remision, gc_despachos.tipo as tipo, gc_despachos.tiquete as tiquete, gc_conductores.nombre as conductor, gc_transportadores.razon_social as transportador from gc_despachos left join gc_conductores on gc_despachos.conductor=gc_conductores.id left join gc_transportadores on gc_despachos.transportador = gc_transportadores.nit " . $where . " order by gc_despachos.fecha desc ";
		}
		if ($tipom=="e"){
			if ($fechai != $fechaf){
				$where = "WHERE tipo='e' and gc_despachos.fecha between '" . $fechai . "' and '" . $fechaf . "'";
			}else{
				$where = "WHERE tipo='e' and gc_despachos.fecha = '" . $fechai . "'";				
			}
	    	$query_pag_data = "SELECT gc_despachos.id as id,gc_despachos.fecha as fecha, gc_despachos.orden as orden, gc_despachos.tiquete as tiquete, gc_despachos.placas as placas, gc_despachos.observacion as observacion, gc_despachos.remision as remision, gc_despachos.tipo as tipo, gc_despachos.tiquete as tiquete, gc_conductores.nombre as conductor, gc_transportadores.razon_social as transportador from gc_despachos left join gc_conductores on gc_despachos.conductor=gc_conductores.id left join gc_transportadores on gc_despachos.transportador = gc_transportadores.nit " . $where . " order by gc_despachos.fecha desc ";
		}
		$result_pag_data = mysqli_query($objConexion,$query_pag_data) or die('Error despacho' . mysqli_error($objConexion));
		$u=0;
		while ($row = mysqli_fetch_array($result_pag_data)) {
			$msg .= '<table class="jtable"><thead class="ppal"><tr>';
			$msg .= '<th class="jtable-column-header" style="width:8%;">';
			$msg .= '<div class="jtable-column-header-container">';
			$msg .= '<span class="jtable-column-header-text">Fecha</span>';
			$msg .= '</div></th><th class="jtable-column-header" style="width:8%;"><div class="jtable-column-header-container"><span class="jtable-column-header-text">Remision</span></div></th><th class="jtable-column-header" style="width:8%;"><div class="jtable-column-header-container"><span class="jtable-column-header-text">Orden</span></div></th><th class="jtable-column-header" style="width:8%;"><div class="jtable-column-header-container"><span class="jtable-column-header-text">Tiquete</span></div></th><th class="jtable-column-header" style="width:6%;"><div class="jtable-column-header-container"><span class="jtable-column-header-text">Placas</span></div></th><th class="jtable-column-header" style="width:31%;"><div class="jtable-column-header-container"><span class="jtable-column-header-text">Conductor</span></div></th><th class="jtable-column-header" style="width:31%;"><div class="jtable-column-header-container"><span class="jtable-column-header-text">Transportadora</span></div></th></tr></thead><tbody>';	
			$spxd="SELECT gc_despachos_producto.id as id, gc_productos.nombre as producto, gc_productos.cliente, (select gc_clientes.nombre from gc_clientes where gc_clientes.nit = gc_productos.cliente) as cliente, gc_despachos_producto.cantidad_tm as ton, gc_despachos_producto.cantidad_sacos as sacos,  gc_despachos_producto.lote as lote, gc_bodegas.nombre as bodega from gc_despachos_producto left join gc_productos on gc_despachos_producto.producto = gc_productos.productosId left join gc_bodegas on gc_despachos_producto.bodega = gc_bodegas.Id where gc_despachos_producto.remisionid=" . $row['remision'] . " and gc_despachos_producto.tipo='" . $tipom . "'";
			$rspxd=mysqli_query($objConexion,$spxd) or die('MySql Error' . mysqli_error($objConexion));
			$fspxd=mysqli_num_rows($rspxd);
			
				   if ($u%2==0){ 
			$msg .= '<tr class="jtable-row-even">';
				} else {
			$msg .= '<tr class="jtable-row-evenf">';
				}
			$msg .= '<td>';
			$msg .= $row['fecha'] ;
			$msg .= '</td>';
			$msg .= '<td>' ;
			$msg .= $row['remision'];
			$msg .= '</td>';
			$msg .= '<td>' ;
			$msg .= $row['orden'];
			$msg .= '</td>';
			$msg .= '<td>' ;
			$msg .= $row['tiquete'];
			$msg .= '</td>';
			$msg .= '<td>' ;
			$msg .= $row['placas'];
			$msg .= '</td>';
			$msg .= '<td>' ;
			$msg .= $row['conductor'];
			$msg .= '</td>';			
			$msg .= '<td>' ;
			$msg .= $row['transportador'];
			$msg .= '</td>';			
			$msg .= '</td></tr>';
			if ($fspxd > 0){
				$msg .= '<table class="jtable">';
				$msg .= '<thead>';
				$msg .= '<tr>';
				$msg .= '<th class="jtable-column-header" style="width:28%;">';
				$msg .= '<div class="jtable-column-header-container">';
				$msg .= '<span class="jtable-column-header-text" style="float:right">Producto</span></div></th><th class="jtable-column-header" style="width:6%;"><div class="jtable-column-header-container"><span class="jtable-column-header-text">Kilos</span></div></th><th class="jtable-column-header" style="width:6%;"><div class="jtable-column-header-container"><span class="jtable-column-header-text">Sacos</span></div></th><th class="jtable-column-header" style="width:26%;"><div class="jtable-column-header-container"><span class="jtable-column-header-text">Bodega</span></div></th><th class="jtable-column-header" style="width:26%;"><div class="jtable-column-header-container"><span class="jtable-column-header-text">Cliente</span></div></th><th class="jtable-column-header" style="width:6%;"><div class="jtable-column-header-container"><span class="jtable-column-header-text">Lote</span></div></th></tr></thead><tbody>';
				$g=0;
				while ($rowp = mysqli_fetch_array($rspxd)){
				   	if ($g%2==0){ 
						$msg .= '<tr class="jtable-rowp-even">';
					} else {
						$msg .= '<tr class="jtable-rowp-evenf">';
					}
					$msg .= '<td align="right">' ;
					$msg .= $rowp['producto'];
					$msg .= '</td>';
					$msg .= '<td>' ;
					$msg .= $rowp['ton'];
					$msg .= '</td>';
					$msg .= '<td>' ;
					$msg .= $rowp['sacos'];
					$msg .= '</td>';
					$msg .= '<td>' ;
					$msg .= $rowp['bodega'];
					$msg .= '</td>';
					$msg .= '<td>' ;
					$msg .= $rowp['cliente'];
					$msg .= '</td>';
					$msg .= '<td>' ;
					$msg .= $rowp['lote'];
					$msg .= '</td></tr>';			
					$g++;
				}
			}
			$u++;
		}
		$msg .= '</tbody></table></div>';
		break;
		
	case "bodega":
		if ($pro==0 and $cli==0){
				$msg .= '<table class="jtable"><thead class="ppal"><tr>';
				$msg .= '<th class="jtable-column-header" style="width:23%;">';
				$msg .= '<div class="jtable-column-header-container">';
				$msg .= '<span class="jtable-column-header-text">Bodega</span>';
				$msg .= '</div></th><th class="jtable-column-header" style="width:21%;"><div class="jtable-column-header-container"><span class="jtable-column-header-text">' . $nombrebodega . '</span></div></th></tr></thead><tbody>';
				if ($tipom=="e"){
					if ($fechai != $fechaf){
						$where = "WHERE gc_despachos_producto.tipo='e' and gc_despachos.fecha between '" . $fechai . "' and '" . $fechaf . "'";
					}else{
						$where = "WHERE gc_despachos_producto.tipo='e' and gc_despachos.fecha = '" . $fechai . "'";				
					}					
					$spxd="SELECT gc_despachos_producto.id as id, gc_clientes.nombre as cliente, gc_productos.nombre as producto, gc_despachos_producto.cantidad_tm as ton, gc_despachos_producto.cantidad_sacos as sacos,  gc_despachos_producto.lote as lote, gc_bodegas.nombre as bodega, gc_despachos.fecha as fecha, gc_despachos.orden as orden, gc_despachos.remision as remision, gc_despachos.placas as placas, gc_despachos.tiquete as tiquete, gc_conductores.nombre as conductor, gc_transportadores.razon_social as transportador, gc_motonaves.nombre as moto from gc_despachos_producto left join gc_bodegas on gc_despachos_producto.bodega = gc_bodegas.Id left join gc_despachos on gc_despachos_producto.remisionid=gc_despachos.remision and gc_despachos_producto.tipo=gc_despachos.tipo left join gc_clientes on gc_despachos_producto.cliente=gc_clientes.nit left join gc_productos on gc_despachos_producto.producto=gc_productos.productosId left join gc_conductores on gc_despachos.conductor=gc_conductores.id left join gc_transportadores on gc_despachos.transportador = gc_transportadores.nit left join gc_motonaves on gc_despachos_producto.motonave=gc_motonaves.motonaveId " . $where  . " and gc_despachos_producto.bodega=" . $bod;
				}	
				if ($tipom=="s"){
					if ($fechai != $fechaf){
						$where = "WHERE gc_despachos_producto.tipo='s' and gc_despachos.fecha between '" . $fechai . "' and '" . $fechaf . "'";
					}else{
						$where = "WHERE gc_despachos_producto.tipo='s' and gc_despachos.fecha = '" . $fechai . "'";				
					}										
					$spxd="SELECT gc_despachos_producto.id as id, gc_clientes.nombre as cliente, gc_productos.nombre as producto, gc_despachos_producto.cantidad_tm as ton, gc_despachos_producto.cantidad_sacos as sacos,  gc_despachos_producto.lote as lote, gc_bodegas.nombre as bodega, gc_despachos.fecha as fecha, gc_despachos.orden as orden, gc_despachos.remision as remision, gc_despachos.placas as placas, gc_despachos.tiquete as tiquete, gc_conductores.nombre as conductor, gc_transportadores.razon_social as transportador, gc_motonaves.nombre as moto from gc_despachos_producto left join gc_bodegas on gc_despachos_producto.bodega = gc_bodegas.Id left join gc_despachos on gc_despachos_producto.remisionid=gc_despachos.remision and gc_despachos_producto.tipo=gc_despachos.tipo left join gc_clientes on gc_despachos_producto.cliente=gc_clientes.nit left join gc_productos on gc_despachos_producto.producto=gc_productos.productosId left join gc_conductores on gc_despachos.conductor=gc_conductores.id left join gc_transportadores on gc_despachos.transportador = gc_transportadores.nit left join gc_motonaves on gc_despachos_producto.motonave=gc_motonaves.motonaveId " . $where . " and gc_despachos_producto.bodega=" . $bod;
				}
				$rspxd=mysqli_query($objConexion,$spxd) or die('MySql Error' . mysqli_error($objConexion));
				$fspxd=mysqli_num_rows($rspxd);
				if ($fspxd>0) {
					$msg .= '<table class="jtable">';
					$msg .= '<thead>';
					$msg .= '<tr><th class="jtable-column-header" style="width:8%;"><div class="jtable-column-header-container"><span class="jtable-column-header-text">Fecha</span></div></th>';
					$msg .= '<th class="jtable-column-header" style="width:12%;">';
					$msg .= '<div class="jtable-column-header-container">';
					$msg .= '<span class="jtable-column-header-text">Producto</span></div></th><th class="jtable-column-header" style="width:6%;"><div class="jtable-column-header-container"><span class="jtable-column-header-text">Kilos</span></div></th><th class="jtable-column-header" style="width:6%;"><div class="jtable-column-header-container"><span class="jtable-column-header-text">Sacos</span></div></th><th class="jtable-column-header" style="width:12%;"><div class="jtable-column-header-container"><span class="jtable-column-header-text">Cliente</span></div></th><th class="jtable-column-header" style="width:6%;"><div class="jtable-column-header-container"><span class="jtable-column-header-text">Remision</span></div></th><th class="jtable-column-header" style="width:6%;"><div class="jtable-column-header-container"><span class="jtable-column-header-text">Lote</span></div></th><th class="jtable-column-header" style="width:6%;"><div class="jtable-column-header-container"><span class="jtable-column-header-text">Orden</span></div></th><th class="jtable-column-header" style="width:6%;"><div class="jtable-column-header-container"><span class="jtable-column-header-text">Tiquete</span></div></th><th class="jtable-column-header" style="width:6%;"><div class="jtable-column-header-container"><span class="jtable-column-header-text">Placas</span></div></th><th class="jtable-column-header" style="width:11%;"><div class="jtable-column-header-container"><span class="jtable-column-header-text">Conductor</span></div></th><th class="jtable-column-header" style="width:11%;"><div class="jtable-column-header-container"><span class="jtable-column-header-text">Transportadora</span></div></th><th class="jtable-column-header" style="width:10%;"><div class="jtable-column-header-container"><span class="jtable-column-header-text">Motonave</span></div></th></tr></thead><tbody>';
					$g=0;
					$tton=0;
					$tsac=0;				
					while ($rowp=mysqli_fetch_array($rspxd)){
					   	if ($g%2==0){ 
							$msg .= '<tr class="jtable-rowp-even">';
						} else {
							$msg .= '<tr class="jtable-rowp-evenf">';
						}
						$msg .= '<td>' ;
						$msg .= $rowp['fecha'];
						$msg .= '</td>';					
						$msg .= '<td>' ;
						$msg .= $rowp['producto'];
						$msg .= '</td>';
						$msg .= '<td align="right">' ;
						$msg .= number_format($rowp['ton'],0,"",",");
						$msg .= '</td>';
						$msg .= '<td align="right">' ;
						$msg .= number_format($rowp['sacos'],0,"",",");
						$msg .= '</td>';
						$msg .= '<td>' ;
						$msg .= $rowp['cliente'];
						$msg .= '</td>';
						$msg .= '<td>' ;
						$msg .= $rowp['remision'];
						$msg .= '</td>' ;						
						$msg .= '<td>' ;
						$msg .= $rowp['lote'];
						$msg .= '</td>' ;
						$msg .= '<td>' ;
						$msg .= $rowp['orden'];
						$msg .= '</td>' ;
						$msg .= '<td>' ;
						$msg .= $rowp['tiquete'];
						$msg .= '</td>';						
						$msg .= '<td>' ;
						$msg .= $rowp['placas'];
						$msg .= '</td>';						
						$msg .= '<td>' ;
						$msg .= $rowp['conductor'];
						$msg .= '</td>';
						$msg .= '<td>' ;
						$msg .= $rowp['transportador'];
						$msg .= '</td>';			
						$msg .= '<td>' ;
						$msg .= $rowp['moto'];
						$msg .= '</td></tr>';			
						$tton = $tton + $rowp['ton'];
						$tsac = $tsac + $rowp['sacos'];
						$g++;					
					}
					$msg .= '</tbody>';
					$msg .= '<tr><td colspan=2>Totales</td><td align="right">';
					$msg .= number_format($tton,0,"",",");
					$msg .= '</td><td align="right">';
					$msg .= number_format($tsac,0,"",",");
					$msg .= '</td></tr>';
					$msg .= '</table></div>';
				}
		}
		if ($pro!=0 and $cli==0){
				$msg .= '<table class="jtable"><thead class="ppal"><tr>';
				$msg .= '<th class="jtable-column-header" style="width:10%;">';
				$msg .= '<div class="jtable-column-header-container">';
				$msg .= '<span class="jtable-column-header-text">Producto</span>';
				$msg .= '</div></th><th class="jtable-column-header" style="width:40%;"><div class="jtable-column-header-container"><span class="jtable-column-header-text">' . $nombreproducto . '</span></div></th><th class="jtable-column-header" style="width:10%;"><div class="jtable-column-header-container"><span class="jtable-column-header-text">Bodega</span></div></th><th class="jtable-column-header" style="width:40%;"><div class="jtable-column-header-container"><span class="jtable-column-header-text">' . $nombrebodega . '</span></div></th></tr></thead><tbody>';						
				if ($tipom=="e"){
					if ($fechai != $fechaf){
						$where = "WHERE gc_despachos_producto.tipo='e' and gc_despachos.fecha between '" . $fechai . "' and '" . $fechaf . "'";
					}else{
						$where = "WHERE gc_despachos_producto.tipo='e' and gc_despachos.fecha = '" . $fechai . "'";				
					}										
					$spxd="SELECT gc_despachos_producto.id as id, gc_clientes.nombre as cliente, gc_productos.nombre as producto, gc_despachos_producto.cantidad_tm as ton, gc_despachos_producto.cantidad_sacos as sacos,  gc_despachos_producto.lote as lote, gc_bodegas.nombre as bodega, gc_despachos.fecha as fecha, gc_despachos.orden as orden, gc_despachos.remision as remision, gc_despachos.placas as placas, gc_despachos.tiquete as tiquete, gc_conductores.nombre as conductor, gc_transportadores.razon_social as transportador, gc_motonaves.nombre as moto from gc_despachos_producto left join gc_bodegas on gc_despachos_producto.bodega = gc_bodegas.Id left join gc_despachos on gc_despachos_producto.remisionid=gc_despachos.remision and gc_despachos_producto.tipo=gc_despachos.tipo left join gc_clientes on gc_despachos_producto.cliente=gc_clientes.nit left join gc_productos on gc_despachos_producto.producto=gc_productos.productosId left join gc_conductores on gc_despachos.conductor=gc_conductores.id left join gc_transportadores on gc_despachos.transportador = gc_transportadores.nit left join gc_motonaves on gc_despachos_producto.motonave=gc_motonaves.motonaveId " . $where . " and gc_despachos_producto.bodega=" . $bod . " and gc_despachos_producto.producto=" . $pro;
				}	
				if ($tipom=="s"){
					if ($fechai != $fechaf){
						$where = "WHERE gc_despachos_producto.tipo='s' and gc_despachos.fecha between '" . $fechai . "' and '" . $fechaf . "'";
					}else{
						$where = "WHERE gc_despachos_producto.tipo='s' and gc_despachos.fecha = '" . $fechai . "'";				
					}										
					$spxd="SELECT gc_despachos_producto.id as id, gc_clientes.nombre as cliente, gc_productos.nombre as producto, gc_despachos_producto.cantidad_tm as ton, gc_despachos_producto.cantidad_sacos as sacos,  gc_despachos_producto.lote as lote, gc_bodegas.nombre as bodega, gc_despachos.fecha as fecha, gc_despachos.orden as orden, gc_despachos.remision as remision, gc_despachos.placas as placas, gc_despachos.tiquete as tiquete, gc_conductores.nombre as conductor, gc_transportadores.razon_social as transportador, gc_motonaves.nombre as moto from gc_despachos_producto left join gc_bodegas on gc_despachos_producto.bodega = gc_bodegas.Id left join gc_despachos on gc_despachos_producto.remisionid=gc_despachos.remision and gc_despachos_producto.tipo=gc_despachos.tipo left join gc_clientes on gc_despachos_producto.cliente=gc_clientes.nit left join gc_productos on gc_despachos_producto.producto=gc_productos.productosId left join gc_conductores on gc_despachos.conductor=gc_conductores.id left join gc_transportadores on gc_despachos.transportador = gc_transportadores.nit left join gc_motonaves on gc_despachos_producto.motonave=gc_motonaves.motonaveId " . $where ." and gc_despachos_producto.bodega=" . $bod . " and gc_despachos_producto.producto=" . $pro;
				}
				$rspxd=mysqli_query($objConexion,$spxd) or die('MySql Error' . mysqli_error($objConexion));
				$fspxd=mysqli_num_rows($rspxd);
				if ($fspxd>0) {
					$msg .= '<table class="jtable">';
					$msg .= '<thead>';
					$msg .= '<tr>';
					$msg .= '<th class="jtable-column-header" style="width:8%;">';
					$msg .= '<div class="jtable-column-header-container">';
					$msg .= '<span class="jtable-column-header-text">Fecha</span></div></th><th class="jtable-column-header" style="width:6%;"><div class="jtable-column-header-container"><span class="jtable-column-header-text">Kilos</span></div></th><th class="jtable-column-header" style="width:6%;"><div class="jtable-column-header-container"><span class="jtable-column-header-text">Sacos</span></div></th><th class="jtable-column-header" style="width:19%;"><div class="jtable-column-header-container"><span class="jtable-column-header-text">Cliente</span></div></th><th class="jtable-column-header" style="width:6%;"><div class="jtable-column-header-container"><span class="jtable-column-header-text">Remision</span></div></th><th class="jtable-column-header" style="width:6%;"><div class="jtable-column-header-container"><span class="jtable-column-header-text">Lote</span></div></th><th class="jtable-column-header" style="width:6%;"><div class="jtable-column-header-container"><span class="jtable-column-header-text">Orden</span></div></th><th class="jtable-column-header" style="width:6%;"><div class="jtable-column-header-container"><span class="jtable-column-header-text">Tiquete</span></div></th><th class="jtable-column-header" style="width:6%;"><div class="jtable-column-header-container"><span class="jtable-column-header-text">Placas</span></div></th><th class="jtable-column-header" style="width:12%;"><div class="jtable-column-header-container"><span class="jtable-column-header-text">Conductor</span></div></th><th class="jtable-column-header" style="width:12%;"><div class="jtable-column-header-container"><span class="jtable-column-header-text">Transportadora</span></div></th><th class="jtable-column-header" style="width:11%;"><div class="jtable-column-header-container"><span class="jtable-column-header-text">Motonave</span></div></th></tr></thead><tbody>';
					$g=0;				
					$tton=0;
					$tsac=0;				
					while ($rowp=mysqli_fetch_array($rspxd)){
					   	if ($g%2==0){ 
							$msg .= '<tr class="jtable-rowp-even">';
						} else {
							$msg .= '<tr class="jtable-rowp-evenf">';
						}
						$msg .= '<td>' ;
						$msg .= $rowp['fecha'];
						$msg .= '</td>';
						$msg .= '<td align="right">' ;
						$msg .= number_format($rowp['ton'],0,"",",");
						$msg .= '</td>';
						$msg .= '<td align="right">' ;
						$msg .= number_format($rowp['sacos'],0,"",",");
						$msg .= '</td>';
						$msg .= '<td>' ;
						$msg .= $rowp['cliente'];
						$msg .= '</td>';
						$msg .= '<td>' ;
						$msg .= $rowp['remision'];
						$msg .= '</td>' ;						
						$msg .= '<td>' ;
						$msg .= $rowp['lote'];
						$msg .= '</td>' ;
						$msg .= '<td>' ;
						$msg .= $rowp['orden'];
						$msg .= '</td>' ;
						$msg .= '<td>' ;
						$msg .= $rowp['tiquete'];
						$msg .= '</td>';						
						$msg .= '<td>' ;
						$msg .= $rowp['placas'];
						$msg .= '</td>';						
						$msg .= '<td>' ;
						$msg .= $rowp['conductor'];
						$msg .= '</td>';
						$msg .= '<td>' ;
						$msg .= $rowp['transportador'];
						$msg .= '</td>';			
						$msg .= '<td>' ;
						$msg .= $rowp['moto'];
						$msg .= '</td></tr>';			
						$tton = $tton + $rowp['ton'];
						$tsac = $tsac + $rowp['sacos'];
						$g++;					
					}
					$msg .= '</tbody>';
					$msg .= '<tr><td>Totales</td><td align="right">';
					$msg .= number_format($tton,0,"",",");
					$msg .= '</td><td align="right">';
					$msg .= number_format($tsac,0,"",",");
					$msg .= '</td></tr>';
					$msg .= '</table></div>';

				}
		}
		if ($pro==0 and $cli!=0){
				$msg .= '<table class="jtable"><thead class="ppal"><tr>';
				$msg .= '<th class="jtable-column-header" style="width:10%;">';
				$msg .= '<div class="jtable-column-header-container">';
				$msg .= '<span class="jtable-column-header-text">Cliente</span>';
				$msg .= '</div></th><th class="jtable-column-header" style="width:40%;"><div class="jtable-column-header-container"><span class="jtable-column-header-text">' . $nombrecliente . '</span></div></th><th class="jtable-column-header" style="width:10%;"><div class="jtable-column-header-container"><span class="jtable-column-header-text">Bodega</span></div></th><th class="jtable-column-header" style="width:40%;"><div class="jtable-column-header-container"><span class="jtable-column-header-text">' . $nombrebodega . '</span></div></th></tr></thead><tbody>';						
				if ($tipom=="e"){
					if ($fechai != $fechaf){
						$where = "WHERE gc_despachos_producto.tipo='e' and gc_despachos.fecha between '" . $fechai . "' and '" . $fechaf . "'";
					}else{
						$where = "WHERE gc_despachos_producto.tipo='e' and gc_despachos.fecha = '" . $fechai . "'";				
					}										
					$spxd="SELECT gc_despachos_producto.id as id, gc_clientes.nombre as cliente, gc_productos.nombre as producto, gc_despachos_producto.cantidad_tm as ton, gc_despachos_producto.cantidad_sacos as sacos,  gc_despachos_producto.lote as lote, gc_bodegas.nombre as bodega, gc_despachos.fecha as fecha, gc_despachos.orden as orden, gc_despachos.remision as remision, gc_despachos.placas as placas, gc_despachos.tiquete as tiquete, gc_conductores.nombre as conductor, gc_transportadores.razon_social as transportador, gc_motonaves.nombre as moto from gc_despachos_producto left join gc_bodegas on gc_despachos_producto.bodega = gc_bodegas.Id left join gc_despachos on gc_despachos_producto.remisionid=gc_despachos.remision and gc_despachos_producto.tipo=gc_despachos.tipo left join gc_clientes on gc_despachos_producto.cliente=gc_clientes.nit left join gc_productos on gc_despachos_producto.producto=gc_productos.productosId left join gc_conductores on gc_despachos.conductor=gc_conductores.id left join gc_transportadores on gc_despachos.transportador = gc_transportadores.nit left join gc_motonaves on gc_despachos_producto.motonave=gc_motonaves.motonaveId " . $where . " and gc_despachos_producto.bodega=" . $bod . " and gc_despachos_producto.cliente=" . $cli;
				}	
				if ($tipom=="s"){
					if ($fechai != $fechaf){
						$where = "WHERE gc_despachos_producto.tipo='s' and gc_despachos.fecha between '" . $fechai . "' and '" . $fechaf . "'";
					}else{
						$where = "WHERE gc_despachos_producto.tipo='s' and gc_despachos.fecha = '" . $fechai . "'";				
					}										
					$spxd="SELECT gc_despachos_producto.id as id, gc_clientes.nombre as cliente, gc_productos.nombre as producto, gc_despachos_producto.cantidad_tm as ton, gc_despachos_producto.cantidad_sacos as sacos,  gc_despachos_producto.lote as lote, gc_bodegas.nombre as bodega, gc_despachos.fecha as fecha, gc_despachos.orden as orden, gc_despachos.remision as remision, gc_despachos.placas as placas, gc_despachos.tiquete as tiquete, gc_conductores.nombre as conductor, gc_transportadores.razon_social as transportador, gc_motonaves.nombre as moto from gc_despachos_producto left join gc_bodegas on gc_despachos_producto.bodega = gc_bodegas.Id left join gc_despachos on gc_despachos_producto.remisionid=gc_despachos.remision and gc_despachos_producto.tipo=gc_despachos.tipo left join gc_clientes on gc_despachos_producto.cliente=gc_clientes.nit left join gc_productos on gc_despachos_producto.producto=gc_productos.productosId left join gc_conductores on gc_despachos.conductor=gc_conductores.id left join gc_transportadores on gc_despachos.transportador = gc_transportadores.nit left join gc_motonaves on gc_despachos_producto.motonave=gc_motonaves.motonaveId " . $where . " and gc_despachos_producto.bodega=" . $bod . " and gc_despachos_producto.cliente=" . $cli;
				}
				$rspxd=mysqli_query($objConexion,$spxd) or die('MySql Error' . mysqli_error($objConexion));
				$fspxd=mysqli_num_rows($rspxd);
				if ($fspxd>0) {
					$msg .= '<table class="jtable">';
					$msg .= '<thead>';
					$msg .= '<tr>';
					$msg .= '<th class="jtable-column-header" style="width:8%;">';
					$msg .= '<div class="jtable-column-header-container">';
					$msg .= '<span class="jtable-column-header-text">Fecha</span></div></th><th class="jtable-column-header" style="width:19%;"><div class="jtable-column-header-container"><span class="jtable-column-header-text">Producto</span></div></th><th class="jtable-column-header" style="width:6%;"><div class="jtable-column-header-container"><span class="jtable-column-header-text">Kilos</span></div></th><th class="jtable-column-header" style="width:6%;"><div class="jtable-column-header-container"><span class="jtable-column-header-text">Sacos</span></div></th><th class="jtable-column-header" style="width:6%;"><div class="jtable-column-header-container"><span class="jtable-column-header-text">Remision</span></div></th><th class="jtable-column-header" style="width:6%;"><div class="jtable-column-header-container"><span class="jtable-column-header-text">Lote</span></div></th><th class="jtable-column-header" style="width:6%;"><div class="jtable-column-header-container"><span class="jtable-column-header-text">Orden</span></div></th><th class="jtable-column-header" style="width:6%;"><div class="jtable-column-header-container"><span class="jtable-column-header-text">Tiquete</span></div></th><th class="jtable-column-header" style="width:6%;"><div class="jtable-column-header-container"><span class="jtable-column-header-text">Placas</span></div></th><th class="jtable-column-header" style="width:12%;"><div class="jtable-column-header-container"><span class="jtable-column-header-text">Conductor</span></div></th><th class="jtable-column-header" style="width:12%;"><div class="jtable-column-header-container"><span class="jtable-column-header-text">Transportadora</span></div></th><th class="jtable-column-header" style="width:11%;"><div class="jtable-column-header-container"><span class="jtable-column-header-text">Motonave</span></div></th></tr></thead><tbody>';
					$g=0;				
					$tton=0;
					$tsac=0;				
					while ($rowp=mysqli_fetch_array($rspxd)){
					   	if ($g%2==0){ 
							$msg .= '<tr class="jtable-rowp-even">';
						} else {
							$msg .= '<tr class="jtable-rowp-evenf">';
						}
						$msg .= '<td>' ;
						$msg .= $rowp['fecha'];
						$msg .= '</td>';
						$msg .= '<td>' ;
						$msg .= $rowp['producto'];
						$msg .= '</td>';						
						$msg .= '<td align="right">' ;
						$msg .= number_format($rowp['ton'],0,"",",");
						$msg .= '</td>';
						$msg .= '<td align="right">' ;
						$msg .= number_format($rowp['sacos'],0,"",",");
						$msg .= '</td>';
						$msg .= '<td>' ;
						$msg .= $rowp['remision'];
						$msg .= '</td>' ;						
						$msg .= '<td>' ;
						$msg .= $rowp['lote'];
						$msg .= '</td>' ;
						$msg .= '<td>' ;
						$msg .= $rowp['orden'];
						$msg .= '</td>' ;
						$msg .= '<td>' ;
						$msg .= $rowp['tiquete'];
						$msg .= '</td>';						
						$msg .= '<td>' ;
						$msg .= $rowp['placas'];
						$msg .= '</td>';						
						$msg .= '<td>' ;
						$msg .= $rowp['conductor'];
						$msg .= '</td>';
						$msg .= '<td>' ;
						$msg .= $rowp['transportador'];
						$msg .= '</td>';			
						$msg .= '<td>' ;
						$msg .= $rowp['moto'];
						$msg .= '</td></tr>';			
						$tton = $tton + $rowp['ton'];
						$tsac = $tsac + $rowp['sacos'];
						$g++;					
					}
					$msg .= '</tbody>';
					$msg .= '<tr><td colspan=2>Totales</td><td align="right">';
					$msg .= number_format($tton,0,"",",");
					$msg .= '</td><td align="right">';
					$msg .= number_format($tsac,0,"",",");
					$msg .= '</td></tr>';
					$msg .= '</table></div>';
				}
		}
		break;
	case "cliente":
		if ($pro==0 and $bod==0){
				$msg .= '<table class="jtable"><thead class="ppal"><tr>';
				$msg .= '<th class="jtable-column-header" style="width:23%;">';
				$msg .= '<div class="jtable-column-header-container">';
				$msg .= '<span class="jtable-column-header-text">Cliente</span>';
				$msg .= '</div></th><th class="jtable-column-header" style="width:21%;"><div class="jtable-column-header-container"><span class="jtable-column-header-text">' . $nombrecliente . '</span></div></th></tr></thead><tbody>';					
				if ($tipom=="e"){
					if ($fechai != $fechaf){
						$where = "WHERE gc_despachos_producto.tipo='e' and gc_despachos.fecha between '" . $fechai . "' and '" . $fechaf . "'";
					}else{
						$where = "WHERE gc_despachos_producto.tipo='e' and gc_despachos.fecha = '" . $fechai . "'";				
					}										
					$spxd="SELECT gc_despachos_producto.id as id, gc_productos.nombre as producto, gc_clientes.nombre as cliente, gc_despachos_producto.cantidad_tm as ton, gc_despachos_producto.cantidad_sacos as sacos,  gc_despachos_producto.lote as lote, gc_bodegas.nombre as bodega, gc_despachos.fecha as fecha, gc_despachos.orden as orden, gc_despachos.remision as remision, gc_despachos.placas as placas, gc_despachos.tiquete as tiquete, gc_conductores.nombre as conductor, gc_transportadores.razon_social as transportador, gc_motonaves.nombre as moto from gc_despachos_producto left join gc_productos on gc_despachos_producto.producto = gc_productos.productosId left join gc_bodegas on gc_despachos_producto.bodega = gc_bodegas.Id left join gc_despachos on gc_despachos_producto.remisionid=gc_despachos.remision and gc_despachos_producto.tipo=gc_despachos.tipo left join gc_clientes on gc_despachos_producto.cliente=gc_clientes.nit left join gc_conductores on gc_despachos.conductor=gc_conductores.id left join gc_transportadores on gc_despachos.transportador = gc_transportadores.nit left join gc_motonaves on gc_despachos_producto.motonave=gc_motonaves.motonaveId " . $where . " and gc_despachos_producto.cliente=" . $cli;
				}	
				if ($tipom=="s"){
					if ($fechai != $fechaf){
						$where = "WHERE gc_despachos_producto.tipo='s' and gc_despachos.fecha between '" . $fechai . "' and '" . $fechaf . "'";
					}else{
						$where = "WHERE gc_despachos_producto.tipo='s' and gc_despachos.fecha = '" . $fechai . "'";				
					}										
					$spxd="SELECT gc_despachos_producto.id as id, gc_productos.nombre as producto, gc_clientes.nombre as cliente, gc_despachos_producto.cantidad_tm as ton, gc_despachos_producto.cantidad_sacos as sacos,  gc_despachos_producto.lote as lote, gc_bodegas.nombre as bodega, gc_despachos.fecha as fecha, gc_despachos.orden as orden, gc_despachos.remision as remision, gc_despachos.placas as placas, gc_despachos.tiquete as tiquete, gc_conductores.nombre as conductor, gc_transportadores.razon_social as transportador, gc_motonaves.nombre as moto from gc_despachos_producto left join gc_productos on gc_despachos_producto.producto = gc_productos.productosId left join gc_bodegas on gc_despachos_producto.bodega = gc_bodegas.Id left join gc_despachos on gc_despachos_producto.remisionid=gc_despachos.remision and gc_despachos_producto.tipo=gc_despachos.tipo left join gc_clientes on gc_despachos_producto.cliente=gc_clientes.nit left join gc_conductores on gc_despachos.conductor=gc_conductores.id left join gc_transportadores on gc_despachos.transportador = gc_transportadores.nit left join gc_motonaves on gc_despachos_producto.motonave=gc_motonaves.motonaveId " . $where . " and gc_despachos_producto.cliente=" . $cli;
				}
				$rspxd=mysqli_query($objConexion,$spxd) or die('MySql Error1' . mysqli_error($objConexion));
				$fspxd=mysqli_num_rows($rspxd);
				if ($fspxd>0) {
					$msg .= '<table class="jtable">';
					$msg .= '<thead>';
					$msg .= '<tr><th class="jtable-column-header" style="width:8%;"><div class="jtable-column-header-container"><span class="jtable-column-header-text">Fecha</span></div></th>';
					$msg .= '<th class="jtable-column-header" style="width:12%;">';
					$msg .= '<div class="jtable-column-header-container">';
					$msg .= '<span class="jtable-column-header-text">Producto</span></div></th><th class="jtable-column-header" style="width:6%;"><div class="jtable-column-header-container"><span class="jtable-column-header-text">Kilos</span></div></th><th class="jtable-column-header" style="width:6%;"><div class="jtable-column-header-container"><span class="jtable-column-header-text">Sacos</span></div></th><th class="jtable-column-header" style="width:11%;"><div class="jtable-column-header-container"><span class="jtable-column-header-text">Bodega</span></div></th><th class="jtable-column-header" style="width:11%;"><div class="jtable-column-header-container"><span class="jtable-column-header-text">Motonave</span></div></th><th class="jtable-column-header" style="width:6%;"><div class="jtable-column-header-container"><span class="jtable-column-header-text">Remision</span></div></th><th class="jtable-column-header" style="width:6%;"><div class="jtable-column-header-container"><span class="jtable-column-header-text">Lote</span></div></th><th class="jtable-column-header" style="width:6%;"><div class="jtable-column-header-container"><span class="jtable-column-header-text">Orden</span></div></th><th class="jtable-column-header" style="width:6%;"><div class="jtable-column-header-container"><span class="jtable-column-header-text">Tiquete</span></div></th><th class="jtable-column-header" style="width:6%;"><div class="jtable-column-header-container"><span class="jtable-column-header-text">Placas</span></div></th><th class="jtable-column-header" style="width:10%;"><div class="jtable-column-header-container"><span class="jtable-column-header-text">Conductor</span></div></th><th class="jtable-column-header" style="width:12%;"><div class="jtable-column-header-container"><span class="jtable-column-header-text">Transportadora</span></div></th></tr></thead><tbody>';
					$g=0;				
					$tton=0;
					$tsac=0;				
					while ($rowp=mysqli_fetch_array($rspxd)){
					   	if ($g%2==0){ 
							$msg .= '<tr class="jtable-rowp-even">';
						} else {
							$msg .= '<tr class="jtable-rowp-evenf">';
						}
						$msg .= '<td>' ;
						$msg .= $rowp['fecha'];
						$msg .= '</td>';					
						$msg .= '<td>' ;
						$msg .= $rowp['producto'];
						$msg .= '</td>';
						$msg .= '<td align="right">' ;
						$msg .= number_format($rowp['ton'],0,"",",");
						$msg .= '</td>';
						$msg .= '<td align="right">' ;
						$msg .= number_format($rowp['sacos'],0,"",",");
						$msg .= '</td>';
						$msg .= '<td>' ;
						$msg .= $rowp['bodega'];
						$msg .= '</td>';
						$msg .= '<td>' ;
						$msg .= $rowp['moto'];
						$msg .= '</td>';
						$msg .= '<td>' ;
						$msg .= $rowp['remision'];
						$msg .= '</td>';						
						$msg .= '<td>' ;
						$msg .= $rowp['lote'];
						$msg .= '</td>';
						$msg .= '<td>' ;
						$msg .= $rowp['orden'];
						$msg .= '</td>';
						$msg .= '<td>' ;
						$msg .= $rowp['tiquete'];
						$msg .= '</td>';						
						$msg .= '<td>' ;
						$msg .= $rowp['placas'];
						$msg .= '</td>';						
						$msg .= '<td>' ;
						$msg .= $rowp['conductor'];
						$msg .= '</td>';
						$msg .= '<td>' ;
						$msg .= $rowp['transportador'];
						$msg .= '</td></tr>';			
						$tton = $tton + $rowp['ton'];
						$tsac = $tsac + $rowp['sacos'];
						$g++;					
					}
					$msg .= '</tbody>';
					$msg .= '<tr><td colspan=2>Totales</td><td align="right">';
					$msg .= number_format($tton,0,"",",");
					$msg .= '</td><td align="right">';
					$msg .= number_format($tsac,0,"",",");
					$msg .= '</td></tr>';
					$msg .= '</table></div>';
				}
		}
		if ($pro!=0 and $bod==0){
				$msg .= '<table class="jtable"><thead class="ppal"><tr>';
				$msg .= '<th class="jtable-column-header" style="width:10%;">';
				$msg .= '<div class="jtable-column-header-container">';
				$msg .= '<span class="jtable-column-header-text">Producto</span>';
				$msg .= '</div></th><th class="jtable-column-header" style="width:40%;"><div class="jtable-column-header-container"><span class="jtable-column-header-text">' . $nombreproducto . '</span></div></th><th class="jtable-column-header" style="width:10%;"><div class="jtable-column-header-container"><span class="jtable-column-header-text">Cliente</span></div></th><th class="jtable-column-header" style="width:40%;"><div class="jtable-column-header-container"><span class="jtable-column-header-text">' . $nombrecliente . '</span></div></th></tr></thead><tbody>';						
				if ($tipom=="e"){
					if ($fechai != $fechaf){
						$where = "WHERE gc_despachos_producto.tipo='e' and gc_despachos.fecha between '" . $fechai . "' and '" . $fechaf . "'";
					}else{
						$where = "WHERE gc_despachos_producto.tipo='e' and gc_despachos.fecha = '" . $fechai . "'";				
					}										
					$spxd="SELECT gc_despachos_producto.id as id, gc_productos.nombre as producto, gc_clientes.nombre as cliente, gc_despachos_producto.cantidad_tm as ton, gc_despachos_producto.cantidad_sacos as sacos,  gc_despachos_producto.lote as lote, gc_bodegas.nombre as bodega, gc_despachos.fecha as fecha, gc_despachos.orden as orden, gc_despachos.remision as remision, gc_despachos.placas as placas, gc_despachos.tiquete as tiquete, gc_conductores.nombre as conductor, gc_transportadores.razon_social as transportador, gc_motonaves.nombre as moto from gc_despachos_producto left join gc_productos on gc_despachos_producto.producto = gc_productos.productosId left join gc_bodegas on gc_despachos_producto.bodega = gc_bodegas.Id left join gc_despachos on gc_despachos_producto.remisionid=gc_despachos.remision and gc_despachos_producto.tipo=gc_despachos.tipo left join gc_clientes on gc_despachos_producto.cliente=gc_clientes.nit left join gc_conductores on gc_despachos.conductor=gc_conductores.id left join gc_transportadores on gc_despachos.transportador = gc_transportadores.nit left join gc_motonaves on gc_despachos_producto.motonave=gc_motonaves.motonaveId " .$where ." and gc_despachos_producto.cliente=" . $cli . " and gc_despachos_producto.producto=" . $pro;
				}
				if ($tipom=="s"){
					if ($fechai != $fechaf){
						$where = "WHERE gc_despachos_producto.tipo='s' and gc_despachos.fecha between '" . $fechai . "' and '" . $fechaf . "'";
					}else{
						$where = "WHERE gc_despachos_producto.tipo='s' and gc_despachos.fecha = '" . $fechai . "'";				
					}										
					$spxd="SELECT gc_despachos_producto.id as id, gc_productos.nombre as producto, gc_clientes.nombre as cliente, gc_despachos_producto.cantidad_tm as ton, gc_despachos_producto.cantidad_sacos as sacos,  gc_despachos_producto.lote as lote, gc_bodegas.nombre as bodega, gc_despachos.fecha as fecha, gc_despachos.orden as orden, gc_despachos.remision as remision, gc_despachos.placas as placas, gc_despachos.tiquete as tiquete, gc_conductores.nombre as conductor, gc_transportadores.razon_social as transportador, gc_motonaves.nombre as moto from gc_despachos_producto left join gc_productos on gc_despachos_producto.producto = gc_productos.productosId left join gc_bodegas on gc_despachos_producto.bodega = gc_bodegas.Id left join gc_despachos on gc_despachos_producto.remisionid=gc_despachos.remision and gc_despachos_producto.tipo=gc_despachos.tipo left join gc_clientes on gc_despachos_producto.cliente=gc_clientes.nit left join gc_conductores on gc_despachos.conductor=gc_conductores.id left join gc_transportadores on gc_despachos.transportador = gc_transportadores.nit left join gc_motonaves on gc_despachos_producto.motonave=gc_motonaves.motonaveId " . $where . " and gc_despachos_producto.cliente=" . $cli . " and gc_despachos_producto.producto=" . $pro;
				}
				$rspxd=mysqli_query($objConexion,$spxd) or die('MySql Error2' . mysqli_error($objConexion));
				$fspxd=mysqli_num_rows($rspxd);
				if ($fspxd>0) {
					$msg .= '<table class="jtable">';
					$msg .= '<thead>';
					$msg .= '<tr>';
					$msg .= '<th class="jtable-column-header" style="width:8%;">';
					$msg .= '<div class="jtable-column-header-container">';
					$msg .= '<span class="jtable-column-header-text">Fecha</span></div></th><th class="jtable-column-header" style="width:6%;"><div class="jtable-column-header-container"><span class="jtable-column-header-text">Kilos</span></div></th><th class="jtable-column-header" style="width:6%;"><div class="jtable-column-header-container"><span class="jtable-column-header-text">Sacos</span></div></th><th class="jtable-column-header" style="width:12%;"><div class="jtable-column-header-container"><span class="jtable-column-header-text">Bodega</span></div></th><th class="jtable-column-header" style="width:11%;"><div class="jtable-column-header-container"><span class="jtable-column-header-text">Motonave</span></div></th><th class="jtable-column-header" style="width:6%;"><div class="jtable-column-header-container"><span class="jtable-column-header-text">Remision</span></div></th><th class="jtable-column-header" style="width:6%;"><div class="jtable-column-header-container"><span class="jtable-column-header-text">Lote</span></div></th><th class="jtable-column-header" style="width:6%;"><div class="jtable-column-header-container"><span class="jtable-column-header-text">Orden</span></div></th><th class="jtable-column-header" style="width:6%;"><div class="jtable-column-header-container"><span class="jtable-column-header-text">Tiquete</span></div></th><th class="jtable-column-header" style="width:6%;"><div class="jtable-column-header-container"><span class="jtable-column-header-text">Placas</span></div></th><th class="jtable-column-header" style="width:11%;"><div class="jtable-column-header-container"><span class="jtable-column-header-text">Conductor</span></div></th><th class="jtable-column-header" style="width:18%;"><div class="jtable-column-header-container"><span class="jtable-column-header-text">Transportadora</span></div></th></tr></thead><tbody>';
					$g=0;				
					$tton=0;
					$tsac=0;				
					while ($rowp=mysqli_fetch_array($rspxd)){
					   	if ($g%2==0){ 
							$msg .= '<tr class="jtable-rowp-even">';
						} else {
							$msg .= '<tr class="jtable-rowp-evenf">';
						}
						$msg .= '<td>' ;
						$msg .= $rowp['fecha'];
						$msg .= '</td>';
						$msg .= '<td align="right">' ;
						$msg .= number_format($rowp['ton'],0,"",",");
						$msg .= '</td>';
						$msg .= '<td align="right">' ;
						$msg .= number_format($rowp['sacos'],0,"",",");
						$msg .= '</td>';
						$msg .= '<td>' ;
						$msg .= $rowp['bodega'];
						$msg .= '</td>';
						$msg .= '<td>' ;
						$msg .= $rowp['moto'];
						$msg .= '</td>';
						$msg .= '<td>' ;
						$msg .= $rowp['remision'];
						$msg .= '</td>' ;						
						$msg .= '<td>' ;
						$msg .= $rowp['lote'];
						$msg .= '</td>' ;
						$msg .= '<td>' ;
						$msg .= $rowp['orden'];
						$msg .= '</td>' ;
						$msg .= '<td>' ;
						$msg .= $rowp['tiquete'];
						$msg .= '</td>';						
						$msg .= '<td>' ;
						$msg .= $rowp['placas'];
						$msg .= '</td>';						
						$msg .= '<td>' ;
						$msg .= $rowp['conductor'];
						$msg .= '</td>';
						$msg .= '<td>' ;
						$msg .= $rowp['transportador'];
						$msg .= '</td></tr>';			
						$tton = $tton + $rowp['ton'];
						$tsac = $tsac + $rowp['sacos'];
						$g++;					
					}
					$msg .= '</tbody>';
					$msg .= '<tr><td>Totales</td><td align="right">';
					$msg .= number_format($tton,0,"",",");
					$msg .= '</td><td align="right">';
					$msg .= number_format($tsac,0,"",",");
					$msg .= '</td></tr>';
					$msg .= '</table></div>';
				}
		}
		if ($pro==0 and $bod!=0){
				$msg .= '<table class="jtable"><thead class="ppal"><tr>';
				$msg .= '<th class="jtable-column-header" style="width:10%;">';
				$msg .= '<div class="jtable-column-header-container">';
				$msg .= '<span class="jtable-column-header-text">Cliente</span>';
				$msg .= '</div></th><th class="jtable-column-header" style="width:40%;"><div class="jtable-column-header-container"><span class="jtable-column-header-text">' . $nombrecliente . '</span></div></th><th class="jtable-column-header" style="width:10%;"><div class="jtable-column-header-container"><span class="jtable-column-header-text">Bodega</span></div></th><th class="jtable-column-header" style="width:40%;"><div class="jtable-column-header-container"><span class="jtable-column-header-text">' . $nombrebodega . '</span></div></th></tr></thead><tbody>';						
				if ($tipom=="e"){
					if ($fechai != $fechaf){
						$where = "WHERE gc_despachos_producto.tipo='e' and gc_despachos.fecha between '" . $fechai . "' and '" . $fechaf . "'";
					}else{
						$where = "WHERE gc_despachos_producto.tipo='e' and gc_despachos.fecha = '" . $fechai . "'";				
					}										
					$spxd="SELECT gc_despachos_producto.id as id, gc_productos.nombre as producto, gc_clientes.nombre as cliente, gc_despachos_producto.cantidad_tm as ton, gc_despachos_producto.cantidad_sacos as sacos,  gc_despachos_producto.lote as lote, gc_bodegas.nombre as bodega, gc_despachos.fecha as fecha, gc_despachos.orden as orden, gc_despachos.remision as remision, gc_despachos.placas as placas, gc_despachos.tiquete as tiquete, gc_conductores.nombre as conductor, gc_transportadores.razon_social as transportador, gc_motonaves.nombre as moto from gc_despachos_producto left join gc_productos on gc_despachos_producto.producto = gc_productos.productosId left join gc_bodegas on gc_despachos_producto.bodega = gc_bodegas.Id left join gc_despachos on gc_despachos_producto.remisionid=gc_despachos.remision and gc_despachos_producto.tipo=gc_despachos.tipo left join gc_clientes on gc_despachos_producto.cliente=gc_clientes.nit left join gc_conductores on gc_despachos.conductor=gc_conductores.id left join gc_transportadores on gc_despachos.transportador = gc_transportadores.nit left join gc_motonaves on gc_despachos_producto.motonave=gc_motonaves.motonaveId " . $where . " and gc_despachos_producto.cliente=" . $cli . " and gc_despachos_producto.bodega=" . $bod;
				}	
				if ($tipom=="s"){
					if ($fechai != $fechaf){
						$where = "WHERE gc_despachos_producto.tipo='s' and gc_despachos.fecha between '" . $fechai . "' and '" . $fechaf . "'";
					}else{
						$where = "WHERE gc_despachos_producto.tipo='s' and gc_despachos.fecha = '" . $fechai . "'";				
					}										
					$spxd="SELECT gc_despachos_producto.id as id, gc_productos.nombre as producto, gc_clientes.nombre as cliente, gc_despachos_producto.cantidad_tm as ton, gc_despachos_producto.cantidad_sacos as sacos,  gc_despachos_producto.lote as lote, gc_bodegas.nombre as bodega, gc_despachos.fecha as fecha, gc_despachos.orden as orden, gc_despachos.remision as remision, gc_despachos.placas as placas, gc_despachos.tiquete as tiquete, gc_conductores.nombre as conductor, gc_transportadores.razon_social as transportador, gc_motonaves.nombre as moto from gc_despachos_producto left join gc_productos on gc_despachos_producto.producto = gc_productos.productosId left join gc_bodegas on gc_despachos_producto.bodega = gc_bodegas.Id left join gc_despachos on gc_despachos_producto.remisionid=gc_despachos.remision and gc_despachos_producto.tipo=gc_despachos.tipo left join gc_clientes on gc_despachos_producto.cliente=gc_clientes.nit left join gc_conductores on gc_despachos.conductor=gc_conductores.id left join gc_transportadores on gc_despachos.transportador = gc_transportadores.nit left join gc_motonaves on gc_despachos_producto.motonave=gc_motonaves.motonaveId " . $where . " and gc_despachos_producto.cliente=" . $cli . " and gc_despachos_producto.bodega=" . $bod;
				}
				$rspxd=mysqli_query($objConexion,$spxd) or die('MySql Error4' . mysqli_error($objConexion));
				$fspxd=mysqli_num_rows($rspxd);
				if ($fspxd>0) {
					$msg .= '<table class="jtable">';
					$msg .= '<thead>';
					$msg .= '<tr>';
					$msg .= '<th class="jtable-column-header" style="width:8%;">';
					$msg .= '<div class="jtable-column-header-container">';
					$msg .= '<span class="jtable-column-header-text">Fecha</span></div></th><th class="jtable-column-header" style="width:6%;"><div class="jtable-column-header-container"><span class="jtable-column-header-text">Kilos</span></div></th><th class="jtable-column-header" style="width:6%;"><div class="jtable-column-header-container"><span class="jtable-column-header-text">Sacos</span></div></th><th class="jtable-column-header" style="width:12%;"><div class="jtable-column-header-container"><span class="jtable-column-header-text">Bodega</span></div></th><th class="jtable-column-header" style="width:12%;"><div class="jtable-column-header-container"><span class="jtable-column-header-text">Motonave</span></div></th><th class="jtable-column-header" style="width:6%;"><div class="jtable-column-header-container"><span class="jtable-column-header-text">Remision</span></div></th><th class="jtable-column-header" style="width:6%;"><div class="jtable-column-header-container"><span class="jtable-column-header-text">Lote</span></div></th><th class="jtable-column-header" style="width:6%;"><div class="jtable-column-header-container"><span class="jtable-column-header-text">Orden</span></div></th><th class="jtable-column-header" style="width:6%;"><div class="jtable-column-header-container"><span class="jtable-column-header-text">Tiquete</span></div></th><th class="jtable-column-header" style="width:6%;"><div class="jtable-column-header-container"><span class="jtable-column-header-text">Placas</span></div></th><th class="jtable-column-header" style="width:14%;"><div class="jtable-column-header-container"><span class="jtable-column-header-text">Conductor</span></div></th><th class="jtable-column-header" style="width:18%;"><div class="jtable-column-header-container"><span class="jtable-column-header-text">Transportadora</span></div></th></tr></thead><tbody>';
					$g=0;				
					$tton=0;
					$tsac=0;				
					while ($rowp=mysqli_fetch_array($rspxd)){
					   	if ($g%2==0){ 
							$msg .= '<tr class="jtable-rowp-even">';
						} else {
							$msg .= '<tr class="jtable-rowp-evenf">';
						}
						$msg .= '<td>' ;
						$msg .= $rowp['fecha'];
						$msg .= '</td>';
						$msg .= '<td align="right">' ;
						$msg .= number_format($rowp['ton'],0,"",",");
						$msg .= '</td>';
						$msg .= '<td align="right">' ;
						$msg .= number_format($rowp['sacos'],0,"",",");
						$msg .= '</td>';
						$msg .= '<td>' ;
						$msg .= $rowp['bodega'];
						$msg .= '</td>';
						$msg .= '<td>' ;
						$msg .= $rowp['moto'];
						$msg .= '</td>';
						$msg .= '<td>' ;
						$msg .= $rowp['remision'];
						$msg .= '</td>' ;						
						$msg .= '<td>' ;
						$msg .= $rowp['lote'];
						$msg .= '</td>' ;
						$msg .= '<td>' ;
						$msg .= $rowp['orden'];
						$msg .= '</td>' ;
						$msg .= '<td>' ;
						$msg .= $rowp['tiquete'];
						$msg .= '</td>';						
						$msg .= '<td>' ;
						$msg .= $rowp['placas'];
						$msg .= '</td>';						
						$msg .= '<td>' ;
						$msg .= $rowp['conductor'];
						$msg .= '</td>';
						$msg .= '<td>' ;
						$msg .= $rowp['transportador'];
						$msg .= '</td></tr>';			
						$tton = $tton + $rowp['ton'];
						$tsac = $tsac + $rowp['sacos'];
						$g++;					
					}
					$msg .= '</tbody>';
					$msg .= '<tr><td>Totales</td><td align="right">';
					$msg .= number_format($tton,0,"",",");
					$msg .= '</td><td align="right">';
					$msg .= number_format($tsac,0,"",",");
					$msg .= '</td></tr>';
					$msg .= '</table></div>';
				}
		}
		if ($pro!=0 and $bod!=0){
				$msg .= '<table class="jtable"><thead class="ppal"><tr>';
				$msg .= '<th class="jtable-column-header" style="width:10%;">';
				$msg .= '<div class="jtable-column-header-container">';
				$msg .= '<span class="jtable-column-header-text">Producto</span>';
				$msg .= '</div></th><th class="jtable-column-header" style="width:24%;"><div class="jtable-column-header-container"><span class="jtable-column-header-text">' . $nombreproducto . '</span></div></th>';
				$msg .= '<th class="jtable-column-header" style="width:10%;">';
				$msg .= '<div class="jtable-column-header-container">';
				$msg .= '<span class="jtable-column-header-text">Bodega</span>';
				$msg .= '</div></th><th class="jtable-column-header" style="width:24%;"><div class="jtable-column-header-container"><span class="jtable-column-header-text">' . $nombrebodega . '</span></div></th><th class="jtable-column-header" style="width:10%;"><div class="jtable-column-header-container"><span class="jtable-column-header-text">Cliente</span></div></th><th class="jtable-column-header" style="width:23%;"><div class="jtable-column-header-container"><span class="jtable-column-header-text">' . $nombrecliente . '</span></div></th></tr></thead><tbody>';						
				if ($tipom=="e"){
					if ($fechai != $fechaf){
						$where = "WHERE gc_despachos_producto.tipo='e' and gc_despachos.fecha between '" . $fechai . "' and '" . $fechaf . "'";
					}else{
						$where = "WHERE gc_despachos_producto.tipo='e' and gc_despachos.fecha = '" . $fechai . "'";				
					}										
					$spxd="SELECT gc_despachos_producto.id as id, gc_productos.nombre as producto, gc_clientes.nombre as cliente, gc_despachos_producto.cantidad_tm as ton, gc_despachos_producto.cantidad_sacos as sacos,  gc_despachos_producto.lote as lote, gc_bodegas.nombre as bodega, gc_despachos.fecha as fecha, gc_despachos.orden as orden, gc_despachos.remision as remision, gc_despachos.placas as placas, gc_despachos.tiquete as tiquete, gc_conductores.nombre as conductor, gc_transportadores.razon_social as transportador, gc_motonaves.nombre as moto from gc_despachos_producto left join gc_productos on gc_despachos_producto.producto = gc_productos.productosId left join gc_bodegas on gc_despachos_producto.bodega = gc_bodegas.Id left join gc_despachos on gc_despachos_producto.remisionid=gc_despachos.remision and gc_despachos_producto.tipo=gc_despachos.tipo left join gc_clientes on gc_despachos_producto.cliente=gc_clientes.nit left join gc_conductores on gc_despachos.conductor=gc_conductores.id left join gc_transportadores on gc_despachos.transportador = gc_transportadores.nit left join gc_motonaves on gc_despachos_producto.motonave=gc_motonaves.motonaveId " . $where . " and gc_despachos_producto.cliente=" . $cli . " and gc_despachos_producto.bodega=" . $bod . " and gc_despachos_producto.producto=" . $pro; 
				}	
				if ($tipom=="s"){
					if ($fechai != $fechaf){
						$where = "WHERE gc_despachos_producto.tipo='s' and gc_despachos.fecha between '" . $fechai . "' and '" . $fechaf . "'";
					}else{
						$where = "WHERE gc_despachos_producto.tipo='s' and gc_despachos.fecha = '" . $fechai . "'";				
					}										
					$spxd="SELECT gc_despachos_producto.id as id, gc_productos.nombre as producto, gc_clientes.nombre as cliente, gc_despachos_producto.cantidad_tm as ton, gc_despachos_producto.cantidad_sacos as sacos,  gc_despachos_producto.lote as lote, gc_bodegas.nombre as bodega, gc_despachos.fecha as fecha, gc_despachos.orden as orden, gc_despachos.remision as remision, gc_despachos.placas as placas, gc_despachos.tiquete as tiquete, gc_conductores.nombre as conductor, gc_transportadores.razon_social as transportador, gc_motonaves.nombre as moto from gc_despachos_producto left join gc_productos on gc_despachos_producto.producto = gc_productos.productosId left join gc_bodegas on gc_despachos_producto.bodega = gc_bodegas.Id left join gc_despachos on gc_despachos_producto.remisionid=gc_despachos.remision and gc_despachos_producto.tipo=gc_despachos.tipo left join gc_clientes on gc_despachos_producto.cliente=gc_clientes.nit left join gc_conductores on gc_despachos.conductor=gc_conductores.id left join gc_transportadores on gc_despachos.transportador = gc_transportadores.nit left join gc_motonaves on gc_despachos_producto.motonave=gc_motonaves.motonaveId " . $where . " and gc_despachos_producto.cliente=" . $cli . " and gc_despachos_producto.bodega=" . $bod . " and gc_despachos_producto.producto=" . $pro;
				}
				$rspxd=mysqli_query($objConexion,$spxd) or die('MySql Error6' . mysqli_error($objConexion));
				$fspxd=mysqli_num_rows($rspxd);
				if ($fspxd>0) {
					$msg .= '<table class="jtable">';
					$msg .= '<thead>';
					$msg .= '<tr>';
					$msg .= '<th class="jtable-column-header" style="width:8%;">';
					$msg .= '<div class="jtable-column-header-container">';
					$msg .= '<span class="jtable-column-header-text">Fecha</span></div></th><th class="jtable-column-header" style="width:6%;"><div class="jtable-column-header-container"><span class="jtable-column-header-text">Kilos</span></div></th><th class="jtable-column-header" style="width:6%;"><div class="jtable-column-header-container"><span class="jtable-column-header-text">Sacos</span></div></th><th class="jtable-column-header" style="width:6%;"><div class="jtable-column-header-container"><span class="jtable-column-header-text">Remision</span></div></th><th class="jtable-column-header" style="width:6%;"><div class="jtable-column-header-container"><span class="jtable-column-header-text">Lote</span></div></th><th class="jtable-column-header" style="width:6%;"><div class="jtable-column-header-container"><span class="jtable-column-header-text">Orden</span></div></th><th class="jtable-column-header" style="width:6%;"><div class="jtable-column-header-container"><span class="jtable-column-header-text">Tiquete</span></div></th><th class="jtable-column-header" style="width:6%;"><div class="jtable-column-header-container"><span class="jtable-column-header-text">Placas</span></div></th><th class="jtable-column-header" style="width:22%;"><div class="jtable-column-header-container"><span class="jtable-column-header-text">Conductor</span></div></th><th class="jtable-column-header" style="width:22%;"><div class="jtable-column-header-container"><span class="jtable-column-header-text">Motonave</span></div></th><th class="jtable-column-header" style="width:22%;"><div class="jtable-column-header-container"><span class="jtable-column-header-text">Transportadora</span></div></th></tr></thead><tbody>';
					$g=0;				
					$tton=0;
					$tsac=0;				
					while ($rowp=mysqli_fetch_array($rspxd)){
					   	if ($g%2==0){ 
							$msg .= '<tr class="jtable-rowp-even">';
						} else {
							$msg .= '<tr class="jtable-rowp-evenf">';
						}
						$msg .= '<td>' ;
						$msg .= $rowp['fecha'];
						$msg .= '</td>';
						$msg .= '<td align="right">' ;
						$msg .= number_format($rowp['ton'],0,"",",");
						$msg .= '</td>';
						$msg .= '<td align="right">' ;
						$msg .= number_format($rowp['sacos'],0,"",",");
						$msg .= '</td>';
						$msg .= '<td>' ;
						$msg .= $rowp['remision'];
						$msg .= '</td>' ;						
						$msg .= '<td>' ;
						$msg .= $rowp['lote'];
						$msg .= '</td>' ;
						$msg .= '<td>' ;
						$msg .= $rowp['orden'];
						$msg .= '</td>' ;
						$msg .= '<td>' ;
						$msg .= $rowp['tiquete'];
						$msg .= '</td>';						
						$msg .= '<td>' ;
						$msg .= $rowp['placas'];
						$msg .= '</td>';						
						$msg .= '<td>' ;
						$msg .= $rowp['conductor'];
						$msg .= '</td>';
						$msg .= '<td>' ;
						$msg .= $rowp['moto'];
						$msg .= '</td>';
						$msg .= '<td>' ;
						$msg .= $rowp['transportador'];
						$msg .= '</td></tr>';			
						$tton = $tton + $rowp['ton'];
						$tsac = $tsac + $rowp['sacos'];
						$g++;					
					}
					$msg .= '</tbody>';
					$msg .= '<tr><td>Totales</td><td align="right">';
					$msg .= number_format($tton,0,"",",");
					$msg .= '</td><td align="right">';
					$msg .= number_format($tsac,0,"",",");
					$msg .= '</td></tr>';
					$msg .= '</table></div>';

				}
		}
	break;
	case "producto":
		if ($bod==0){
			$msg .= '<table class="jtable"><thead class="ppal"><tr>';
			$msg .= '<th class="jtable-column-header" style="width:23%;">';
			$msg .= '<div class="jtable-column-header-container">';
			$msg .= '<span class="jtable-column-header-text">Producto</span>';
			$msg .= '</div></th><th class="jtable-column-header" style="width:21%;"><div class="jtable-column-header-container"><span class="jtable-column-header-text">' . $nombreproducto . '</span></div></th></tr></thead><tbody>';					
			if ($tipom=="e"){
					if ($fechai != $fechaf){
						$where = "WHERE gc_despachos_producto.tipo='e' and gc_despachos.fecha between '" . $fechai . "' and '" . $fechaf . "'";
					}else{
						$where = "WHERE gc_despachos_producto.tipo='e' and gc_despachos.fecha = '" . $fechai . "'";				
					}									
				$spxd="SELECT gc_despachos_producto.id as id, gc_clientes.nombre as cliente, gc_despachos_producto.cantidad_tm as ton, gc_despachos_producto.cantidad_sacos as sacos,  gc_despachos_producto.lote as lote, gc_bodegas.nombre as bodega, gc_despachos.fecha as fecha, gc_despachos.orden as orden, gc_despachos.remision as remision, gc_despachos.placas as placas, gc_despachos.tiquete as tiquete, gc_conductores.nombre as conductor, gc_transportadores.razon_social as transportador, gc_motonaves.nombre as moto from gc_despachos_producto left join gc_bodegas on gc_despachos_producto.bodega = gc_bodegas.Id left join gc_despachos on gc_despachos_producto.remisionid=gc_despachos.remision and gc_despachos_producto.tipo=gc_despachos.tipo left join gc_clientes on gc_despachos_producto.cliente=gc_clientes.nit left join gc_conductores on gc_despachos.conductor=gc_conductores.id left join gc_transportadores on gc_despachos.transportador = gc_transportadores.nit left join gc_motonaves on gc_despachos_producto.motonave=gc_motonaves.motonaveId " . $where . " and gc_despachos_producto.producto=" . $pro;
			}	
			if ($tipom=="s"){
					if ($fechai != $fechaf){
						$where = "WHERE gc_despachos_producto.tipo='s' and gc_despachos.fecha between '" . $fechai . "' and '" . $fechaf . "'";
					}else{
						$where = "WHERE gc_despachos_producto.tipo='s' and gc_despachos.fecha = '" . $fechai . "'";				
					}									
				$spxd="SELECT gc_despachos_producto.id as id, gc_clientes.nombre as cliente, gc_despachos_producto.cantidad_tm as ton, gc_despachos_producto.cantidad_sacos as sacos,  gc_despachos_producto.lote as lote, gc_bodegas.nombre as bodega, gc_despachos.fecha as fecha, gc_despachos.orden as orden, gc_despachos.remision as remision, gc_despachos.placas as placas, gc_despachos.tiquete as tiquete, gc_conductores.nombre as conductor, gc_transportadores.razon_social as transportador, gc_motonaves.nombre as moto from gc_despachos_producto left join gc_bodegas on gc_despachos_producto.bodega = gc_bodegas.Id left join gc_despachos on gc_despachos_producto.remisionid=gc_despachos.remision and gc_despachos_producto.tipo=gc_despachos.tipo left join gc_clientes on gc_despachos_producto.cliente=gc_clientes.nit left join gc_conductores on gc_despachos.conductor=gc_conductores.id left join gc_transportadores on gc_despachos.transportador = gc_transportadores.nit left join gc_motonaves on gc_despachos_producto.motonave=gc_motonaves.motonaveId " . $where . " and gc_despachos_producto.producto=" . $pro;
			}

			$rspxd=mysqli_query($objConexion,$spxd) or die('MySql Error1' . mysqli_error($objConexion));
			$fspxd=mysqli_num_rows($rspxd);
			if ($fspxd>0) {
				$msg .= '<table class="jtable">';
				$msg .= '<thead>';
				$msg .= '<tr><th class="jtable-column-header" style="width:8%;"><div class="jtable-column-header-container"><span class="jtable-column-header-text">Fecha</span></div></th>';
				$msg .= '<th class="jtable-column-header" style="width:15%;">';
				$msg .= '<div class="jtable-column-header-container">';
				$msg .= '<span class="jtable-column-header-text">Cliente</span></div></th><th class="jtable-column-header" style="width:6%;"><div class="jtable-column-header-container"><span class="jtable-column-header-text">Kilos</span></div></th><th class="jtable-column-header" style="width:6%;"><div class="jtable-column-header-container"><span class="jtable-column-header-text">Sacos</span></div></th><th class="jtable-column-header" style="width:11%;"><div class="jtable-column-header-container"><span class="jtable-column-header-text">Bodega</span></div></th><th class="jtable-column-header" style="width:10%;"><div class="jtable-column-header-container"><span class="jtable-column-header-text">Motonave</span></div></th><th class="jtable-column-header" style="width:6%;"><div class="jtable-column-header-container"><span class="jtable-column-header-text">Remision</span></div></th><th class="jtable-column-header" style="width:6%;"><div class="jtable-column-header-container"><span class="jtable-column-header-text">Lote</span></div></th><th class="jtable-column-header" style="width:6%;"><div class="jtable-column-header-container"><span class="jtable-column-header-text">Orden</span></div></th><th class="jtable-column-header" style="width:6%;"><div class="jtable-column-header-container"><span class="jtable-column-header-text">Tiquete</span></div></th><th class="jtable-column-header" style="width:6%;"><div class="jtable-column-header-container"><span class="jtable-column-header-text">Placas</span></div></th><th class="jtable-column-header" style="width:10%;"><div class="jtable-column-header-container"><span class="jtable-column-header-text">Conductor</span></div></th><th class="jtable-column-header" style="width:10%;"><div class="jtable-column-header-container"><span class="jtable-column-header-text">Transportadora</span></div></th></tr></thead><tbody>';
				$g=0;				
				$tton=0;
				$tsac=0;				
				while ($rowp=mysqli_fetch_array($rspxd)){
				   	if ($g%2==0){ 
						$msg .= '<tr class="jtable-rowp-even">';
					} else {
						$msg .= '<tr class="jtable-rowp-evenf">';
					}
					$msg .= '<td>' ;
					$msg .= $rowp['fecha'];
					$msg .= '</td>';					
					$msg .= '<td>' ;
					$msg .= $rowp['cliente'];
					$msg .= '</td>';
					$msg .= '<td align="right">' ;
					$msg .= number_format($rowp['ton'],0,"",",");
					$msg .= '</td>';
					$msg .= '<td align="right">' ;
					$msg .= number_format($rowp['sacos'],0,"",",");
					$msg .= '</td>';
					$msg .= '<td>' ;
					$msg .= $rowp['bodega'];
					$msg .= '</td>';
					$msg .= '<td>' ;
					$msg .= $rowp['moto'];
					$msg .= '</td>';
					$msg .= '<td>' ;
					$msg .= $rowp['remision'];
					$msg .= '</td>' ;					
					$msg .= '<td>' ;
					$msg .= $rowp['lote'];
					$msg .= '</td>' ;
					$msg .= '<td>' ;
					$msg .= $rowp['orden'];
					$msg .= '</td>' ;
					$msg .= '<td>' ;
					$msg .= $rowp['tiquete'];
					$msg .= '</td>';						
					$msg .= '<td>' ;
					$msg .= $rowp['placas'];
					$msg .= '</td>';						
					$msg .= '<td>' ;
					$msg .= $rowp['conductor'];
					$msg .= '</td>';
					$msg .= '<td>' ;
					$msg .= $rowp['transportador'];
					$msg .= '</td></tr>';			
					$tton = $tton + $rowp['ton'];
					$tsac = $tsac + $rowp['sacos'];
					$g++;					
				}
					$msg .= '</tbody>';
					$msg .= '<tr><td colspan=2>Totales</td><td align="right">';
					$msg .= number_format($tton,0,"",",");
					$msg .= '</td><td align="right">';
					$msg .= number_format($tsac,0,"",",");
					$msg .= '</td></tr>';
					$msg .= '</table></div>';
				}
		} else {
			$msg .= '<table class="jtable"><thead class="ppal"><tr>';
			$msg .= '<th class="jtable-column-header" style="width:10%;">';
			$msg .= '<div class="jtable-column-header-container">';
			$msg .= '<span class="jtable-column-header-text">Producto</span>';
			$msg .= '</div></th><th class="jtable-column-header" style="width:24%;"><div class="jtable-column-header-container"><span class="jtable-column-header-text">' . $nombreproducto . '</span></div></th><th class="jtable-column-header" style="width:10%;"><div class="jtable-column-header-container"><span class="jtable-column-header-text">Bodega</span></div></th><th class="jtable-column-header" style="width:23%;"><div class="jtable-column-header-container"><span class="jtable-column-header-text">' . $nombrebodega . '</span></div></th><th class="jtable-column-header" style="width:10%;"><div class="jtable-column-header-container"><span class="jtable-column-header-text">Cliente</span></div></th><th class="jtable-column-header" style="width:23%;"><div class="jtable-column-header-container"><span class="jtable-column-header-text">' . $nombrecliente . '</span></div></th></tr></thead><tbody>';						
			if ($tipom=="e"){
					if ($fechai != $fechaf){
						$where = "WHERE gc_despachos_producto.tipo='e' and gc_despachos.fecha between '" . $fechai . "' and '" . $fechaf . "'";
					}else{
						$where = "WHERE gc_despachos_producto.tipo='e' and gc_despachos.fecha = '" . $fechai . "'";				
					}									
				$spxd="SELECT gc_despachos_producto.id as id, gc_clientes.nombre as cliente, gc_despachos_producto.cantidad_tm as ton, gc_despachos_producto.cantidad_sacos as sacos,  gc_despachos_producto.lote as lote, gc_bodegas.nombre as bodega, gc_despachos.fecha as fecha, gc_despachos.orden as orden, gc_despachos.remision as remision, gc_despachos.placas as placas, gc_despachos.tiquete as tiquete, gc_conductores.nombre as conductor, gc_transportadores.razon_social as transportador, gc_motonaves.nombre as moto from gc_despachos_producto left join gc_bodegas on gc_despachos_producto.bodega = gc_bodegas.Id left join gc_despachos on gc_despachos_producto.remisionid=gc_despachos.remision and gc_despachos_producto.tipo=gc_despachos.tipo left join gc_clientes on gc_despachos_producto.cliente=gc_clientes.nit left join gc_conductores on gc_despachos.conductor=gc_conductores.id left join gc_transportadores on gc_despachos.transportador = gc_transportadores.nit left join gc_motonaves on gc_despachos_producto.motonave=gc_motonaves.motonaveId " . $where . " and gc_despachos_producto.producto=" . $pro . " and gc_despachos_producto.bodega=" . $bod;
			}	
			if ($tipom=="s"){
					if ($fechai != $fechaf){
						$where = "WHERE gc_despachos_producto.tipo='s' and gc_despachos.fecha between '" . $fechai . "' and '" . $fechaf . "'";
					}else{
						$where = "WHERE gc_despachos_producto.tipo='s' and gc_despachos.fecha = '" . $fechai . "'";				
					}									
				$spxd="SELECT gc_despachos_producto.id as id, gc_clientes.nombre as cliente, gc_despachos_producto.cantidad_tm as ton, gc_despachos_producto.cantidad_sacos as sacos,  gc_despachos_producto.lote as lote, gc_bodegas.nombre as bodega, gc_despachos.fecha as fecha, gc_despachos.orden as orden, gc_despachos.remision as remision, gc_despachos.placas as placas, gc_despachos.tiquete as tiquete, gc_conductores.nombre as conductor, gc_transportadores.razon_social as transportador, gc_motonaves.nombre as moto from gc_despachos_producto left join gc_bodegas on gc_despachos_producto.bodega = gc_bodegas.Id left join gc_despachos on gc_despachos_producto.remisionid=gc_despachos.remision and gc_despachos_producto.tipo=gc_despachos.tipo left join gc_clientes on gc_despachos_producto.cliente=gc_clientes.nit left join gc_conductores on gc_despachos.conductor=gc_conductores.id left join gc_transportadores on gc_despachos.transportador = gc_transportadores.nit left join gc_motonaves on gc_despachos_producto.motonave=gc_motonaves.motonaveId " . $where . " and gc_despachos_producto.producto=" . $pro . " and gc_despachos_producto.bodega=" . $bod;
			}
			$rspxd=mysqli_query($objConexion,$spxd) or die('MySql Error4' . mysqli_error($objConexion));
			$fspxd=mysqli_num_rows($rspxd);
			if ($fspxd>0) {
				$msg .= '<table class="jtable">';
				$msg .= '<thead>';
				$msg .= '<tr>';
				$msg .= '<th class="jtable-column-header" style="width:8%;">';
				$msg .= '<div class="jtable-column-header-container">';
				$msg .= '<span class="jtable-column-header-text">Fecha</span></div></th><th class="jtable-column-header" style="width:6%;"><div class="jtable-column-header-container"><span class="jtable-column-header-text">Kilos</span></div></th><th class="jtable-column-header" style="width:6%;"><div class="jtable-column-header-container"><span class="jtable-column-header-text">Sacos</span></div></th><th class="jtable-column-header" style="width:6%;"><div class="jtable-column-header-container"><span class="jtable-column-header-text">Remision</span></div></th><th class="jtable-column-header" style="width:6%;"><div class="jtable-column-header-container"><span class="jtable-column-header-text">Lote</span></div></th><th class="jtable-column-header" style="width:6%;"><div class="jtable-column-header-container"><span class="jtable-column-header-text">Orden</span></div></th><th class="jtable-column-header" style="width:6%;"><div class="jtable-column-header-container"><span class="jtable-column-header-text">Tiquete</span></div></th><th class="jtable-column-header" style="width:6%;"><div class="jtable-column-header-container"><span class="jtable-column-header-text">Placas</span></div></th><th class="jtable-column-header" style="width:22%;"><div class="jtable-column-header-container"><span class="jtable-column-header-text">Conductor</span></div></th><th class="jtable-column-header" style="width:22%;"><div class="jtable-column-header-container"><span class="jtable-column-header-text">Motonave</span></div></th><th class="jtable-column-header" style="width:22%;"><div class="jtable-column-header-container"><span class="jtable-column-header-text">Transportadora</span></div></th></tr></thead><tbody>';
				$g=0;
				$tton=0;
				$tsac=0;			
				while ($rowp=mysqli_fetch_array($rspxd)){
				   	if ($g%2==0){ 
						$msg .= '<tr class="jtable-rowp-even">';
					} else {
						$msg .= '<tr class="jtable-rowp-evenf">';
					}
					$msg .= '<td>' ;
					$msg .= $rowp['fecha'];
					$msg .= '</td>';
					$msg .= '<td align="right">' ;
					$msg .= number_format($rowp['ton'],0,"",",");
					$msg .= '</td>';
					$msg .= '<td align="right">' ;
					$msg .= number_format($rowp['sacos'],0,"",",");
					$msg .= '</td>';
					$msg .= '<td>' ;
					$msg .= $rowp['remision'];
					$msg .= '</td>' ;					
					$msg .= '<td>' ;
					$msg .= $rowp['lote'];
					$msg .= '</td>' ;
					$msg .= '<td>' ;
					$msg .= $rowp['orden'];
					$msg .= '</td>' ;
					$msg .= '<td>' ;
					$msg .= $rowp['tiquete'];
					$msg .= '</td>';						
					$msg .= '<td>' ;
					$msg .= $rowp['placas'];
					$msg .= '</td>';						
					$msg .= '<td>' ;
					$msg .= $rowp['conductor'];
					$msg .= '</td>';
					$msg .= '<td>' ;
					$msg .= $rowp['moto'];
					$msg .= '</td>';
					$msg .= '<td>' ;
					$msg .= $rowp['transportador'];
					$msg .= '</td></tr>';			
					$tton = $tton + $rowp['ton'];
					$tsac = $tsac + $rowp['sacos'];
					$g++;					
				}
					$msg .= '</tbody>';
					$msg .= '<tr><td>Totales</td><td align="right">';
					$msg .= number_format($tton,0,"",",");
					$msg .= '</td><td align="right">';
					$msg .= number_format($tsac,0,"",",");
					$msg .= '</td></tr>';
					$msg .= '</table></div>';
			}
		}
	break;
}
echo $msg;
?>