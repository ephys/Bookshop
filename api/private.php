<?php
if(!isset($_GET['username']) || empty($_GET['username'])) {
	echo json_encode(array('error' => 'Missing GET username'));
	exit;
}

if(!isset($_GET['token']) || empty($_GET['token'])) {
	echo json_encode(array('error' => 'Missing GET token'));
	exit;
}

if(get_magic_quotes_gpc()) {
	stripslashes_recursive($_GET);

	function stripslashes_recursive(&$val, $key) {
		$val = stripslashes($val);
	}
}

require_once('config.inc.php');

$query = $bdd->prepare('SELECT `id`, `api_token` FROM members WHERE `username` = :username');
$query->bindParam(':username', $_GET['username'], PDO::PARAM_STR);
$query->execute();

if($query->rowCount() < 1) {
	echo json_encode(array('error' => 'Unregistered Member'));
	$query->closeCursor();
	exit;
} else {
	$user = $query->fetch();
	if(md5($_GET['token']) != $user['api_token']) {
		echo json_encode(array('error' => 'Wrong Token'));
		exit;
	}
}


// check for editing & adding
if(isset($_GET['data'])) {
	$getData = json_decode($_GET['data']);

	if(!isset($getData->title) || !isset($getData->pages) || !isset($getData->author)) {
		echo json_encode(array('error' => 'missing json encoded var title, var pages or var author in GET data'));
		exit;
	}

	if(!is_array($getData->pages)) {
		echo json_encode(array('error' => "Var pages must be an array in GET data"));
		exit;
	}

	$pages = json_encode($getData->pages);

	// check for editing
	if(isset($_GET['id'])) {
		$query = $bdd->prepare('UPDATE `books` SET `author` = :author, `title` = :title, `pages` = :pages, `date` = CURDATE() WHERE `owner` = :user_id AND `id` = :book_id');
		$query->bindParam(':user_id', $user['id'], PDO::PARAM_INT);
		$query->bindParam(':book_id', $_GET['id'], PDO::PARAM_INT);
		$query->bindParam(':author', $getData->author, PDO::PARAM_STR);
		$query->bindParam(':title', $getData->title, PDO::PARAM_STR);
		$query->bindParam(':pages', $pages, PDO::PARAM_STR);
		$query->execute();

		if($query->rowCount() < 1) {
			echo json_encode(array('error' => 'Book does not exist'));
		} else {
			echo json_encode(array('success' => true));
		}
		$query->closeCursor();
	} else {
		$query = $bdd->prepare("INSERT INTO `books`(`author`, `title`, `pages`, `date`, `owner`) VALUES(:author, :title, :pages, CURDATE(), :owner)");
		$query->bindParam(':author', $getData->author, PDO::PARAM_STR);
		$query->bindParam(':title', $getData->title, PDO::PARAM_STR);
		$query->bindParam(':pages', $pages, PDO::PARAM_STR);
		$query->bindParam(':owner', $user['id'], PDO::PARAM_INT);
		$query->execute();

		echo json_encode(array('success' => $bdd->lastinsertid())); 
	}
} elseif(isset($_GET['id'])) {
	// check for deleting
	$query = $bdd->prepare('DELETE FROM `books` WHERE `owner` = :user_id AND `id` = :book_id');
	$query->bindParam(':user_id', $user['id'], PDO::PARAM_INT);
	$query->bindParam(':book_id', $_GET['id'], PDO::PARAM_INT);
	$query->execute();
	if($query->rowCount() < 1) {
		echo json_encode(array('error' => 'Book does not exist'));
	} else {
		echo json_encode(array('success' => true));
	}
	$query->closeCursor();
} else {
	echo json_encode(array('error' => 'Missing param data or id (GET)'));
}
?>