<?php

require('TextMagicAPI.php');

$usuario = 'gabrielernestobarrio';
$password = 'wE8NxddZXZ';

$enrutador = new TextMagicAPI(array(
			    'username' => $usuario,
			    'password' => $password
));

$respuesta = $enrutador->send('Nuevo mensaje de texto desde PHP!', array(573233656749), true);

var_dump($respuesta);
?>