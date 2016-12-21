<?php

require_once 'imgur.php';

$imgur = new Imgur;
$imgur->debug = true;

	$next = $imgur->next(30);

	foreach ($next as $image) {
		echo 'http://i.imgur.com/' . $image . '.jpg' . PHP_EOL;
	}

die;
    

$existingImages = [
'13ImMVf',
'g2le7',
'Xb5tQ',
'Hxx0f3',
'2xIopCx',
'OIwUo',
'6JnDgE9',
'UaWIN',
'oHGh5',
'LkR92',
'HpzwVw',
'80cCzrX',
'EfdRK',
'Xrhm3I',
'2AZ9pPv',
'Br91J',
'r1Z75',
'bNGDlxk',
'qsIis',
'iYxTZz',
'ZrKbY',
'1W7xx',
'30vqb',
'WJH52pd'
];


foreach ($existingImages as $imageName) {
    if ($existing = $imgur->checkKnown($imageName)) {
        echo $imageName . ' -> ' . ($existing->valid ? 'Valid' : 'Invalid') . PHP_EOL;
    }
}