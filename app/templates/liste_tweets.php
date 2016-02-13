<html>
<head>
	<title>Liste des Tweets !</title>
	<link rel="stylesheet" href="style.css" />
</head>
<body>
<h1>Liste des Tweets !</h1>

<?php

	/*foreach($liste as $ind=>$elt) {
		echo "<b>".$elt["nom"]." </b>";	
		echo "<i>".$elt["date_tweet"]."</i>"; //"date" pour json et inmemory 
		echo "<h2>".$elt["message"]."</h2>";
		if(isset($elt["os"])) {
			echo "<span>".$elt["os"]."</span>";		
		}
		echo "<br/><br/>";  	
	}*/
	
	echo "<div id='content'><ul>";
	foreach($liste as $tweet) {
		echo '<a href="tweets/'.$tweet->getId().'">';
		echo '<li class="itemTweet" >';
		echo "<b>".$tweet->getNom()." </b>";	
		echo "<i>".$tweet->getDateTweet()."</i>"; 
		echo "<h2>".$tweet->getMessage()."</h2>";
		if($tweet->getOs() !== null) {
			echo "<span>".$tweet->getOs()."</span>";		
		}
		echo "</li>";
		echo '</a>';
	}
	echo "</ul></div>";

	echo "<a href='tweet/new' id='boutonAjouter'>Ajouter un tweet</a>";
	
?>
</body>
</html>
