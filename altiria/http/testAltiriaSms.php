<?php
// Copyright (c) 2018, Altiria TIC SL
// All rights reserved.
// El uso de este código de ejemplo es solamente para mostrar el uso de la pasarela de envío de SMS de Altiria
// Para un uso personalizado del código, es necesario consultar la API de especificaciones técnicas, donde también podrás encontrar
// más ejemplos de programación en otros lenguajes y otros protocolos (http, REST, web services)
// https://www.altiria.com/api-envio-sms/

// XX, YY y ZZ se corresponden con los valores de identificacion del
// usuario en el sistema.
include('httpPHPAltiria.php');

$altiriaSMS = new AltiriaSMS();
$altiriaSMS->setUrl("http://www.altiria.net/api/http");
$altiriaSMS->setDomainId('test');
$altiriaSMS->setLogin('gbarriosf');
$altiriaSMS->setPassword('89sbrayj');

$altiriaSMS->setDebug(true);

//$sDestination = '346xxxxxxxx';
$sDestination = '573005544261';
//$sDestination = array('346xxxxxxxx','346yyyyyyyy');

//No es posible utilizar el remitente en América pero sí en España y Europa
$response = $altiriaSMS->sendSMS($sDestination, "Te amo pampuncho");
//Utilizar esta llamada solo si se cuenta con un remitente autorizado por Altiria
//$response = $altiriaSMS->sendSMS($sDestination, "Mensaje de prueba", "remitente");

if (!$response)
  echo "El envío ha terminado en error";
else
  echo $response;
?>

