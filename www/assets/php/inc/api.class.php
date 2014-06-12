<?php
class API {
	public function __construct() {
		if(!class_exists('Database'))
			require_once dirname(__FILE__).'/db.class.php';
	}

	private function methodIs($type, $method) {
		if(!method_exists($this, $method))
			return false;

		$refl = new ReflectionMethod($this, $method);
		switch($type) { 
			case "static":
				return $refl->isStatic();
			case "public":
				return $refl->isPublic();
			case "private": 
				return $refl->isPrivate();
			case "protected":
				return $refl->isProtected();
			case "final":
				return $refl->isFinal();
			default:
				return false;
		} 
	}

	private function argsMatch($args, $method) {
		$refl = new ReflectionMethod($this, $method);
		
		foreach($refl->getParameters() as $arg) {
			if(!$arg->isOptional() && !array_key_exists((string)$arg->name, $args))
				return array('error_code' => 403, 'error_msg' => 'Missing parameter '.$arg->name);
		}

		return true;
	}

	private function callMethod($method, $args) {
		$reflection = new ReflectionMethod($this, $method); 

		$pass = array(); 
		foreach($reflection->getParameters() as $param) { 
			if(isset($args[$param->getName()])) 
				$pass[] = $args[$param->getName()]; 
			else 
				$pass[] = $param->getDefaultValue(); 
		} 
		return $reflection->invokeArgs($this, $pass); 
	}

