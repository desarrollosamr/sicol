<?php
<?php

include '../pear/Mail.php';
include '../Mail/mime.php' ;

$text = 'Text version of email';
$html = '<html><body>HTML version of email</body></html>';
$file = '/home/richard/example.php';
$crlf = "\n";
$hdrs = array(
              'From'    => 'you@yourdomain.com',
              'Subject' => 'Test mime message'
              );

$mime = new Mail_mime(array('eol' => $crlf));

$mime->setTXTBody($text);
$mime->setHTMLBody($html);
$mime->addAttachment($file, 'text/plain');

$body = $mime->get();
$hdrs = $mime->headers($hdrs);

$mail =& Mail::factory('mail');
$mail->send('postmaster@localhost', $hdrs, $body);

?>
?>