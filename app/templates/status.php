<html>
<head></head>
	<body>
		<form action="/statuses/<?= $id ?>" method="POST">
			<input type="hidden" name="_method" value="DELETE">
			<input type="submit" value="Delete">
		</form>
	</body>
</html>
