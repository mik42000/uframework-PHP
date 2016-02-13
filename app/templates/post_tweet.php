<html>
	<head>
		<title>Ajouter un Tweet !</title>
		<link rel="stylesheet" href="../style.css" />
	</head>
	<body>
		<h1>Ajouter un Tweet !</h1>
		<div id='content'>
			<form action="/tweet" method="POST">
				<label for="nom">Username:</label>
				<input type="text" name="nom">
				<br><br>
				<label for="message">Message:</label>
				<textarea name="message"></textarea>
				<br><br>
				<input type="submit" id='boutonAjouter' value="Tweet!">
			</form>
		</div>
	</body>
</html>
