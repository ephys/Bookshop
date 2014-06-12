<?php
require_once('inc/db.class.php');
session_start();
session_destroy();
unset($_SESSION);

header('location: '.$_SERVER["HTTP_REFERER"]);
?>