<?php
require_once('nbt.class.php');

class BookLoader {
	private $nbt = null;
	private $data = array();
	private $signedBooks = array();
	private $unsignedBooks = array();
	
	const BOOK_UNSIGNED = 386;
	const BOOK_SIGNED = 387;

	public function __construct($book = null) {
		$this->nbt = new nbt();
		$this->parseNBT($book);
	}
	
	public function parseNBT($book = '')
	{
		if($book && (@$this->data = $this->nbt->loadFile($book, null))) 
		{
			if(isset($this->data['Inventory']) && is_array($this->data['Inventory']))
					$inventaire = $this->data['Inventory'];
			elseif(isset($this->data['Data']['Inventory']) && is_array($this->data['Data']['Inventory']))
				$inventaire = $this->data['Data']['Inventory'];
			elseif(isset($this->data['Data']['Player']['Inventory']) && is_array($this->data['Data']['Player']['Inventory']))
				$inventaire = $this->data['Data']['Player']['Inventory'];
			else
			{
				echo ERROR_UPLOAD_UNSUPORTED_NBT;
				print_r($this->data);
				return;
			}
				
			foreach($inventaire as $item)
			{
				if($item['id'] == 386) // unsigned book
					$this->unsignedBooks[] = utf8_encode_array($item['tag']);
				elseif($item['id'] == 387) // signed book
					$this->signedBooks[] = utf8_encode_array($item['tag']);
			}
		}
	}
	
	public function getBooks($type = false)
	{
		if(!$type)
			return array_merge($this->unsignedBooks, $this->signedBooks);
		elseif($type == $this::BOOK_UNSIGNED)
			return $this->unsignedBooks;
		elseif($type == $this::BOOK_SIGNED)
			return $this->signedBooks;
	}
	
	public function getBooksCount($type = false)
	{
		if(!$type)
			return (sizeof($this->unsignedBooks)+sizeof($this->signedBooks));
		elseif($type == $this::BOOK_UNSIGNED)
			return sizeof($this->unsignedBooks);
		elseif($type == $this::BOOK_SIGNED)
			return sizeof($this->signedBooks);
	}
	
	public function getRaw()
	{
		return $this->data;
	}
	
	public function clearData()
	{
		$this->data = array();
		$this->signedBooks = array();
		$this->unsignedBooks = array();
	}
}

function utf8_encode_array($array)
{
	foreach($array as $key=>$value)
	{
		if(is_array($value))
			$value = utf8_encode_array($value);
		else
			$value = utf8_encode($value);
		$newarray[$key] = $value;
	}
	return $newarray;
}
?>
