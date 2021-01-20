<?php
require 'clases/smsuplib.php';
$s = new smsup\smsuplib('gbarriosf','the_reborn');
$s->NuevoSMS('Mensaje de prueba', array('573233656749'), null, '', 'SMSUP');