	private function insertIpData($ip) {
		$bdd = Database::getInstance();
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

	public function selectMethod($data) {
		if(isset($data['method']) && $data['method'] != 'selectMethod' && $data['method'] != '__construct' && $this->methodIs("public", $data['method'])) {
			$message = $this->argsMatch($data, $data['method']);
			if($message !== true)
				return $message;
			else {
				return $this->callMethod($data['method'], $data);
			}
		} else
			return array('error_code' => 403, 'error_msg' => 'Unknown method ');
	}

	/**
	 * Returns the list of books where the author (e.g. the displayed name) 
	 * matches the username (@link $username)
	 * if the maximum number of books returned (@link $max) is less than 1, it 
	 * returns all the matching books
	 *
	 * @author  EphysPotato
	 * @version 1.0
	 * @param   String $username the books' author
	 * @param   int    $max      the amount of returned results
	 * @return  a JSON Array containing the requested books
	 */
	public function authorBooks($username, $max = -1) {
		if(empty($username))
			return array('error_code' => 403, 'error_msg' => 'Empty $username');

		$bdd = Database::getInstance();

		$cmd = 'SELECT date_format(`date`, "%d/%m/%Y") as `date`, `id`, `title` 
				FROM `books` 
				WHERE `author` = :author 
				AND `title` != ""
				AND `Pages` != "[]"
				AND `bIndexed` = 1';

		$max = intval($max);
		if($max > 0)
			$cmd .= ' LIMIT 0, :max';

		$query = $bdd->prepare($cmd);
		$query->bindParam(':author', $username, PDO::PARAM_STR);
		if($max > 0)
			$query->bindParam(':max', $max, PDO::PARAM_INT);
		$query->execute();

		$books = $query->fetchAll(PDO::FETCH_ASSOC);
		$query->closeCursor();

		return $books;
	}

	/**
	 * Returns the list of books where the book owner (as registered on 
	 * http://mcnetwork.fr.nf) matches the username (@link $username)
	 * if the maximum number of books returned (@link $max) is less than 1, it  
	 * returns all the matching books
	 *
	 * @author  EphysPotato
	 * @version 1.0
	 * @param   String $username the books' owner
	 * @param   int    $max      the amount of returned results
	 * @return  a JSON Array containing the requested books
	 */
	public function userBooks($username, $max = -1) {
		if(empty($username))
			return array('error_code' => 403, 'error_msg' => 'Empty $username');

		$bdd = Database::getInstance();

		$cmd = 'SELECT date_format(b.`date`, "%d/%m/%Y") as `date`, b.`id`, b.`title` 
				FROM `books` b
				LEFT JOIN `members` m 
				ON b.`owner` = m.`id` 
				WHERE m.`username` = :owner 
				AND `title` != ""
				AND `Pages` != "[]"
				AND `bIndexed` = 1';

		$max = intval($max);
		if($max > 0)
			$cmd .= ' LIMIT 0, :max';

		$query = $bdd->prepare($cmd);
		$query->bindParam(':owner', $username, PDO::PARAM_STR);
		if($max > 0)
			$query->bindParam(':max', $max, PDO::PARAM_INT);
		$query->execute();

		$data = $query->fetchAll(PDO::FETCH_ASSOC);
		$query->closeCursor();

		return $data;
	}

	/**
	 * Returns the authors' username matching the received partial 
	 * username (@link $needle)
	 * The maximum number of usernames returned (@link $max) has a range 
	 * of 1 to 15.
	 * 
	 * @author  EphysPotato
	 * @version 1.0
	 * @param   String $needle the partial username
	 * @param   int    $max    the amount of returned results
	 * @return  a JSON Array containing the matching usernames
	 */
	public function searchAuthor($needle, $max = 8) {
		if(empty($needle))
			return array('error_code' => 403, 'error_msg' => 'Empty $needle');

		$max = intval($max);
		if($max > 15 || $max < 1)
			return array('error_code' => 403, 'error_msg' => '$max range: 1 - 15');

		$bdd = Database::getInstance();
		$query = $bdd->prepare("SELECT DISTINCT `author` 
								FROM `books` 
								WHERE `author` REGEXP :author 
								AND `bIndexed` = 1
								ORDER BY `author` 
								LIMIT 0, :max");
		$query->bindParam(':author', $needle, PDO::PARAM_STR);
		$query->bindParam(':max', $max, PDO::PARAM_INT);
		$query->execute();
		$data = $query->fetchAll(PDO::FETCH_COLUMN);
		return $data;
	}

	/**
	 * Returns the books title matching the received partial 
	 * title (@link $needle)
	 * The maximum number of books returned (@link $max) has a range 
	 * of 1 to 15.
	 *
	 * @author  EphysPotato
	 * @version 1.0
	 * @param   String $needle the searched book title
	 * @param   int    $max    the amount of returned books
	 * @return  a JSON Array containing the matching titles
	 */
	public function searchTitle($needle, $max = 8) {
		if(empty($needle))
			return array('error_code' => 403, 'error_msg' => 'Empty $needle');

		$max = intval($max);
		if($max > 15 || $max < 1)
			return array('error_code' => 403, 'error_msg' => '$max range: 1 - 15');

		$bdd = Database::getInstance();
		$query = $bdd->prepare("SELECT DISTINCT `title`, `id` 
								FROM `books` 
								WHERE `title` REGEXP :title 
								AND `bIndexed` = 1
								ORDER BY `title` 
								LIMIT 0, :max");
		$query->bindParam(':title', $needle, PDO::PARAM_STR);
		$query->bindParam(':max', $max, PDO::PARAM_INT);
		$query->execute();
		$data = $query->fetchAll(PDO::FETCH_COLUMN);
		return $data;
	}

	/**
	 * Returns the books data whose titles matchs the received partial 
	 * title (@link $needle)
	 * The maximum number of books data returned (@link $max) has a range 
	 * of 1 to 15.
	 *
	 * @author  EphysPotato
	 * @version 1.0
	 * @param   String $title the searched book title
	 * @param   int    $max   the amount of returned books
	 * @return  a JSON Array containing the matching books title data
	 */
	public function titleList($title, $max = 10) {
		if(empty($title))
			return array('error_code' => 403, 'error_msg' => 'Empty $title');

		$max = intval($max);
		if($max > 15 || $max < 1)
			return array('error_code' => 403, 'error_msg' => '$max range: 1 - 15');

		$bdd = Database::getInstance();
		$query = $bdd->prepare('SELECT date_format(`date`, "%d/%m/%Y") as `date`, `id`, `author`, `title` 
								FROM `books` 
								WHERE `title` REGEXP :title 
								AND `bIndexed` = 1
								AND `Pages` != "[]"
								ORDER BY `author`
								LIMIT 0, :max');
		$query->bindParam(':title', $title, PDO::PARAM_STR);
		$query->bindParam(':max', $max, PDO::PARAM_INT);
		$query->execute();

		$data = $query->fetchAll(PDO::FETCH_ASSOC);
		$query->closeCursor();

		return $data;
	}

	/**
	 * Returns the lastest edited books
	 * The maximum number of books returned (@link $max) has a range of 1 to 15.
	 *
	 * @author  EphysPotato
	 * @version 1.0
	 * @param   int $max the amount of returned books
	 * @return  a JSON Array containing the books as JSONObjects
	 */
	public function lastestBooks($max = 10) {
		$max = intval($max);
		if($max > 15 || $max < 1)
			return array('error_code' => 403, 'error_msg' => '$max range: 1 - 15');

		$bdd = Database::getInstance();
		$query = $bdd->prepare('SELECT `date` as `dateOrder`, date_format(`date`, "%d/%m/%Y") as `date`, `id`, `author`, `title` 
								FROM `books` 
								WHERE `bIndexed` = 1
								AND `title` != ""
								AND `Pages` != "[]"
								ORDER BY `dateOrder` DESC
								LIMIT 0, :max');
		$query->bindParam(':max', $max, PDO::PARAM_INT);
		$query->execute();

		$data = array();
		while($book = $query->fetch(PDO::FETCH_ASSOC)) {
			$data[] = $book;
		}
		$query->closeCursor();

		return $data;
	}

	/**
	 * Returns the best rated books
	 * The maximum number of books returned (@link $max) has a range of 1 to 15.
	 *
	 * @author  EphysPotato
	 * @version 1.0
	 * @param   int $max the amount of returned books
	 * @return  a JSON Array containing the books as JSONObjects
	 */
	public function bestBooks($max = 10) {
		$max = intval($max);
		if($max > 15 || $max < 1)
			return array('error_code' => 403, 'error_msg' => '$max range: 1 - 15');

		$bdd = Database::getInstance();
		$query = $bdd->prepare('SELECT `rate_positive`, `rate_negative`, date_format(`date`, "%d/%m/%Y") as `date`, `id`, `author`, `title` 
								FROM `books` 
								WHERE `bIndexed` = 1
								AND `title` != ""
								AND `Pages` != "[]"
								ORDER BY (`rate_positive`-`rate_negative`) DESC
								LIMIT 0, :max');
		$query->bindParam(':max', $max, PDO::PARAM_INT);
		$query->execute();

		$data = array();
		while($book = $query->fetch(PDO::FETCH_ASSOC)) {
			$data[] = $book;
		}
		$query->closeCursor();

		return $data;
	}

	/**
	 * Returns a random selection of books
	 * The maximum amount of books returned (@link $max) has a range of 1 to 15.
	 *
	 * @author  EphysPotato
	 * @version 1.0
	 * @param   int $max the amount of returned books
	 * @return  a JSON Array containing the books as JSONObjects
	 */
	public function randomBooks($max = 10) {
		$max = intval($max);
		if($max > 15 || $max < 1)
			return array('error_code' => 403, 'error_msg' => '$max range: 1 - 15');

		$bdd = Database::getInstance();
		$query = $bdd->prepare('SELECT DISTINCT `id`, date_format(`date`, "%d/%m/%Y") as `date`, `author`, `title` 
								FROM `books` 
								WHERE `bIndexed` = 1
								AND `title` != ""
								AND `Pages` != "[]"
								ORDER BY Rand()
								LIMIT :max');
		$query->bindParam(':max', $max, PDO::PARAM_INT);
		$query->execute();

		$data = array();
		while($book = $query->fetch(PDO::FETCH_ASSOC)) {
			$data[] = $book;
		}
		$query->closeCursor();

		return $data;
	}

	/**
	 * Returns the book data associated with an id (@link $id)
	 *
	 * @author  EphysPotato
	 * @version 1.0
	 * @param   int $id the id of the book
	 * @return  a JSON Object containing the book data
	 */
	public function loadBook($id) {
		if(empty($id))
			return array('error_code' => 403, 'error_msg' => 'Empty $id');

		$bdd = Database::getInstance();
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

		$query = $bdd->prepare('SELECT date_format(`date`, "%d/%m/%Y") as `date`, `id`, `title`, `pages`, `author`, `rate_positive`, `rate_negative` FROM `books` WHERE `id` = :id AND `bPublic` = 1');
		$query->bindParam(':id', $id, PDO::PARAM_INT);
		$query->execute();
		$book = $query->fetch(PDO::FETCH_ASSOC);
		$query->closeCursor();

		$book['pages'] = json_decode($book['pages']);
		return $book;
	}

	public function vote($id, $isPositive = true) {
		if(empty($id))
			return array('error_code' => 403, 'error_msg' => 'Empty $id');

		$isPositive = (bool)$isPositive;

		$bdd = Database::getInstance();
		$ip = ip2long($_SERVER['REMOTE_ADDR']);
		$query = $bdd->prepare('SELECT 1 FROM `books_ranking` WHERE `user_ip` = ? AND `book_id` = ?');
		$query->execute(array($ip, $id));
		$rows = $query->rowCount();
		$query->closeCursor();

		if($rows != 0) // si aucune ligne, c'est que ça n'a pas été encore voté
			return array('error_code' => 403, 'error_msg' => 'Already voted');

		$query = $bdd->prepare('INSERT INTO `books_ranking`(`user_ip`, `book_id`, `isPositive`) VALUES(:ip, :id, :vote)');
		$query->bindParam(':ip', $ip, PDO::PARAM_INT);
		$query->bindParam(':id', $id, PDO::PARAM_INT);
		$query->bindParam(':vote', $isPositive, PDO::PARAM_BOOL);
		$query->execute();
		$query->closeCursor();

		if($isPositive == true)
			$query = $bdd->prepare('UPDATE `books` SET `rate_positive` = `rate_positive`+1 WHERE `id` = :id');
		else
			$query = $bdd->prepare('UPDATE `books` SET `rate_negative` = `rate_negative`-1 WHERE `id` = :id');
		$query->bindParam(':id', $id, PDO::PARAM_INT);
		$query->execute();
		$query->closeCursor();

		$this->insertIpData($ip);
	}

	private function checkCredentials($username, $token) {
		$bdd = Database::getInstance();
		$query = $bdd->prepare('SELECT `id`, `api_token` FROM `members` WHERE `username` = :username');
		$query->bindParam(':username', $username, PDO::PARAM_STR);
		$query->execute();
		$user = $query->fetch();
		$rowCount = $query->rowCount();

		if($rowCount === 0) {
			return 'Unregistered Member';
		} elseif($token != $user['api_token']) {
			return 'Wrong Token';
		}

		return intval($user['id']);
	}

	private function parseJSONBook($book) {
		$book = json_decode($book);

		if(!isset($book->title) || !isset($book->pages) || !isset($book->author)) {
			return 'missing variable \'title\', \'pages\' or \'author\' in $book';
		}

		if(!is_array($book->pages)) {
			return 'Var \'pages\' must be an array in $book';
		}

		$book->pages = json_encode($book->pages);

		return $book;
	}

	/**
	 * Upload a book (@link $book) on an user (@link $username)'s account
	 *
	 * @author  EphysPotato
	 * @version 1.0
	 * @param   String     $username the username of a MCNetwork account
	 * @param   String     $token    the api key associated with $username
	 * @param   JSONObject $book     a JSONObject composed of
	 *  - 'title': the book title
	 *  - 'author': the book author
	 *  - 'pages': an array of strings, one element of the array equals one page
	 * @return  a JSON Object containing the book id
	 */
	public function uploadBook($username, $token, $book) {
		$user_id = $this->checkCredentials($username, $token);
		if(gettype($user_id) !== 'integer')
			return array('error_code' => 403, 'error_msg' => $user_id);

		$book = $this->parseJSONBook($book);
		if(!is_object($book))
			return array('error_code' => 403, 'error_msg' => $book);

		$bdd = Database::getInstance();
		$query = $bdd->prepare("INSERT INTO `books`(`author`, `title`, `pages`, `date`, `owner`) VALUES(:author, :title, :pages, CURDATE(), :owner)");
		$query->bindParam(':author', $book->author, PDO::PARAM_STR);
		$query->bindParam(':title', $book->title, PDO::PARAM_STR);
		$query->bindParam(':pages', $book->pages, PDO::PARAM_STR);
		$query->bindParam(':owner', $user_id, PDO::PARAM_INT);
		$query->execute();

		return array('book' => $bdd->lastinsertid()); 
	}

	/**
	 * Edit a book (@link $book) on an user (@link $username)'s account
	 *
	 * @author  EphysPotato
	 * @version 1.0
	 * @param   String     $username the username of a MCNetwork account
	 * @param   String     $token    the api key associated with $username
	 * @param   int        $id       the edited book id
	 * @param   JSONObject $book     a JSONObject composed of
	 *  - 'title': the book title
	 *  - 'author': the book author
	 *  - 'pages': an array of strings, one element of the array equals one page
	 * @return  a JSON Object containing the book id
	 */
	public function editBook($username, $token, $book, $id) {
		$user_id = $this->checkCredentials($username, $token);
		if(gettype($user_id) !== 'integer')
			return array('error_code' => 403, 'error_msg' => $user_id);

		$book = $this->parseJSONBook($book);
		if(!is_array($book))
			return array('error_code' => 403, 'error_msg' => $book);

		$bdd = Database::getInstance();
		$query = $bdd->prepare('UPDATE `books` SET `author` = :author, `title` = :title, `pages` = :pages, `date` = CURDATE() WHERE `owner` = :user_id AND `id` = :book_id');
		$query->bindParam(':user_id', $user_id, PDO::PARAM_INT);
		$query->bindParam(':book_id', $id, PDO::PARAM_INT);
		$query->bindParam(':author', $book->author, PDO::PARAM_STR);
		$query->bindParam(':title', $book->title, PDO::PARAM_STR);
		$query->bindParam(':pages', $book->pages, PDO::PARAM_STR);
		$query->execute();
		$rowCount = $query->rowCount();
		$query->closeCursor();

		return ($rowCount !== 1)?array('error_code' => '403', 'error_msg' => 'Book does not exist'):array('book' => $id);	
	}

	/**
	 * Delete a book (@link $id) on an user (@link $username)'s account
	 *
	 * @author  EphysPotato
	 * @version 1.0
	 * @param   String     $username the username of a MCNetwork account
	 * @param   String     $token    the api key associated with $username
	 * @param   int        $id       the edited book id
	 * @return  a JSON Object containing the book id
	 */
	public function deleteBook($username, $token, $id) {
		$user_id = $this->checkCredentials($username, $token);
		if(gettype($user_id) !== 'integer')
			return array('error_code' => 403, 'error_msg' => $user_id);

		$bdd = Database::getInstance();
		$query = $bdd->prepare('DELETE FROM `books` WHERE `owner` = :user_id AND `id` = :book_id');
		$query->bindParam(':user_id', $user_id, PDO::PARAM_INT);
		$query->bindParam(':book_id', $id, PDO::PARAM_INT);
		$query->execute();
		$rowCount = $query->rowCount();
		$query->closeCursor();

		return ($rowCount !== 1)?array('error_code' => '403', 'error_msg' => 'Book does not exist'):array('deleted' => true);
	}
}
?>