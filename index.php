<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">

<html
	<head>
		<meta content="text/html;charset=utf-8" http-equiv="Content-Type">
		<title>Image Roulette</title>

		<link href="/css/main.css" rel="stylesheet" media="screen" type="text/css">
		<script src="/js/jquery.min.js" type="text/javascript"></script>
		<!-- <script src="/js/main.js" type="text/javascript"></script> -->
	</head>

	<?php 

	include('classes/imgur.php');
	$imgur = new Imgur;
	$next = $imgur->next(30);
	?>

	<body>
		<div id="titleCont">
			<h2>Random Imgur Images</h2>
		</div>
		
		<?php foreach ($next as $image): ?>
		<a target="_blank" href="http://i.imgur.com/<?php echo $image; ?>.jpg" ><img class="imgur" src="http://i.imgur.com/<?php echo $image; ?>.jpg" /><a/>
		<?php endforeach; ?>

	</body>

</html>