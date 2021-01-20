<?php
$para  = 'gbarrios@norpack.com.co' . ', '; // atención a la coma
$para .= 'webmaster@proyectosena.hol.es';

// Asunto
$titulo = 'Atencion con cambio de tarifas';

// Cuerpo o mensaje
$mensaje = '
<html>
<head>
  <title>Atención con el Cambio de Tarifas y Comisiones</title>
</head>
<body>
  <p>¡todos los vendedores a leer las tarifas segun empresas!</p>
  <table>
    <tr>
      <th>Empresa</th><th>Tarifa Anterior</th><th>Nueva Tarifa</th><th>Comision</th>
    </tr>
    <tr>
      <td>Repsol</td><td>3.3</td><td>3.5</td><td>10%</td>
    </tr>
    <tr>
      <td>Telefonica</td><td>17.45</td><td>18.1</td><td>11%</td>
    </tr>
  </table>
</body>
</html>
';

// Cabecera que especifica que es un HMTL
$cabeceras  = 'MIME-Version: 1.0' . "\r\n";
$cabeceras .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";

// Cabeceras adicionales
$cabeceras .= 'From: SICOL <webmaster@proyectosena.hol.es>' . "\r\n";
$cabeceras .= 'Cc: archivotarifas@example.com' . "\r\n";
$cabeceras .= 'Bcc: copiaoculta@example.com' . "\r\n";

// enviamos el correo!
mail($para, $titulo, $mensaje, $cabeceras);
?>