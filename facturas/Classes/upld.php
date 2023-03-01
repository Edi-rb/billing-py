<?php
include_once("/var/www/api/antisql.php");
include ("/var/www/api/mtools.php");
//instancia con el nombre de la variable de subida
try {
	$tools = new MtcTools('file');
} catch (Exception $e) {
	echo $e;	
}
$_FILES["temp"]["name"] = $_POST['ruta'].$_FILES["file"]["name"];
//se sube el archivo con el nombre asignado
$res=$tools->upload($_FILES["temp"]["name"]);
// devuelve booleano
echo $res;
//
//print_r();
?>
