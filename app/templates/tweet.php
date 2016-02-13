<html>
<head>
	<title>Tweet !</title>
	<link rel="stylesheet" href="../style.css" />
</head>
<body>
<h1>Tweet !</h1>

<div id="content">
<?php

		/*echo "<b>".$tweet["nom"]." </b>";	
		echo "<i>".$tweet["date_tweet"]."</i>"; //"date" pour json et inmemory 
		echo "<h2>".$tweet["message"]."</h2>";
		if(isset($tweet["os"])) {
			echo "<span>".$tweet["os"]."</span>";		
		}*/

		echo "<b>".$tweet->getNom()." </b>";	
		echo "<i>".$tweet->getDateTweet()."</i>";
		echo "<h2>".$tweet->getMessage()."</h2>";
		if($tweet->getOs() !== null) {
			echo "<span>".$tweet->getOs()."</span>";		
		}
?>

<form action="/statuses/<?= $tweet->getId() ?>" method="POST">
	<input type="hidden" name="_method" value="DELETE">
	<input type="submit" id='boutonAjouter' value="Delete">
</form>
</div>
</body>
</html>
