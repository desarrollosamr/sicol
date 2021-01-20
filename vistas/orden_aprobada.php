<?php
require "../clases/ConexionDatos.php";
require_once('../clases/class.phpmailer.php');

//if (!isset($_SESSION['user']))
	//header("location:index.php?x=2");

$objConexion=Conectarse();
$ordena = $_SESSION['orden'];
$proveedora = $_SESSION['proveedor'];

$tarifas = "select id, nombre, valor, unidad from gc_tarifas where proveedor=" . $proveedora . " order by nombre";
//$tarifas = "select id, nombre from gc_tarifas order by nombre";
$rstarifas = $objConexion->query($tarifas);
$motonaves = "select id, motonave from gc_recibo_buques_contenedores order by motonave";
$rsmotonaves = $objConexion->query($motonaves);
$proveedores = "select nit, razon_social from gc_proveedores where nit=" . $proveedora;
$rsproveedores = $objConexion->query($proveedores);
$proveedor = $rsproveedores->fetch_object();

$sql="select gc_ordenes.id as ordenid, gc_proveedores.razon_social as proveedor from gc_ordenes left join gc_proveedores on gc_ordenes.proveedor = gc_proveedores.nit where gc_ordenes.id = '$ordena'";
$resultadoordenproveedor = $objConexion->query($sql);
$ordenproveedor = $resultadoordenproveedor->fetch_object();

$sqlod="SELECT `gc_ordenes_detalle`.`id` as codigo,`gc_ordenes_detalle`.`orden` as orden,`gc_ordenes_detalle`.`detalle` as detalle,`gc_tarifas`.`nombre` as tarifa,`gc_tarifas`.`valor` as valor,`gc_tarifas`.`unidad` as unidad,`gc_ordenes_detalle`.`cantidad` as cantidad,`gc_recibo_buques_contenedores`.`motonave` as operacion FROM `gc_ordenes_detalle`
LEFT OUTER JOIN `gc_tarifas` ON `gc_ordenes_detalle`.`tarifa` = `gc_tarifas`.`id`
LEFT OUTER JOIN `gc_recibo_buques_contenedores` ON `gc_ordenes_detalle`.`operacion` = `gc_recibo_buques_contenedores`.`id` where orden=" . $ordena;

$resultordendetalle = $objConexion->query($sqlod);
$mail = new PHPMailer(true); // the true param means it will throw exceptions on errors, which we need to catch

$mail->IsSMTP(); // telling the class to use SMTP

try {
  $mail->IsHTML(true);
  $mail->Host       = "mx1.hostinger.es"; // SMTP server
  $mail->SMTPDebug  = 2;                     // enables SMTP debug information (for testing)
  $mail->SMTPAuth   = true;                  // enable SMTP authentication
  $mail->Host       = "mx1.hostinger.es"; // sets the SMTP server
  $mail->Port       = 2525;                    // set the SMTP port for the GMAIL server
  $mail->Username   = "webmaster@proyectosena.hol.es"; // SMTP account username
  $mail->Password   = "the_reborn";        // SMTP account password
  $mail->AddReplyTo('gbarriosf@gmail.com', 'First Last');
  $mail->SetFrom('webmaster@sicol.hol.es', 'SICOL');
  $mail->AddAddress('gbarrios@norpack.com.co');
//  $mail->AddAddress('eparquio.gonzalez@norpack.com.co');
//  $mail->AddCC('gbarrios@norpack.com.co');
//  $mail->AddCC('asanchez@norpack.com.co');
  $mail->AddReplyTo('gbarriosf@gmail.com', 'First Last');
  $mail->Subject = 'Solicitud de autorizacion de orden de compra';
  $mail->AltBody = 'To view the message, please use an HTML compatible email viewer!'; // optional - MsgHTML will create an alternate automatically
$cuerpo='
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Formulario Actualizar Detalles de Solicitudes de Ordenes de Compra</title>
</head>

<body>

<h2>Orden No. ' . $ordena  . '</h2>
<hr>
<p>Proveedor: ' . $proveedor->razon_social . '</p>
<hr>
<h3>Detalle de la orden</h3>
<hr>
<table border="1" bordercollapse="collapse"><tr><td>Servicio</td><td>Detalle</td><td>Cantidad</td><td>Unidad</td><td>Vr Unitario</td><td>Vr Total</td></tr>';
$totalorden = 0;
while ($odetalle = $resultordendetalle->fetch_object()) {
	$total = $odetalle->valor * $odetalle->cantidad;
	$totalorden += $total; 
    $cuerpo .= '<tr><td>' . $odetalle->tarifa . '</td><td>' . $odetalle->detalle . '</td><td align="right">' . number_format($odetalle->cantidad,2) . '</td><td>' . $odetalle->unidad . '</td><td align="right">' . number_format($odetalle->valor) . '</td><td align="right">' . number_format($total) . '</td></tr>';
}
$cuerpo.= '<tr><td colspan="5">Total de la orden:</td><td align="right">' . number_format($totalorden) . '</table>';
$cuerpo.= '<a href="http://proyectosena.hol.es/controlador/coordenes.php?orden=' . $ordena . '&ap=1&boton=Actualizar">Aprobar</a></body></html>';
$mail->Body = $cuerpo;
$mail->Send();
echo "<script>alert('El mensaje fue enviado correctamente')</script>";
} catch (phpmailerException $e) {
  echo $e->errorMessage(); //Pretty error messages from PHPMailer
} catch (Exception $e) {
  echo $e->getMessage(); //Boring error messages from anything else!
}

header ("location: ../Plantilla/vistaPrincipal.php?pg=../vistas/paginador2.php&lista=../vistas/ordenes_data.php&solicitado=1");
?>
