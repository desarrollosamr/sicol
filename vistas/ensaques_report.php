<?php
include"../clases/ConexionDatos.php";
extract($_REQUEST);
$objConexion=Conectarse();

$fechai=$_REQUEST['fechai'];
$fechaf=$_REQUEST['fechaf'];
$cli=$_REQUEST['cliente'];
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
$msg .= '<div class="jtable-title"><div class="jtable-title-text">Ensaques</div></div>';

switch ($criterio){
	case "general":
		$where = "WHERE fecha between '" . $fechai . "' and '" . $fechaf . "'";
    	$query_pag_data = "SELECT gc_ensaque.id as id, gc_ensaque.fecha as fecha, gc_ensaque.cantidad_tm as kilos, gc_ensaque.cantidad_sacos as sacos, gc_ensaque.granel as granel, (select nombre from gc_productos where gc_productos.productosId=gc_ensaque.granel) as granel, (select nombre from gc_productos where gc_productos.productosId=gc_ensaque.producto) as producto, gc_bodegas.nombre as bodega, gc_ensaque.lote from gc_ensaque left join gc_bodegas on gc_ensaque.bodega= gc_bodegas.Id " . $where . " order by gc_ensaque.fecha desc ";
		$result_pag_data = mysqli_query($objConexion,$query_pag_data) or die('Error despacho' . mysqli_error($objConexion));
		$msg .= '<table class="jtable"><thead class="ppal"><tr>';
		$msg .= '<th class="jtable-column-header" style="width:12%;">';
		$msg .= '<div class="jtable-column-header-container">';
		$msg .= '<span class="jtable-column-header-text">Fecha</span>';
		$msg .= '</div></th><th class="jtable-column-header" style="width:22%;"><div class="jtable-column-header-container"><span class="jtable-column-header-text">Granel</span></div></th><th class="jtable-column-header" style="width:22%;"><div class="jtable-column-header-container"><span class="jtable-column-header-text">Producto</span></div></th><th class="jtable-column-header" style="width:20%;"><div class="jtable-column-header-container"><span class="jtable-column-header-text">Bodega</span></div></th><th class="jtable-column-header" style="width:8%;"><div class="jtable-column-header-container"><span class="jtable-column-header-text">Lote</span></div></th><th class="jtable-column-header" style="width:8%;"><div class="jtable-column-header-container"><span class="jtable-column-header-text">Kilos</span></div></th><th class="jtable-column-header" style="width:8%;"><div class="jtable-column-header-container"><span class="jtable-column-header-text">Sacos</span></div></th></tr></thead><tbody>';			
		$u=0;
		$tton=0;
		$tsac=0;
		while ($row = mysqli_fetch_array($result_pag_data)) {

			if ($u%2==0){ 
				$msg .= '<tr class="jtable-row-even">';
			} else {
				$msg .= '<tr class="jtable-row-evenf">';
			}
			$msg .= '<td>';
			$msg .= $row['fecha'] ;
			$msg .= '</td>';
			$msg .= '<td>' ;
			$msg .= $row['granel'];
			$msg .= '</td>';			
			$msg .= '<td>' ;
			$msg .= $row['producto'];
			$msg .= '</td>';
			$msg .= '<td>' ;
			$msg .= $row['bodega'];
			$msg .= '</td>';
			$msg .= '<td>' ;
			$msg .= $row['lote'];
			$msg .= '</td>';
			$msg .= '<td align="right">' ;
			$msg .= number_format($row['kilos'],0,"",",");
			$msg .= '</td>';
			$msg .= '<td align="right">' ;
			$msg .= number_format($row['sacos'],0,"",",");
			$msg .= '</td>';											
			$msg .= '</tr>';
			$tton = $tton + $row['kilos'];
			$tsac = $tsac + $row['sacos'];
			$u++;					
		}
		$msg .= '</tbody>';
		$msg .= '<tr><td colspan=5>Totales</td><td align="right">';
		$msg .= number_format($tton,0,"",",");
		$msg .= '</td><td align="right">';
		$msg .= number_format($tsac,0,"",",");
		$msg .= '</td></tr>';
		$msg .= '</table></div>';
		break;
	case "bodega":
		if ($pro==0 and $cli==0){
				$msg .= '<table class="jtable"><thead class="ppal"><tr>';
				$msg .= '<th class="jtable-column-header" style="width:23%;">';
				$msg .= '<div class="jtable-column-header-container">';
				$msg .= '<span class="jtable-column-header-text">Bodega</span>';
				$msg .= '</div></th><th class="jtable-column-header" style="width:21%;"><div class="jtable-column-header-container"><span class="jtable-column-header-text">' . $nombrebodega . '</span></div></th></tr></thead><tbody>';	
				$spxd="SELECT gc_ensaque.id as id, gc_ensaque.fecha as fecha, gc_ensaque.cantidad_tm as kilos, gc_ensaque.cantidad_sacos as sacos, gc_ensaque.granel as granel, (select nombre from gc_productos where gc_productos.productosId=gc_ensaque.granel) as granel, (select nombre from gc_productos where gc_productos.productosId=gc_ensaque.producto) as producto, gc_bodegas.nombre as bodega, gc_ensaque.lote from gc_ensaque left join gc_bodegas on gc_ensaque.bodega= gc_bodegas.Id where fecha between '" . $fechai . "' and '" . $fechaf . "' and gc_ensaque.bodega=" . $bod . " order by gc_ensaque.fecha desc" ;
				$rspxd=mysqli_query($objConexion,$spxd) or die('MySql Error' . mysqli_error($objConexion));
				$fspxd=mysqli_num_rows($rspxd);
				if ($fspxd>0) {
						$msg .= '<table class="jtable"><thead class="ppal"><tr>';
						$msg .= '<th class="jtable-column-header" style="width:12%;">';
						$msg .= '<div class="jtable-column-header-container">';
						$msg .= '<span class="jtable-column-header-text">Fecha</span>';
						$msg .= '</div></th><th class="jtable-column-header" style="width:22%;"><div class="jtable-column-header-container"><span class="jtable-column-header-text">Granel</span></div></th><th class="jtable-column-header" style="width:22%;"><div class="jtable-column-header-container"><span class="jtable-column-header-text">Producto</span></div></th><th class="jtable-column-header" style="width:8%;"><div class="jtable-column-header-container"><span class="jtable-column-header-text">Lote</span></div></th><th class="jtable-column-header" style="width:8%;"><div class="jtable-column-header-container"><span class="jtable-column-header-text">Kilos</span></div></th><th class="jtable-column-header" style="width:8%;"><div class="jtable-column-header-container"><span class="jtable-column-header-text">Sacos</span></div></th></tr></thead><tbody>';			
						$g=0;
						$tton=0;
						$tsac=0;
						while ($row = mysqli_fetch_array($rspxd)) {

							if ($g%2==0){ 
								$msg .= '<tr class="jtable-row-even">';
							} else {
								$msg .= '<tr class="jtable-row-evenf">';
							}
							$msg .= '<td>';
							$msg .= $row['fecha'] ;
							$msg .= '</td>';
							$msg .= '<td>' ;
							$msg .= $row['granel'];
							$msg .= '</td>';			
							$msg .= '<td>' ;
							$msg .= $row['producto'];
							$msg .= '</td>';
							$msg .= '<td>' ;
							$msg .= $row['lote'];
							$msg .= '</td>';
							$msg .= '<td align="right">' ;
							$msg .= number_format($row['kilos'],0,"",",");
							$msg .= '</td>';
							$msg .= '<td align="right">' ;
							$msg .= number_format($row['sacos'],0,"",",");
							$msg .= '</td>';											
							$msg .= '</tr>';
							$tton = $tton + $row['kilos'];
							$tsac = $tsac + $row['sacos'];
							$g++;					
						}
						$msg .= '</tbody>';
						$msg .= '<tr><td colspan=4>Totales</td><td align="right">';
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
				$spxd="SELECT gc_ensaque.id as id, gc_ensaque.fecha as fecha, gc_ensaque.cantidad_tm as kilos, gc_ensaque.cantidad_sacos as sacos, gc_ensaque.granel as granel, (select nombre from gc_productos where gc_productos.productosId=gc_ensaque.granel) as granel, (select nombre from gc_productos where gc_productos.productosId=gc_ensaque.producto) as producto, gc_bodegas.nombre as bodega, gc_ensaque.lote from gc_ensaque left join gc_bodegas on gc_ensaque.bodega= gc_bodegas.Id where fecha between '" . $fechai . "' and '" . $fechaf . "' and gc_ensaque.bodega=" . $bod . " and gc_ensaque.producto=" . $pro . " order by gc_ensaque.fecha desc";
				$rspxd=mysqli_query($objConexion,$spxd) or die('MySql Error' . mysqli_error($objConexion));
				$fspxd=mysqli_num_rows($rspxd);
				if ($fspxd>0) {
						$msg .= '<table class="jtable"><thead class="ppal"><tr>';
						$msg .= '<th class="jtable-column-header" style="width:12%;">';
						$msg .= '<div class="jtable-column-header-container">';
						$msg .= '<span class="jtable-column-header-text">Fecha</span>';
						$msg .= '</div></th><th class="jtable-column-header" style="width:22%;"><div class="jtable-column-header-container"><span class="jtable-column-header-text">Granel</span></div></th><th class="jtable-column-header" style="width:8%;"><div class="jtable-column-header-container"><span class="jtable-column-header-text">Lote</span></div></th><th class="jtable-column-header" style="width:8%;"><div class="jtable-column-header-container"><span class="jtable-column-header-text">Kilos</span></div></th><th class="jtable-column-header" style="width:8%;"><div class="jtable-column-header-container"><span class="jtable-column-header-text">Sacos</span></div></th></tr></thead><tbody>';			
						$g=0;
						$tton=0;
						$tsac=0;
						while ($row = mysqli_fetch_array($rspxd)) {

							if ($g%2==0){ 
								$msg .= '<tr class="jtable-row-even">';
							} else {
								$msg .= '<tr class="jtable-row-evenf">';
							}
							$msg .= '<td>';
							$msg .= $row['fecha'] ;
							$msg .= '</td>';
							$msg .= '<td>' ;
							$msg .= $row['granel'];
							$msg .= '</td>';			
							$msg .= '<td>' ;
							$msg .= $row['lote'];
							$msg .= '</td>';
							$msg .= '<td align="right">' ;
							$msg .= number_format($row['kilos'],0,"",",");
							$msg .= '</td>';
							$msg .= '<td align="right">' ;
							$msg .= number_format($row['sacos'],0,"",",");
							$msg .= '</td>';											
							$msg .= '</tr>';
							$tton = $tton + $row['kilos'];
							$tsac = $tsac + $row['sacos'];
							$g++;					
						}
						$msg .= '</tbody>';
						$msg .= '<tr><td colspan=3>Totales</td><td align="right">';
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
				$spxd="SELECT gc_ensaque.id as id, gc_ensaque.fecha as fecha, gc_ensaque.cantidad_tm as kilos, gc_ensaque.cantidad_sacos as sacos, gc_ensaque.granel as granel, (select nombre from gc_productos where gc_productos.productosId=gc_ensaque.granel) as granel, (select nombre from gc_productos where gc_productos.productosId=gc_ensaque.producto) as producto, gc_bodegas.nombre as bodega, gc_ensaque.lote, gc_ensaque.cliente from gc_ensaque left join gc_bodegas on gc_ensaque.bodega= gc_bodegas.Id where fecha between '" . $fechai . "' and '" . $fechaf . "' and gc_ensaque.bodega=" . $bod . " and gc_ensaque.cliente=" . $cli . " order by gc_ensaque.fecha desc";
				$rspxd=mysqli_query($objConexion,$spxd) or die('MySql Error' . mysqli_error($objConexion));
				$fspxd=mysqli_num_rows($rspxd);
				if ($fspxd>0) {
						$msg .= '<table class="jtable"><thead class="ppal"><tr>';
						$msg .= '<th class="jtable-column-header" style="width:12%;">';
						$msg .= '<div class="jtable-column-header-container">';
						$msg .= '<span class="jtable-column-header-text">Fecha</span>';
						$msg .= '</div></th><th class="jtable-column-header" style="width:22%;"><div class="jtable-column-header-container"><span class="jtable-column-header-text">Producto</span></div></th><th class="jtable-column-header" style="width:22%;"><div class="jtable-column-header-container"><span class="jtable-column-header-text">Granel</span></div></th><th class="jtable-column-header" style="width:8%;"><div class="jtable-column-header-container"><span class="jtable-column-header-text">Lote</span></div></th><th class="jtable-column-header" style="width:8%;"><div class="jtable-column-header-container"><span class="jtable-column-header-text">Kilos</span></div></th><th class="jtable-column-header" style="width:8%;"><div class="jtable-column-header-container"><span class="jtable-column-header-text">Sacos</span></div></th></tr></thead><tbody>';			
						$g=0;
						$tton=0;
						$tsac=0;
						while ($row = mysqli_fetch_array($rspxd)) {

							if ($g%2==0){ 
								$msg .= '<tr class="jtable-row-even">';
							} else {
								$msg .= '<tr class="jtable-row-evenf">';
							}
							$msg .= '<td>';
							$msg .= $row['fecha'] ;
							$msg .= '</td>';
							$msg .= '<td>' ;
							$msg .= $row['granel'];
							$msg .= '</td>';			
							$msg .= '<td>' ;
							$msg .= $row['producto'];
							$msg .= '</td>';							
							$msg .= '<td>' ;
							$msg .= $row['lote'];
							$msg .= '</td>';
							$msg .= '<td align="right">' ;
							$msg .= number_format($row['kilos'],0,"",",");
							$msg .= '</td>';
							$msg .= '<td align="right">' ;
							$msg .= number_format($row['sacos'],0,"",",");
							$msg .= '</td>';											
							$msg .= '</tr>';
							$tton = $tton + $row['kilos'];
							$tsac = $tsac + $row['sacos'];
							$g++;					
						}
						$msg .= '</tbody>';
						$msg .= '<tr><td colspan=4>Totales</td><td align="right">';
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
				$msg .= '</div></th><th class="jtable-column-header" style="width:21%;"><div class="jtable-column-header-container"><span class="jtable-column-header-text">' . $nombrecliente . '</span></div></th></tr></thead><tbody>';						$spxd="SELECT gc_ensaque.id as id, gc_ensaque.fecha as fecha, gc_ensaque.cantidad_tm as kilos, gc_ensaque.cantidad_sacos as sacos, gc_ensaque.granel as granel, (select nombre from gc_productos where gc_productos.productosId=gc_ensaque.granel) as granel, (select nombre from gc_productos where gc_productos.productosId=gc_ensaque.producto) as producto, gc_bodegas.nombre as bodega, gc_ensaque.lote from gc_ensaque left join gc_bodegas on gc_ensaque.bodega= gc_bodegas.Id where fecha between '" . $fechai . "' and '" . $fechaf . "' and gc_ensaque.cliente=" . $cli . " order by gc_ensaque.fecha desc";
				$rspxd=mysqli_query($objConexion,$spxd) or die('MySql Error1' . mysqli_error($objConexion));
				$fspxd=mysqli_num_rows($rspxd);
				if ($fspxd>0) {
						$msg .= '<table class="jtable"><thead class="ppal"><tr>';
						$msg .= '<th class="jtable-column-header" style="width:12%;">';
						$msg .= '<div class="jtable-column-header-container">';
						$msg .= '<span class="jtable-column-header-text">Fecha</span>';
						$msg .= '</div></th><th class="jtable-column-header" style="width:22%;"><div class="jtable-column-header-container"><span class="jtable-column-header-text">Granel</span></div></th><th class="jtable-column-header" style="width:22%;"><div class="jtable-column-header-container"><span class="jtable-column-header-text">Bodega</span></div></th><th class="jtable-column-header" style="width:8%;"><div class="jtable-column-header-container"><span class="jtable-column-header-text">Lote</span></div></th><th class="jtable-column-header" style="width:8%;"><div class="jtable-column-header-container"><span class="jtable-column-header-text">Kilos</span></div></th><th class="jtable-column-header" style="width:8%;"><div class="jtable-column-header-container"><span class="jtable-column-header-text">Sacos</span></div></th></tr></thead><tbody>';			
						$g=0;
						$tton=0;
						$tsac=0;
						while ($row = mysqli_fetch_array($rspxd)) {

							if ($g%2==0){ 
								$msg .= '<tr class="jtable-row-even">';
							} else {
								$msg .= '<tr class="jtable-row-evenf">';
							}
							$msg .= '<td>';
							$msg .= $row['fecha'] ;
							$msg .= '</td>';
							$msg .= '<td>' ;
							$msg .= $row['granel'];
							$msg .= '</td>';			
							$msg .= '<td>' ;
							$msg .= $row['bodega'];
							$msg .= '</td>';							
							$msg .= '<td>' ;
							$msg .= $row['lote'];
							$msg .= '</td>';
							$msg .= '<td align="right">' ;
							$msg .= number_format($row['kilos'],0,"",",");
							$msg .= '</td>';
							$msg .= '<td align="right">' ;
							$msg .= number_format($row['sacos'],0,"",",");
							$msg .= '</td>';											
							$msg .= '</tr>';
							$tton = $tton + $row['kilos'];
							$tsac = $tsac + $row['sacos'];
							$g++;					
						}
						$msg .= '</tbody>';
						$msg .= '<tr><td colspan=4>Totales</td><td align="right">';
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
				$spxd="SELECT gc_ensaque.id as id, gc_ensaque.fecha as fecha, gc_ensaque.cantidad_tm as kilos, gc_ensaque.cantidad_sacos as sacos, gc_ensaque.granel as granel, (select nombre from gc_productos where gc_productos.productosId=gc_ensaque.granel) as granel, (select nombre from gc_productos where gc_productos.productosId=gc_ensaque.producto) as producto, gc_bodegas.nombre as bodega, gc_ensaque.lote from gc_ensaque left join gc_bodegas on gc_ensaque.bodega= gc_bodegas.Id where fecha between '" . $fechai . "' and '" . $fechaf . "' and gc_ensaque.cliente=" . $cli . " and gc_ensaque.producto=" . $pro . " order by gc_ensaque.fecha desc";
				$rspxd=mysqli_query($objConexion,$spxd) or die('MySql Error2' . mysqli_error($objConexion));
				$fspxd=mysqli_num_rows($rspxd);
				if ($fspxd>0) {
					$msg .= '<table class="jtable">';
					$msg .= '<thead>';
					$msg .= '<tr>';
					$msg .= '<th class="jtable-column-header" style="width:12%;">';
					$msg .= '<div class="jtable-column-header-container">';
					$msg .= '<span class="jtable-column-header-text">Fecha</span></div></th><th class="jtable-column-header" style="width:26%;"><div class="jtable-column-header-container"><span class="jtable-column-header-text">Granel</span></div></th><th class="jtable-column-header" style="width:26%;"><div class="jtable-column-header-container"><span class="jtable-column-header-text">Bodega</span></div></th><th class="jtable-column-header" style="width:6%;"><div class="jtable-column-header-container"><span class="jtable-column-header-text">Kilos</span></div></th><th class="jtable-column-header" style="width:6%;"><div class="jtable-column-header-container"><span class="jtable-column-header-text">Sacos</span></div></th><th class="jtable-column-header" style="width:6%;"><div class="jtable-column-header-container"><span class="jtable-column-header-text">Lote</span></div></th></tr></thead><tbody>';
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
						$msg .= $rowp['granel'];
						$msg .= '</td>';
						$msg .= '<td>' ;
						$msg .= $rowp['bodega'];
						$msg .= '</td>';						
						$msg .= '<td align="right">' ;
						$msg .= number_format($rowp['kilos'],0,"",",");
						$msg .= '</td>';
						$msg .= '<td align="right">' ;
						$msg .= number_format($rowp['sacos'],0,"",",");
						$msg .= '</td>';
						$msg .= '<td>' ;
						$msg .= $rowp['lote'];
						$msg .= '</td></tr>';			
						$tton = $tton + $rowp['kilos'];
						$tsac = $tsac + $rowp['sacos'];
						$g++;					
					}
					$msg .= '</tbody>';
					$msg .= '<tr><td colspan=3>Totales</td><td align="right">';
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
				$spxd="SELECT gc_ensaque.id as id, gc_ensaque.fecha as fecha, gc_ensaque.cantidad_tm as kilos, gc_ensaque.cantidad_sacos as sacos, gc_ensaque.granel as granel, (select nombre from gc_productos where gc_productos.productosId=gc_ensaque.granel) as granel, (select nombre from gc_productos where gc_productos.productosId=gc_ensaque.producto) as producto, gc_bodegas.nombre as bodega, gc_ensaque.lote from gc_ensaque left join gc_bodegas on gc_ensaque.bodega= gc_bodegas.Id where fecha between '" . $fechai . "' and '" . $fechaf . "' and gc_ensaque.cliente=" . $cli . " and gc_ensaque.bodega=" . $bod . " order by gc_ensaque.fecha desc";
				$rspxd=mysqli_query($objConexion,$spxd) or die('MySql Error4' . mysqli_error($objConexion));
				$fspxd=mysqli_num_rows($rspxd);
				if ($fspxd>0) {
					$msg .= '<table class="jtable">';
					$msg .= '<thead>';
					$msg .= '<tr>';
					$msg .= '<th class="jtable-column-header" style="width:28%;">';
					$msg .= '<div class="jtable-column-header-container">';
					$msg .= '<span class="jtable-column-header-text">Fecha</span></div></th><th class="jtable-column-header" style="width:26%;"><div class="jtable-column-header-container"><span class="jtable-column-header-text">Granel</span></div></th><th class="jtable-column-header" style="width:26%;"><div class="jtable-column-header-container"><span class="jtable-column-header-text">Producto</span></div></th><th class="jtable-column-header" style="width:6%;"><div class="jtable-column-header-container"><span class="jtable-column-header-text">Kilos</span></div></th><th class="jtable-column-header" style="width:6%;"><div class="jtable-column-header-container"><span class="jtable-column-header-text">Sacos</span></div></th><th class="jtable-column-header" style="width:6%;"><div class="jtable-column-header-container"><span class="jtable-column-header-text">Lote</span></div></th></tr></thead><tbody>';
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
						$msg .= $rowp['granel'];
						$msg .= '</td>';
						$msg .= '<td>' ;
						$msg .= $rowp['producto'];
						$msg .= '</td>';						
						$msg .= '<td align="right">' ;
						$msg .= number_format($rowp['kilos'],0,"",",");
						$msg .= '</td>';
						$msg .= '<td align="right">' ;
						$msg .= number_format($rowp['sacos'],0,"",",");
						$msg .= '</td>';
						$msg .= '<td>' ;
						$msg .= $rowp['lote'];
						$msg .= '</td></tr>';			
						$tton = $tton + $rowp['kilos'];
						$tsac = $tsac + $rowp['sacos'];
						$g++;					
					}
					$msg .= '</tbody>';
					$msg .= '<tr><td colspan=3>Totales</td><td align="right">';
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
			$spxd="SELECT gc_ensaque.id as id, gc_ensaque.fecha as fecha, gc_ensaque.cantidad_tm as kilos, gc_ensaque.cantidad_sacos as sacos, gc_ensaque.granel as granel, (select nombre from gc_productos where gc_productos.productosId=gc_ensaque.granel) as granel, (select nombre from gc_productos where gc_productos.productosId=gc_ensaque.producto) as producto, gc_bodegas.nombre as bodega, gc_ensaque.lote from gc_ensaque left join gc_bodegas on gc_ensaque.bodega= gc_bodegas.Id where fecha between '" . $fechai . "' and '" . $fechaf . "' and gc_ensaque.producto=" . $pro . " order by gc_ensaque.fecha desc";

			$rspxd=mysqli_query($objConexion,$spxd) or die('MySql Error1' . mysqli_error($objConexion));
			$fspxd=mysqli_num_rows($rspxd);
			if ($fspxd>0) {
					$msg .= '<table class="jtable">';
					$msg .= '<thead>';
					$msg .= '<tr>';
					$msg .= '<th class="jtable-column-header" style="width:28%;">';
					$msg .= '<div class="jtable-column-header-container">';
					$msg .= '<span class="jtable-column-header-text">Fecha</span></div></th><th class="jtable-column-header" style="width:26%;"><div class="jtable-column-header-container"><span class="jtable-column-header-text">Granel</span></div></th><th class="jtable-column-header" style="width:26%;"><div class="jtable-column-header-container"><span class="jtable-column-header-text">Bodega</span></div></th><th class="jtable-column-header" style="width:6%;"><div class="jtable-column-header-container"><span class="jtable-column-header-text">Kilos</span></div></th><th class="jtable-column-header" style="width:6%;"><div class="jtable-column-header-container"><span class="jtable-column-header-text">Sacos</span></div></th><th class="jtable-column-header" style="width:6%;"><div class="jtable-column-header-container"><span class="jtable-column-header-text">Lote</span></div></th></tr></thead><tbody>';
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
						$msg .= $rowp['granel'];
						$msg .= '</td>';
						$msg .= '<td>' ;
						$msg .= $rowp['bodega'];
						$msg .= '</td>';						
						$msg .= '<td align="right">' ;
						$msg .= number_format($rowp['kilos'],0,"",",");
						$msg .= '</td>';
						$msg .= '<td align="right">' ;
						$msg .= number_format($rowp['sacos'],0,"",",");
						$msg .= '</td>';
						$msg .= '<td>' ;
						$msg .= $rowp['lote'];
						$msg .= '</td></tr>';			
						$tton = $tton + $rowp['kilos'];
						$tsac = $tsac + $rowp['sacos'];
						$g++;					
					}
					$msg .= '</tbody>';
					$msg .= '<tr><td colspan=3>Totales</td><td align="right">';
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
			$msg .= '</div></th><th class="jtable-column-header" style="width:24%;"><div class="jtable-column-header-container"><span class="jtable-column-header-text">' . $nombreproducto . '</span></div></th><th class="jtable-column-header" style="width:10%;"><div class="jtable-column-header-container"><span class="jtable-column-header-text">Bodega</span></div></th><th class="jtable-column-header" style="width:23%;"><div class="jtable-column-header-container"><span class="jtable-column-header-text">' . $nombrebodega . '</span></div></th></tr></thead><tbody>';						
			$spxd="SELECT gc_ensaque.id as id, gc_ensaque.fecha as fecha, gc_ensaque.cantidad_tm as kilos, gc_ensaque.cantidad_sacos as sacos, gc_ensaque.granel as granel, (select nombre from gc_productos where gc_productos.productosId=gc_ensaque.granel) as granel, (select nombre from gc_productos where gc_productos.productosId=gc_ensaque.producto) as producto, gc_bodegas.nombre as bodega, gc_ensaque.lote from gc_ensaque left join gc_bodegas on gc_ensaque.bodega= gc_bodegas.Id where fecha between '" . $fechai . "' and '" . $fechaf . "' and gc_ensaque.producto=" . $pro . " and gc_ensaque.bodega=" . $bod . " order by gc_ensaque.fecha desc";
			$rspxd=mysqli_query($objConexion,$spxd) or die('MySql Error4' . mysqli_error($objConexion));
			$fspxd=mysqli_num_rows($rspxd);
			if ($fspxd>0) {
					$msg .= '<table class="jtable">';
					$msg .= '<thead>';
					$msg .= '<tr>';
					$msg .= '<th class="jtable-column-header" style="width:28%;">';
					$msg .= '<div class="jtable-column-header-container">';
					$msg .= '<span class="jtable-column-header-text">Fecha</span></div></th><th class="jtable-column-header" style="width:26%;"><div class="jtable-column-header-container"><span class="jtable-column-header-text">Granel</span></div></th><th class="jtable-column-header" style="width:6%;"><div class="jtable-column-header-container"><span class="jtable-column-header-text">Kilos</span></div></th><th class="jtable-column-header" style="width:6%;"><div class="jtable-column-header-container"><span class="jtable-column-header-text">Sacos</span></div></th><th class="jtable-column-header" style="width:6%;"><div class="jtable-column-header-container"><span class="jtable-column-header-text">Lote</span></div></th></tr></thead><tbody>';
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
						$msg .= $rowp['granel'];
						$msg .= '</td>';
						$msg .= '<td align="right">' ;
						$msg .= number_format($rowp['kilos'],0,"",",");
						$msg .= '</td>';
						$msg .= '<td align="right">' ;
						$msg .= number_format($rowp['sacos'],0,"",",");
						$msg .= '</td>';
						$msg .= '<td>' ;
						$msg .= $rowp['lote'];
						$msg .= '</td></tr>';			
						$tton = $tton + $rowp['kilos'];
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
}
echo $msg;

