<?php
require "../clases/ConexionDatos.php";
//require_once('../clases/PHPMailerAutoload.php');
require_once("PHPMailerAutoload.php");
require('../clases/mc_table.php');

//if (!isset($_SESSION['user']))
	//header("location:index.php?x=2");
extract ($_REQUEST);
$objConexion=Conectarse();
if (isset($_SESSION['orden'])){
	$ordena = $_SESSION['orden'];
} else {
	$ordena = $_REQUEST['orden'];
}
if (isset($_SESSION['proveedor'])){
	$proveedora = $_SESSION['proveedor'];
} else {
	$proveedora = $_REQUEST['proveedor'];
}
//echo $orden;
//echo $proveedora;

$tarifas = "select id, nombre, valor, unidad from gc_tarifas where proveedor=" . $proveedora . " order by nombre";
$rstarifas = $objConexion->query($tarifas);
$motonaves = "select id, motonave from gc_recibo_buques_contenedores order by motonave";
$rsmotonaves = $objConexion->query($motonaves);
$proveedores = "select nit, razon_social from gc_proveedores where nit=" . $proveedora;
$rsproveedores = $objConexion->query($proveedores);
$proveedor = $rsproveedores->fetch_object();

$sql="select gc_ordenes.id as ordenid, gc_proveedores.razon_social as proveedor from gc_ordenes left join gc_proveedores on gc_ordenes.proveedor = gc_proveedores.nit where gc_ordenes.id = '$ordena'";
$resultadoordenproveedor = $objConexion->query($sql);
$ordenproveedor = $resultadoordenproveedor->fetch_object();

$sqlld="SELECT  `gc_ordenes_detalle`.`id` as id,  `gc_ordenes_detalle`.`orden` as orden,  `gc_ordenes_detalle`.`tarifa` as tarifa,  `gc_tarifas`.`nombre` as tarifanombre,  `gc_tarifas`.`valor` as valor,  `gc_ordenes_detalle`.`cantidad` as cantidad, `gc_programacion_recibo`.`motonave` as nave,  `gc_motonaves`.`nombre` as navenombre,  `gc_ordenes_detalle`.`detalle` as detalle 
	FROM gc_ordenes_detalle
	LEFT JOIN  `gc_programacion_recibo` ON  `gc_ordenes_detalle`.`operacion` =  `gc_programacion_recibo`.`id` 
	LEFT JOIN  `gc_tarifas` ON  `gc_ordenes_detalle`.`tarifa` =  `gc_tarifas`.`id` 
	LEFT JOIN  `gc_motonaves` ON  `gc_programacion_recibo`.`motonave` =  `gc_motonaves`.`motonaveId` where orden =" . $ordena . " group by `gc_programacion_recibo`.`motonave`";
$rsld= $objConexion->query($sqlld);


//$mail = new PHPMailer(); 

//$mail->IsSMTP(); 

