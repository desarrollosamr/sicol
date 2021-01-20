<?php
	session_start();
	session_unset();
	session_destroy();
	/* se envía al formualrio inicio sesión con una variable x=3, que le indica que
	hn cerrado la sesión */
	header('location:index.php?x=3');
?>





