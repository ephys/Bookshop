<?php
require_once('inc/db.class.php');
session_start();

class book_editor {
	const DELETE = 0;
	const LOAD = 1;
	const SAVE = 2;
	const LOADLIST = 3;
	const STATS = 4;

	public function loadBookList($user_id) {
		if(empty($user_id))
			return false;

		$bdd = Database::getInstance();
		$query = $bdd->prepare("SELECT `date` as `dateOrder`, `bPublic`, `bIndexed`, `id`, `title`, `author`, date_format(`date`, '%d/%m/%Y') as `date` 
								FROM `books` 
								WHERE `owner` = :user_id
								ORDER BY `dateOrder`");
		$query->bindParam(':user_id', $user_id, PDO::PARAM_INT);
		$query->execute();
		return json_encode($query->fetchAll(PDO::FETCH_ASSOC));
	}

	public function loadBook_stats($user_id, $book_id) {
		$bdd = Database::getInstance();
		$query = $bdd->prepare('SELECT b.`title`, b.`view`, b.`downloads`, b.`rate_positive`, b.`rate_negative` 
								FROM `books` b
								WHERE b.`id` = :id AND b.`owner` = :owner');
		$query->bindParam(':id', $book_id, PDO::PARAM_INT);
		$query->bindParam(':owner', $user_id, PDO::PARAM_INT);
		$query->execute();

		if($query->rowCount() != 1) {
			$data['books'] = $query->fetchAll();
			$data['error'] = true;
			$data[0] = 'less or more than 1 entry.';
			$data['owner'] = $user_id;
			$data['id'] = $book_id;
			return json_encode($data);
		}
		else {
			$data = $query->fetch(PDO::FETCH_ASSOC);
			$query->closeCursor();
			$query = $bdd->prepare('SELECT ip.`country` as name, COUNT(ip.`country`) as count
									FROM `ip_data` ip
									LEFT JOIN `books_views` b
									ON ip.`ip` = b.`user_ip`
									WHERE b.`book_id` = :id AND b.`isAPI` = 0
									GROUP BY ip.`country`');
			$query->bindParam(':id', $book_id, PDO::PARAM_INT);
			$query->execute();
			$data['countries'] = $query->fetchAll(PDO::FETCH_ASSOC);
		}
		$query->closeCursor();
		return json_encode($data);
	}

	public function loadBook($user_id, $book_id) {
		$bdd = Database::getInstance();
		$query = $bdd->prepare('SELECT `title`, `pages`, `author`, `bPublic`, `bIndexed`, `nsfw`, `language` FROM `books` WHERE `id` = :id AND `owner` = :owner');
		$query->bindParam(':id', $book_id, PDO::PARAM_INT);
		$query->bindParam(':owner', $user_id, PDO::PARAM_INT);
		$query->execute();

		if($query->rowCount() != 1) {
			$data['books'] = $query->fetchAll();
			$data['error'] = true;
			$data[0] = 'less or more than 1 entry.';
			$data['owner'] = $user_id;
			$data['id'] = $book_id;
			return json_encode($data);
		}
		else
			$book = $query->fetch(PDO::FETCH_ASSOC);
		$book['pages'] = json_decode($book['pages']);
		$query->closeCursor();
		return json_encode($book);
	}

	public function deleteBook($user_id, $book_id) {
		if(empty($user_id) || empty($book_id))
			return false;

		$bdd = Database::getInstance();
		$query = $bdd->prepare('DELETE FROM `books` WHERE `id` = :id AND `owner` = :owner');
		$query->bindParam(':id', $book_id, PDO::PARAM_INT);
		$query->bindParam(':owner', $user_id, PDO::PARAM_INT);
		$query->execute();
		$return = $query->rowCount();
		$query->closeCursor();
		return $return;
	}

	public function editBook($user_id, $book_id, $book_data, $username = null) {
		if(empty($user_id) || empty($book_id) || empty($book_data))
			return false;

		if(!isset($book_data['name']) || !isset($book_data['data']) || !isset($book_data['security']) || !isset($book_data['author']))
			return false;

		if(!clean_array($book_data['data']))
			return false;

		if(empty($book_data['author']) && $username !== null)
			$book_data['author'] = $username;

		$book_data['data'] = json_encode($book_data['data']);

		// default: public (2)
		$public = 1;
		$indexed = 1;

		switch($book_data['security']) {
			case 0: // 0: unindexed
				$indexed = 0;
				break;
			case 1: // 1: private
				$public = 0;
				$indexed = 0;
		}

		$bdd = Database::getInstance();
		$query = $bdd->prepare('UPDATE `books` SET `date` = CURDATE(), `title` = :title, `pages` = :pages, `author` = :author, `bPublic` = :bPublic, `bIndexed` = :bIndexed, `nsfw` = :nsfw, `language` = :language WHERE `id` = :id AND `owner` = :owner');
		$query->bindParam(':title', $book_data['name'], PDO::PARAM_STR);
		$query->bindParam(':pages', $book_data['data'], PDO::PARAM_STR);
		$query->bindParam(':author', $book_data['author'], PDO::PARAM_STR);
		$query->bindParam(':id', $book_id, PDO::PARAM_INT);
		$query->bindParam(':owner', $user_id, PDO::PARAM_INT);
		$query->bindParam(':bPublic', $public, PDO::PARAM_BOOL);
		$query->bindParam(':bIndexed', $indexed, PDO::PARAM_BOOL);
		$query->bindParam(':nsfw', $indexed, PDO::PARAM_BOOL);
		$query->bindParam(':language', $indexed, PDO::PARAM_STR);

		$query->execute();
		$return = $query->rowCount();
		$query->closeCursor();
		return $return;
	}
}

if(get_magic_quotes_gpc()) {
	array_walk_recursive($_POST, 'stripslashes_gpc');
}

function stripslashes_gpc(&$input, $key) {
	$input = stripslashes($input);
}

$bookManager = new book_editor();

$return = json_encode(array("error" => true, 0 => "403: Bad Request"));

if(isset($_POST['action']) && isset($_SESSION['user_id']))
{
	switch($_POST['action']) {
		case book_editor::DELETE:
			if(!isset($_POST['id']))
				break;
			$return = $bookManager->deleteBook($_SESSION['user_id'], $_POST['id']);
			break;
		case book_editor::SAVE:
			print_r($_POST);
			if(!isset($_POST['id']) || !isset($_POST['title']) || !isset($_POST['pages']) || !isset($_POST['security']) || !isset($_POST['author']))
				break;
			$bookData = array('name' => $_POST['title'], 
								'data' => $_POST['pages'], 
								'security' => $_POST['security'],
								'author' => $_POST['author']);
			$return = $bookManager->editBook($_SESSION['user_id'], $_POST['id'], $bookData, $_SESSION['username']);
			break;
		case book_editor::LOADLIST:
			$return = $bookManager->loadBookList($_SESSION['user_id']);
			break;
		case book_editor::LOAD:
			if(!isset($_POST['id']))
				break;
			$return = $bookManager->loadBook($_SESSION['user_id'], $_POST['id']);
			break;
		case book_editor::STATS:
			if(!isset($_POST['id']))
				break;
			$return = $bookManager->loadBook_stats($_SESSION['user_id'], $_POST['id']);
			break;
	}
}

echo $return;

function clean_array(&$array) {
	if(!is_array($array))
		return false;

	foreach($array as $key => $value) {
		if(empty($value))
			unset($array[$key]);
	}

	return true;
}
?>