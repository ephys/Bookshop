<?php
namespace EphysCMS;

class Router {
	const CMS_VERSION = 2.5;
	const SITE_LOCATION = null;
	const DEFAULT_PAGE = 'about';
	const DEV_MODE = true;

	const DIR_MODULES = 'modules';
	const DIR_TEMPLATES = 'templates';
	const DIR_SCRIPTS = 'php';

	private static $page;

	private $subdomains = array('api');

	public $url;

	//response code, setting default to 200
	private static $response_code = 200;

	public function __construct($url) {
		if(self::DEV_MODE)
			ini_set('display_errors', 'on');
		else
			ini_set('display_errors', 'off');

		if(substr($url, -1) != '/') {
			header('Location: '.$url.'/');
			exit;
		}

		$this->url = $this->parseURI($url);
		self::$page = (isset($this->url[1]) && !empty($this->url[1]))?$this->url[1]:self::DEFAULT_PAGE;

		$_GET = $this->extractGETFromURL($this->url);

		if(isset($_SERVER['REDIRECT_QUERY_STRING']))
			$_GET = array_merge(parse_url($_SERVER['REDIRECT_QUERY_STRING']), $_GET);

		if(!self::DEV_MODE) {
			ob_start("self::sanitize_output");
			header('Content-Encoding: gzip');
		} else {
			ob_start();
		}

		if(!defined('DS'))
			define('DS', DIRECTORY_SEPARATOR);
		define('ROOT', dirname(__FILE__).DS);

		$this->loadModules();
		self::setResponseCode($this->getDefaultResponseCode());

		define('WEBSITE_ROOT', 'http://'.$this->get_main_folder());
		define('PAGE_RELATIVE', str_repeat('../', count($this->url)-2).self::$page.'/');

		foreach($this->subdomains as $subdomain) {
			define(strtoupper($subdomain).'_ROOT', 'http://'.$subdomain.'.'.$this->get_main_folder());
		}

		if(self::isAjax()) {
			require_once ROOT.'assets'.DS.self::DIR_TEMPLATES.DS.self::$page.'.page.php';
		} else {
			require_once ROOT.'assets'.DS.self::DIR_TEMPLATES.DS.'header.tpl.php';
			require_once ROOT.'assets'.DS.self::DIR_TEMPLATES.DS.self::$page.'.page.php';
			require_once ROOT.'assets'.DS.self::DIR_TEMPLATES.DS.'footer.tpl.php';
			
		}

		http_response_code(self::$response_code);

		//now, headers has been sent, it's time to send buffer to the output, let's flush !
		ob_end_flush();
	}

	public static function isAjax() {
		return (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest');
	}

	public static function setPage($page) {
		self::$page = $page;
	}

	private function sanitize_output($buffer) {
		$search = array('/\s+/');
		$replace = array(' ');
		$buffer = preg_replace($search, $replace, $buffer);

		return ob_gzhandler($buffer, 5);
		// return gzencode($buffer);flozd
	}

	private function loadModules() {
		if(!is_dir(ROOT.'assets'.DS.self::DIR_MODULES))
			return;

		$loadList = array();
		foreach (glob(ROOT.'assets'.DS.self::DIR_MODULES.DS.'*.module.*.php', GLOB_ERR) as $filename) {
			$file = explode('.', basename($filename));
			if(!is_numeric($file[2]))
				throw new Exception('id is not numeric '.$file[2]);

			if(isset($loadList[$file[2]]))
				throw new Exception('Duplicate module id '.$file[2]);
			else
				$loadList[$file[2]] = $filename;
		}

		$loadList = array_reverse($loadList);

		foreach ($loadList as $module) {
			require_once $module;
		}
	}

	private function get_main_folder() {
		$dir = strrev(realpath(dirname ( __FILE__ )));
		return str_replace('\\', '/', $_SERVER['HTTP_HOST'].strrev(substr($dir, 0, -(strlen($_SERVER['DOCUMENT_ROOT'])))).'/');
	}

	public static function setResponseCode($code) {
		self::$response_code = (int)$code;
	}

	private function getDefaultResponseCode() {
		if(is_numeric(self::$page))
			return intval(self::$page);

		if(!file_exists(ROOT.'assets'.DS.self::DIR_TEMPLATES.DS.self::$page.'.page.php'))
			return self::$page = 404;

		return self::$response_code;
	}

	private function parseURI($uri) {
		$url = (self::SITE_LOCATION == null)?$uri:strstr($uri, stripslashes(SITE_LOCATION));
		return explode('/', strstr($uri, $url));
	}

	private function extractGETFromURL($url) {
		if(!is_array($url))
			return array();

		$getList = array();
		foreach($url as $key => $val) {
			if($key > 1 && $key&1)
				$getList[$url[$key-1]] = $val;
		}

		return $getList;
	}
}

if (!function_exists('http_response_code')) {
	function http_response_code($code = NULL) {
		if ($code !== NULL) {
			switch ($code) {
				case 200: $text = 'OK'; break;
				case 301: $text = 'Moved Permanently'; break;
				case 302: $text = 'Moved Temporarily'; break;
				case 400: $text = 'Bad Request'; break;
				case 401: $text = 'Unauthorized'; break;
				case 402: $text = 'Payment Required'; break;
				case 403: $text = 'Forbidden'; break;
				case 404: $text = 'Not Found'; break;
				case 500: $text = 'Internal Server Error'; break;
				default:
					exit('Unknown http status code "' . htmlentities($code) . '"');
				break;
			}

			$protocol = (isset($_SERVER['SERVER_PROTOCOL']) ? $_SERVER['SERVER_PROTOCOL'] : 'HTTP/1.0');
			header($protocol . ' ' . $code . ' ' . $text, false, $code);
			$GLOBALS['http_response_code'] = $code;
		} else {
			$code = (isset($GLOBALS['http_response_code']) ? $GLOBALS['http_response_code'] : 200);
		}
		return $code;
	}
}

$router = new router($_SERVER['REDIRECT_URL']);
?>