<?php 
//Importamos la url del index.php 
if(isset($_POST['web'])) 
//metemos la url en una bariable 
$url="$_POST[web]"; 
//abrimos la url y que la lea que contiene 
$fo= fopen("$url","r") or die ("No se encuentra la pagina."); 
while (!feof($fo)) { 
    $cadena .= fgets($fo, 4096); 
} 
fclose ($fo); 
//inprmimos el codigo 
print("<textarea name='area' cols='100%' rows='100%'>$cadena"); 
?>