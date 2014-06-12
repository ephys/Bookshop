<?php
class error_manager {
	private $errorList = array();

	const TYPE_JSON = 0;
	const TYPE_HTML = 1;
	
	public function display($error = null, $dataType = error_manager::TYPE_JSON, $return_link = null) {
		global $page;
		if($return_link == null)
			$return_link = '<a href="'.$_SERVER["HTTP_REFERER"].'">Retour</a>';

		if($error != null) {
			if($dataType == $this::TYPE_JSON)
				return json_encode(array('error' => true, '0' => $error));
			elseif($dataType == $this::TYPE_HTML)
				return $page['header'].'<p>'.$error.', '.$return_link.'</p>'.$page['footer'];
			else
				return $error;
		}
		elseif(!empty($this->errorList)) {
			if($dataType == $this::TYPE_JSON) {
				$this->errorList['error'] = true;
				return json_encode($this->errorList);
			}
			elseif($dataType == $this::TYPE_HTML)
				return $page['header'].'<p>'.implode("<br/>\n", $this->errorList).', '.$return_link.'</p>'.$page['footer'];
			else
				return $this->errorList;
		}
		else
			return false;
	}

	public function add($error) {
		$this->errorList[] = $error;
	}
}
?>