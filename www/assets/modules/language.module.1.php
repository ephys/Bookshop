<?php
require_once(ROOT.'assets'.DS.self::DIR_SCRIPTS.DS.'inc/db.class.php');
session_start();

class Language {
	private static $language;
	private static $_TRANSLATIONS;

	public  static $language_list = array();

	public function __construct() {
		trigger_error("Language cannot be instancied");
	}

	# update the user's language preference if he decided to change it
	private static function updateLanguage() {
		if(isset($_SESSION['user_id']) && isset($_GET['l']) && ($_GET['l'] != $_SESSION['language'])) {
			$bdd = Database::getInstance();
			$query = $bdd->prepare('UPDATE `members` SET `language` = :language WHERE `id` = :id');
			$query->bindParam(':language', $_GET['l'], PDO::PARAM_STR);
			$query->bindParam(':id', $_SESSION['user_id'], PDO::PARAM_INT);
			$query->execute();
			$query->closeCursor();
		}

		self::setLanguage($_SESSION['language'] = isset($_GET['l'])?$_GET['l']:(isset($_SESSION['language'])?$_SESSION['language']:'fr_FR'));
	}

	# load a language translations file and store it
	private static function loadTranslations() {
		if(file_exists(ROOT.'assets'.DS.'php/language/'.self::$language.'.lang.php')) {
			require_once(ROOT.'assets'.DS.'php/language/'.self::$language.'.lang.php');
			self::$_TRANSLATIONS = $_LANGUAGE;
		} else
			throw new Exception('Can\'t find requested language '.self::$language);
	}

	# load the list of available languages
	private static function loadLanguageList() {
		foreach (glob(ROOT.'assets/php/language/*.lang.php', GLOB_ERR) as $filename) {
			$file = explode('.', basename($filename));
			array_push(self::$language_list, $file[0]);
		}
	}

	# returns current website language
	public static function getLanguage() {
		self::init();
		return self::$language;
	}

	# set the current website language
	public static function setLanguage($lang) {
		self::$language = $lang;
		self::loadTranslations();
	}

	# returns the translation associed with $key in the current language
	public static function translate($key) {
		self::init();

		if(array_key_exists($key, self::$_TRANSLATIONS))
			return self::$_TRANSLATIONS[$key];
		else
			throw new Exception('Can\'t find requested translation '.$key.' in '.self::$language);
	}

	private static function init() {
		if(self::$language !== null)
			return;

		self::updateLanguage();
		self::loadLanguageList();
	}
}
?>