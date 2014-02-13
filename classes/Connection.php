<?php

/**
* A simple browser connection
*/
class Connection
{
	public $verbose = false;

	protected $_curl;

	protected $_defaultCurlOptions = array(
		'CURLOPT_USERAGENT'      => 'Mozilla/5.0 (Windows NT 6.1; WOW64; rv:14.0) Gecko/20100101 Firefox/14.0.1',
		'CURLOPT_COOKIEJAR'      => '',
		'CURLOPT_FOLLOWLOCATION' => true,
		'CURLOPT_RETURNTRANSFER' => true,
		'CURLOPT_AUTOREFERER'    => true,
		'CURLOPT_CONNECTTIMEOUT' => 120,
		'CURLINFO_HEADER_OUT'    => true,
		);

	public $lastResponse;
	public $lastInfo;

	public function __construct($options = array())	{
		$constants = $this->_defaultCurlOptions + $options;
		$this->setOptions($constants);
	}

	/**
	 * Gets a curl handler
	 *
	 * @return resource $curl
	 **/
	public function getCurl() {
	    if (!isset($this->_curl)) {
	    	$this->_curl = curl_init();
	    }

	    return $this->_curl;
	}

	/**
	 * Allows you to time events
	 * useless but I like it anyway
	 *
	 * @param bool $start
	 * @return int $time
	 **/
	protected $_startTime;
	public function timer($start = true)
	{
		list($usec, $sec) = explode(' ', microtime());
		$time = (float) $sec + (float) $usec;
		if ($start) {
			$this->_startTime = $time;
		} else {
			if (!empty($this->_startTime)) {
				return round($time - $this->_startTime, 3);
			}
		}

		return 0;
	}

	/**
	 * Gets the html from a specified url
	 *
	 * @param string $url
	 * @return html
	 **/
	public function request($url, $options = array()) {
		$this->setOptions($options);
		$ch = $this->getCurl();

		curl_setopt($ch, CURLOPT_URL, $url);
		$this->timer();
		if ($this->verbose){ echo 'Requesting: ' . $url . PHP_EOL; }
		$this->lastResponse = curl_exec($ch);
		$time = $this->timer(false);
		if ($this->verbose){ echo 'Received ('. $time .'s): ' . $url . PHP_EOL; }

		$this->lastInfo     = curl_getinfo($ch);

		return $this->lastResponse;
	}

	/**
	 * Sets up some curl options
	 *
	 * @param array of curl_opts
	 * @return void
	 **/
	public function setOptions($options) {
	    $ch = $this->getCurl();
	    foreach($options as $opt => $val) {
	    	$curlOpt = strtoupper($opt);
	    	curl_setopt($ch, constant($curlOpt), $val);
	    }
	}
	
}