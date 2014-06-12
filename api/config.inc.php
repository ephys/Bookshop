<?php
$pdo_options[PDO::ATTR_ERRMODE] = PDO::ERRMODE_EXCEPTION;

if($_SERVER["HTTP_HOST"] == "localhost" || $_SERVER["HTTP_HOST"] == "127.0.0.1") {
	$mysql_host = 'localhost';
	$mysql_database = 'bookshop';
	$mysql_user = 'root';
	$mysql_password = '';
} else {
	$mysql_host = '';
	$mysql_database = '';
	$mysql_user = '';
	$mysql_password = '';
}

try {
	$bdd = new PDO('mysql:host='.$mysql_host.';dbname='.$mysql_database, $mysql_user, $mysql_password, $pdo_options);
	$bdd->exec("SET NAMES 'utf8'");
} catch(PDOException $e) {
	echo json_encode(array('error' => $e->getMessage()));
}
?>