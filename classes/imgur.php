<?php
require_once 'ImgurGet.php';

class Imgur {

	protected $_db = null;
	protected $_path = null;
	public $debug = false;
	public $iterations  = 0;
	public $knownImages = 0;
	public $newImages   = 0;


	public function __construct() {
		$this->_path = realpath(dirname(__FILE__));
		$this->_setupDb();

		$this->imgurGet = new ImgurGet;
	}

	public function next($amount = 1) {
		$images = array();

		for ($i=0; $i < $amount; $i++) { 
			$images[] = $this->getImage();
		}

		return $images;
	}

	public function getTypes() {
		$sql = 'SELECT * FROM type ORDER BY id ASC';

		return $this->getDb()->query($sql)->fetchAll();
	}

	public function getImage() {

		$iterations = 100;

		while($iterations--) {
			$this->iterations++;

			//Generate new string
			$string = $this->getString();

			// $string = 'fFoOL';

			$this->consoleLog('Checking Image: ' . $string);
			
			//Search for existing record of string
			if ($existing = $this->checkKnown($string)) {
				$this->consoleLog('Known String: ' . $string);
				$this->consoleLog('String is:    ' . $existing->valid ? 'Valid' : 'Invalid');

				if ($existing->valid) { //Valid? we can stop
					$this->knownImages++;
					return $string;
				}

				continue; //Image id is invalid
			}

			if ($this->imgurGet->haveImage($string)) {
				$this->consoleLog('Found Image On Drive');
				$this->saveImage($string, 1);
				$this->knownImages++;

				return $string;
			}

			//Get the image
			$this->consoleLog('Getting Image:  ' . $string);
			if ($this->imgurGet->getImage($string)) {
				$this->consoleLog('Got Valid Image', true);
				$this->newImages++;
				$this->saveImage($string, 1);
				return $string;
			} else { //Cant get the image? Invalid string
				$this->consoleLog('Invalid Image', true);
				$this->saveImage($string, 0);
			}

		}

	}


	public function getString() {
		//We're looking for at least 5 - 7 chars
		$length = rand(5, 7);
		$chars = array_merge(range('A', 'Z'), range('a', 'z'), range(0, 9)); //alpa numeric, no special chars
		$range = count($chars) - 1;

    	$randomString = '';
    	//Pic a random leter $length number of times
	    for ($i = 0; $i < $length; $i++) {
	        $rand = rand(0, $range);
	        $randomString .= $chars[$rand];
	    }

    	return $randomString;
	}
	public function saveImage($string, $valid) {
		$sql = 'INSERT INTO known (uri, valid) VALUES (:uri, :valid)';

		$stmt = $this->getDb()->prepare($sql);

		$stmt->bindParam(':uri', $string);
		$stmt->bindParam(':valid', $valid);

		return $stmt->execute();
	}

	public function updateImage($string, $valid) {
		$sql = "UPDATE known SET valid = '$valid' WHERE uri = '$string'";


		return $this->getDb()->exec($sql);
	}

	public function checkKnown($string) {
		$sql  = "SELECT * FROM known WHERE uri = '{$string}'";

		return $this->getDb()->query($sql)->fetchObject();
	}


	protected function _setupDb() {

		$dbSetup = file_get_contents($this->_path . '/../db/setup.sql');

		$result = $this->getDb()->exec($dbSetup);

		$this->getDb()->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		$this->getDb()->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_OBJ);
	}


	public function getDb() {
		if ($this->_db) {
			return $this->_db;
		}

		$path = realpath(dirname(__FILE__) . '/../db/imgur.sqlite');

		$this->_db = new PDO('sqlite:' . $path);

		if (!$this->_db) {
			throw new Exception($error);
		}

		return $this->_db;
	}

	public function consoleLog($string, $end = false)
	{
		if ($this->debug) {
			echo $string . PHP_EOL . ($end ? PHP_EOL : '');

		}		
	}

}