try {
  //$mail->IsHTML(true);
  //$mail->Host       = "mx1.hostinger.es"; // SMTP server
  //$mail->SMTPDebug  = 2;                     // enables SMTP debug information (for testing)
  //$mail->SMTPAuth   = true;                  // enable SMTP authentication
  //$mail->Host       = "mx1.hostinger.es"; // sets the SMTP server
  //$mail->Port       = 2525;                    // set the SMTP port for the GMAIL server
  //$mail->Username   = "webmaster@proyectosena.hol.es"; // SMTP account username
  //$mail->Password   = "the_reborn";        // SMTP account password
  //$mail->AddReplyTo('gbarriosf@gmail.com', 'Gabriel Barrios');
  //$mail->SetFrom('webmaster@sicol.hol.es', 'SICOL');
  //$mail->AddAddress('asanchez@norpack.com.co');
  //$mail->AddAddress('asanchez@norpack.com.co');
  //$mail->AddAddress('gbarrios@norpack.com.co');
  //$mail->AddCC('webmaster@proyectosena.hol.es');
  //$mail->AddReplyTo('gbarriosf@gmail.com', 'Gabriel Barrios');
  //$mail->Subject = 'Solicitud de autorizacion de orden de compra No. ' . $ordena;
  //$mail->AltBody = 'Para ver este mensaje, por favor utilice un cliente de correo compatible con HTML!'; 
  //$mail->Body = 'Enviar a Eparquio Gonzalez';
  $archivo="sol_orden_" . $ordena . ".pdf";
  $pdf=new PDF_MC_Table();
  $pdf->AddPage();
  $pdf->SetFont('Arial','B',12);
  $orden='Orden No. ' . $ordena;
  $prove='Proveedor: ' . $proveedor->razon_social;
  $pdf->Cell(0,7,$orden);
  $pdf->SetXY(10,10);
  $pdf->Cell(0,17,$prove);
  $pdf->SetXY(10,20);
  $pdf->Cell(0,7,'Detalle de la orden');
  $pdf->SetFont('Arial','B',11);
  $pdf->SetWidths(array(50,50,20,12,20,30));
  $pdf->SetAligns(array('L','L','R','C','R','R'));
  $pdf->SetXY(10,30);
  $pdf->SetFillColor(255,255,255);
  $pdf->Row(array('Servicio','Detalle','Cantidad','UND','Vr. Unit.','Vr. Total'));
  $totalorden = 0;
  while ($oldetalle = $rsld->fetch_object()){
    $opac = $oldetalle->nave;
	$pdf->SetFillColor(209,240,247);
	$pdf->Row(array('OPERACION',strtoupper($oldetalle->navenombre),'','','',''));
	$sqlod="SELECT  `gc_ordenes_detalle`.`id` as id,  `gc_ordenes_detalle`.`orden` as orden,  `gc_ordenes_detalle`.`tarifa` as tarifa,  `gc_tarifas`.`nombre` as tarifanombre,  `gc_tarifas`.`valor` as valor,  `gc_tarifas`.`unidad` as unidad,  `gc_ordenes_detalle`.`cantidad` as cantidad, `gc_programacion_recibo`.`motonave` as nave,  `gc_motonaves`.`nombre` as navenombre,  `gc_ordenes_detalle`.`detalle` as detalle 
	FROM gc_ordenes_detalle
	LEFT JOIN  `gc_programacion_recibo` ON  `gc_ordenes_detalle`.`operacion` =  `gc_programacion_recibo`.`id` 
	LEFT JOIN  `gc_tarifas` ON  `gc_ordenes_detalle`.`tarifa` =  `gc_tarifas`.`id` 
	LEFT JOIN  `gc_motonaves` ON  `gc_programacion_recibo`.`motonave` =  `gc_motonaves`.`motonaveId`
	 where `gc_programacion_recibo`.`motonave` =" . $opac . " and `gc_ordenes_detalle`.`orden` =" . $ordena;
	$resultordendetalle = $objConexion->query($sqlod);

	  while ($odetalle = $resultordendetalle->fetch_object()) {
			$total = $odetalle->valor * $odetalle->cantidad;
			$totalorden += $total;
			$pdf->SetFillColor(255,255,255);
			$pdf->Row(array($odetalle->tarifanombre,$odetalle->detalle,number_format($odetalle->cantidad,2),$odetalle->unidad,number_format($odetalle->valor),number_format($total)));
	  }
  }
  $pdf->Cell(152,10,'Total de la orden:',1);
  $pdf->SetX(162);
  $pdf->Cell(30,10,number_format($totalorden),1,0,'R');
  ob_end_clean();
  $pdf->Output($archivo, 'D');
  //$mail->AddAttachment($archivo, $archivo, 'base64', 'application/pdf');
  //$mail->Send();
} catch (phpmailerException $e) {
  echo $e->errorMessage(); //Pretty error messages from PHPMailer
} catch (Exception $e) {
  echo $e->getMessage(); //Boring error messages from anything else!
}

//header ("location: ../Plantilla/vistaPrincipal.php?pg=../vistas/paginador1.php&lista=../vistas/ordenes_data.php&modulo=ordenes&solicitado=1");
?>
