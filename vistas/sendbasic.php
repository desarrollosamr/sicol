<?php
require_once("PHPMailerAutoload.php");

$mail = new PHPMailer();
$mail->From = "gbarrios@norpack.com.co";
$mail->FromName = "From Name";
$mail->Subject = "Demo de PHPMailer";
$mail->Body = "Hola <strong>tito</strong>, bienvenido!!!";
$mail->IsHTML(true);
$mail->AddAddress("webmaster@proyectosena.hol.es", "User Name");
$mail->Send();
?>