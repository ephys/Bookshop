<?php
require_once('inc/db.class.php');
session_start();

if(!@include_once('language/'.$_SESSION['language'].'.lang.php'))
	include_once('language/en_EN.lang.php');

class uploader {
	private function loadData($file = null) {
		if($file == null)
			$data[] = array('author' => $_SESSION['username'], 'title' => '', 'pages' => "[]");
		else {
			require_once('inc/fileuploader.inc.php');
			require_once('inc/bookLoader.inc.php');
			$uploader = new qqFileUploader(array('dat'), 1048576);
			if($uploader->file->getType() != "dat")
				return array('error' => true, 0 => $_LANGUAGE['ERROR_UPLOAD_UNSUPORTED_FORMAT']);
			$bookManager = new BookLoader($uploader->file->getRessource());

			foreach($bookManager->getBooks(387) as $book) {
				$data[] = array('author' => $book['author'], 'title' => $book['title'], 'pages' => json_encode($book['pages']));
			}
			foreach($bookManager->getBooks(386) as $book) {
				$data[] = array('author' => '', 'title' => "", 'pages' => json_encode($book['pages']));
			}
		}
		return $data;
	}

	private function upload($data) {
		$bdd = Database::getInstance();
		$query = $bdd->prepare("INSERT INTO books(`author`, `title`, `pages`, `date`, `owner`, `bIndexed`, `bPublic`) 
								VALUES(:author, :title, :pages, CURDATE(), :owner, false, false)");
		// $query->bindParam(':owner', $_SESSION['user_id'], PDO::PARAM_INT);
		foreach($data as &$book)
		{
			$query->execute(array('owner' => $_SESSION['user_id'],
									'author' => $book['author'],
									'title' => $book['title'],
									'pages' => $book['pages']));
			$book['id'] = $bdd->lastinsertid();
			$book['bIndexed'] = false;
			$book['bPublic'] = false;
			$book['date'] = date('d/m/Y');
		}
		$query->closeCursor();

		return $data;
	}

	public function __construct() {
		if(!isset($_SESSION['username']))
			return;

		if(isset($_POST['emptyBook']))
			$bookData = $this->loadData();
		elseif(isset($_GET['qqfile']))
			$bookData = $this->loadData($_GET['qqfile']);
		else
			return;

		if(isset($bookData['error'])) {
			echo json_encode($bookData);
		} else {
			echo json_encode($this->upload($bookData));
		}
	}
}

$uploader = new uploader();
?>