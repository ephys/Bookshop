<?php
require_once('config.inc.php');
header('HTTP/1.1 418 I\'m a teapot');

class profile {
	public function loadbookList_author($username) {
		if(empty($username))
			return false;

		global $bdd;
		$query = $bdd->prepare('SELECT date_format(`date`, "%d/%m/%Y") as `date`, `id`, `title` 
								FROM `books` 
								WHERE `author` = :author 
								AND `title` != ""
								AND `Pages` != "[]"
								AND `bIndexed` = 1');
		$query->bindParam(':author', $username, PDO::PARAM_STR);
		$query->execute();

		$data = array('type' => 0, 'author' => $username);
		while($book = $query->fetch(PDO::FETCH_ASSOC)) {
			$data[] = $book;
		}
		$query->closeCursor();

		return json_encode($data);
	}

	private function insertIpData($ip) {
		global $bdd;
		try {
			$query = $bdd->prepare('SELECT `ip` FROM `ip_data` WHERE `ip` = :ip');
			$query->bindParam(':ip', $ip, PDO::PARAM_INT);
			$query->execute();
			$rows = $query->rowCount();
			$query->closeCursor();

			if($rows == 0) {
				$user_data = json_decode(file_get_contents("http://api.hostip.info/get_json.php?ip=".$_SERVER['REMOTE_ADDR']));
				$query = $bdd->prepare('INSERT INTO `ip_data`(`ip`, `country`, `city`) VALUES(:ip, :country, :city)');
				$query->bindParam(':ip', $ip, PDO::PARAM_INT);
				$query->bindParam(':country', $user_data->country_name, PDO::PARAM_STR);
				$query->bindParam(':city', $user_data->city, PDO::PARAM_STR);
				$query->execute();
				$query->closeCursor();
			}
		} catch(Exception $e) {
			
		}
	}

	public function loadbookList_title($title) {
		if(empty($title))
			return false;

		global $bdd;
		$query = $bdd->prepare('SELECT date_format(`date`, "%d/%m/%Y") as `date`, `id`, `author`, `title` 
								FROM `books` 
								WHERE `title` REGEXP :title 
								AND `bIndexed` = 1
								AND `Pages` != "[]"
								ORDER BY `author`
								LIMIT 0, 10');
		$query->bindParam(':title', $title, PDO::PARAM_STR);
		$query->execute();

		$data = array('type' => 1, 'title' => $title);
		while($book = $query->fetch(PDO::FETCH_ASSOC)) {
			$data[] = $book;
		}
		$query->closeCursor();

		return json_encode($data);
	}

	public function loadbookList_lastest() {
		global $bdd;
		$query = $bdd->prepare('SELECT `date` as `dateOrder`, date_format(`date`, "%d/%m/%Y") as `date`, `id`, `author`, `title` 
								FROM `books` 
								WHERE `bIndexed` = 1
								AND `title` != ""
								AND `Pages` != "[]"
								ORDER BY `dateOrder` DESC
								LIMIT 0, 10');
		$query->bindParam(':title', $title, PDO::PARAM_STR);
		$query->execute();

		$data = array('type' => 2);
		while($book = $query->fetch(PDO::FETCH_ASSOC)) {
			$data[] = $book;
		}
		$query->closeCursor();

		return json_encode($data);
	}

	public function loadbookList_best() {
		global $bdd;
		$query = $bdd->prepare('SELECT `rate_positive`, `rate_negative`, date_format(`date`, "%d/%m/%Y") as `date`, `id`, `author`, `title` 
								FROM `books` 
								WHERE `bIndexed` = 1
								AND `title` != ""
								AND `Pages` != "[]"
								ORDER BY (`rate_positive`-`rate_negative`) DESC
								LIMIT 0, 10');
		$query->bindParam(':title', $title, PDO::PARAM_STR);
		$query->execute();

		$data = array('type' => 2);
		while($book = $query->fetch(PDO::FETCH_ASSOC)) {
			$data[] = $book;
		}
		$query->closeCursor();

		return json_encode($data);
	}

	public function loadbookList_random() {
		global $bdd;
		$query = $bdd->prepare('SELECT DISTINCT `id`, date_format(`date`, "%d/%m/%Y") as `date`, `author`, `title` 
								FROM `books` 
								WHERE `bIndexed` = 1
								AND `title` != ""
								AND `Pages` != "[]"
								ORDER BY Rand()
								LIMIT 10');
		$query->bindParam(':title', $title, PDO::PARAM_STR);
		$query->execute();

		$data = array('type' => 2);
		while($book = $query->fetch(PDO::FETCH_ASSOC)) {
			$data[] = $book;
		}
		$query->closeCursor();

		return json_encode($data);
	}

	public function searchProfile($search) {
		if(empty($search))
			return false;

		global $bdd;
		$query = $bdd->prepare("SELECT DISTINCT `author` 
								FROM `books` 
								WHERE `author` REGEXP :author 
								AND `bIndexed` = 1
								ORDER BY `author` 
								LIMIT 0, 8");
		$query->bindParam(':author', $search, PDO::PARAM_STR);
		$query->execute();
		$data = $query->fetchAll(PDO::FETCH_COLUMN);
		return json_encode($data);
	}

	public function searchTitle($search) {
		if(empty($search))
			return false;

		global $bdd;
		$query = $bdd->prepare("SELECT DISTINCT `title`, `id` 
								FROM `books` 
								WHERE `title` REGEXP :title 
								AND `bIndexed` = 1
								ORDER BY `title` 
								LIMIT 0, 8");
		$query->bindParam(':title', $search, PDO::PARAM_STR);
		$query->execute();
		$data = $query->fetchAll(PDO::FETCH_COLUMN);
		return json_encode($data);
	}

	public function loadbook_id($id) {
		if(empty($id))
			return false;

		global $bdd;
		$ip = ip2long($_SERVER['REMOTE_ADDR']);
		$query = $bdd->prepare('INSERT INTO `books_views`(`user_ip`, `book_id`, `isAPI`)
								SELECT * FROM (SELECT :ip, :id, 1) AS tmp
								WHERE NOT EXISTS (
		    						SELECT `book_id` FROM `books_views` WHERE `isAPI` = 1 AND `user_ip` = :ip AND `book_id` = :id
								) LIMIT 1');
		$query->bindParam(':ip', $ip, PDO::PARAM_INT);
		$query->bindParam(':id', $id, PDO::PARAM_INT);
		$query->execute();
		$rows = $query->rowCount();
		$query->closeCursor();
		$this->insertIpData($ip);

		if($rows != 0) {
			$query = $bdd->prepare('UPDATE `books` SET `view` = `view`+1 WHERE `id` = :id');
			$query->bindParam(':id', $id, PDO::PARAM_INT);
			$query->execute();
			$query->closeCursor();
		}

		$query = $bdd->prepare('SELECT date_format(`date`, "%d/%m/%Y") as `date`, `id`, `title`, `pages`, `author` FROM `books` WHERE `id` = :id AND `bPublic` = 1');
		$query->bindParam(':id', $id, PDO::PARAM_INT);
		$query->execute();
		$book = $query->fetch(PDO::FETCH_ASSOC);
		$query->closeCursor();

		$book['pages'] = json_decode($book['pages']);
		return json_encode($book);
	}
}

$profile = new profile();

if(get_magic_quotes_gpc()) {
	array_walk_recursive($_GET, 'stripslashes_gpc');
}

function stripslashes_gpc(&$input, $key) {
	$input = stripslashes($input);
}

$data = json_encode(array('error' => '403: Bad request', "query" => $_GET));
if(isset($_GET['user']) && !empty($_GET['user'])) {
	$data = $profile->loadbookList_author($_GET['user']);
} elseif(isset($_GET['title']) && !empty($_GET['title'])) {
	$data = $profile->loadbookList_title($_GET['title']);
} elseif(isset($_GET['lastest'])) {
	$data = $profile->loadbookList_lastest();
}  elseif(isset($_GET['id']) && !empty($_GET['id'])) {
	$data = $profile->loadbook_id($_GET['id']);
} elseif(isset($_GET['search_u'])) {
	$data = $profile->searchProfile($_GET['search_u']);
} elseif(isset($_GET['search_t'])) {
	$data = $profile->searchTitle($_GET['search_t']);
} elseif(isset($_GET['best'])) {
	$data = $profile->loadbookList_best();
} elseif(isset($_GET['random'])) {
	$data = $profile->loadbookList_random();
}

echo $data;