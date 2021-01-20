<?php 

// Report all errors
error_reporting(E_ALL);
$dbName = "u280193113inter";
include"../clases/ConexionDatos.php";
$objConexion=Conectarse();
if (! mysqli_set_charset ($objConexion, "UTF_8"))
{
    mysqli_query($objConexion, "SET NAMES utf-8");
}

$tables = array();
$result = mysqli_query($objConexion, "show tables");
while($row = mysqli_fetch_row($result))
{
    $tables[] = $row[0];
}


$sql = "CREATE DATABASE IF NOT EXISTS ".$dbName.";\n\n";
$sql .= "USE ".$dbName.";\n\n";

/**
* Iterate tables
*/
foreach($tables as $table)
{
echo "Respaldando la tabla: ".$table."...<br>";

$result = mysqli_query($objConexion, "SELECT * FROM ".$table);
$numFields = mysqli_num_fields($result);

$sql .= "DROP TABLE IF EXISTS ".$table.";";
$row2 = mysqli_fetch_row(mysqli_query($objConexion, "SHOW CREATE TABLE ".$table));
$sql.= "\n\n".$row2[1].";\n\n";

for ($i = 0; $i < $numFields; $i++) 
{
    while($row = mysqli_fetch_row($result))
    {
        $sql .= 'INSERT INTO '.$table.' VALUES(';
        for($j=0; $j<$numFields; $j++) 
        {
            $row[$j] = addslashes($row[$j]);
            $row[$j] = preg_replace("/\n/","\\n",$row[$j]);
            if (isset($row[$j]))
            {
                $sql .= '"'.$row[$j].'"' ;
            }
            else
            {
                $sql.= '""';
            }

            if ($j < ($numFields-1))
            {
                $sql .= ',';
            }
        }

        $sql.= ");\n";
    }
}
}
$sql.="\n\n\n";
echo " OK";
$handle = fopen("/home/ilogistica2017/public_html/intranet/backup/db-backup-".$dbName."-".date("Ymd-His", time()).".sql","w+");
fwrite($handle, $sql);
fclose($handle);
?>
