<?php

require_once 'imgur.php';

$imgur = new Imgur;
$imgur->debug = true;

	$next = $imgur->next(5);

	var_dump($next);

	foreach ($next as $image) {
		echo 'http://i.imgur.com/' . $image . '.jpg' . PHP_EOL;
	}
