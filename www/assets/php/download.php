<?php
$types = array('mod', 'plugin', 'mod_gui');
if(isset($_GET['ref']) && isset($_GET['mc']) && isset($_GET['type']) && in_array($_GET['type'], $types)) {
	$filename = $_GET['type'].'/bookshop_'.$_GET['ref'].'_mc_'.$_GET['mc'];
	if(isset($_GET['src']))
		$filename .= '_src';
	
	if($_GET['type'] == 'plugin')
		$filename .= '.jar';
	else
		$filename .= '.zip';

	if(file_exists("../misc/archives/".$filename)) {
		header('Content-Description: File Transfer');
		header('Content-Type: application/octet-stream');
		header('Content-Disposition: attachment; filename='.$filename); 
		header('Content-Transfer-Encoding: binary');
		header('Expires: 0');
		header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
		header('Pragma: public');
		header('Content-Length: '.filesize("../misc/archives/".$filename)); 
		ob_clean();
		flush();
		readfile("../misc/archives/".$filename);
	} 
}
?>