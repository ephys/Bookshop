<?php
require_once('inc/error_manager.class.php');
require_once('inc/db.class.php');
session_start();

class login_manager {
	public function __construct() {
		if(isset($_POST['password']))
			echo $this->login();
	}

	private function login() {
		$bdd = Database::getInstance();
		$errors = new error_manager();

		if(!isset($_POST['username']) || !isset($_POST['password']) || empty($_POST['username']) || empty($_POST['password']))
			return $errors->display('Missing username/password');

		if(get_magic_quotes_gpc()) {
			$_POST['password'] = stripslashes($_POST['password']);
			$_POST['username'] = stripslashes($_POST['username']);
		}

		$query = $bdd->prepare('SELECT password, id, email, language FROM members WHERE username = :username');
		$query->bindParam(':username', $_POST['username'], PDO::PARAM_STR);
		$query->execute();

		if($query->rowCount() < 1) {
			$query->closeCursor();
			return $errors->display('Unregistered username');
		} else {
			$data = $query->fetch();
			$query->closeCursor();
			if(crypt($_POST['password'], $data['password']) == $data['password']) {
				$_SESSION['username'] = $_POST['username'];
				$_SESSION['email'] = $data['email'];
				$_SESSION['user_id'] = $data['id'];
				$_SESSION['language'] = $data['language'];

				return json_encode(array('error' => false));
			}
			else
				return $errors->display('Bad Login');
		}

		return $errors->display('Script faillure, you shouldn\'t see this.');
	}
}

new login_manager();