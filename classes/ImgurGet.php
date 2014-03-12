<?php

require_once 'Connection.php';


class ImgurGet {
	
	protected $_baseUri = 'http://i.imgur.com/';
	public $browser;

	protected $_removedImage = null;

	public function __construct() {
		$this->browser = new Connection;

	}


	public function getImage($string) {

		$result = $this->browser->request($this->_baseUri . $string . '.jpg');

		if ($this->validate($result)) {
			$this->_saveImage($result, $string);
		}

		return $this->validate($result);
	}

	public function validate($image) {

		$searchStrings = array(
			'/www/imgur.com',
			'<!doctype html>',
			'<!DOCTYPE'
		);

		foreach ($searchStrings as $html) {
			//404 or something?
			if (strpos($image, $html) !== FALSE) {
				return false;
			}	
		}

		

		//Special image saying that the one we're looking for has been deleted
		//Somehow the compare works for now :D
		if ($image == $this->_getRemovedImage()) {
			return false;
		}

		return true;
	}


	protected function _getRemovedImage() {
		if (!$this->_removedImage) {
			$this->_removedImage = file_get_contents(realpath(dirname(__FILE__) .'/removed.jpg'));
		}


		return $this->_removedImage;
	}

	protected function _saveImage($image, $name) {
		$path = realpath(dirname(__FILE__) . '/../img/found/');

		file_put_contents($path . '/' . $name . '.jpg', $image);
	}



}