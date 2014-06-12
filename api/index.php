<?php
if(isset($_SERVER['HTTP_ORIGIN'])) {
	$allowed_origins = array('http://bookshop.fr.nf', 'http://bookshop');
	if(in_array($_SERVER['HTTP_ORIGIN'], $allowed_origins))
		header("Access-Control-Allow-Origin: *");
	header("Access-Control-Allow-Methods: GET,POST,OPTIONS", true);
	header("Access-Control-Allow-Headers: x-requested-with", true);
}

if($_SERVER['REQUEST_METHOD'] !== 'OPTIONS') {
	require_once '../www/assets/php/inc/api.class.php';
	if(get_magic_quotes_gpc()) {
		array_walk_recursive($_REQUEST, 'stripslashes_gpc');
	}

	function stripslashes_gpc(&$input, $key) {
		$input = stripslashes($input);
	}

	$api = new API();
	echo json_encode($api->selectMethod($_REQUEST));
}
?>