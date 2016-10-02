<!DOCTYPE html>
<html>
	<head>
		<title>Confirmation Page</title>
            </head>
	<body >
		
		<strong>Name:</strong> <?php echo $_POST["name"]; ?><br>
		<strong>Email:</strong> <?php echo $_POST["email"]; ?><br>
		<strong>Major:</strong> <?php echo $_POST["major"]; ?><br>
		<strong>Places:</strong> <?php $place = $_POST["continent"];
		$N = count($place);
		for($i = 0; $i < $N; $i++) {
			echo htmlspecialchars($place[$i] ). " ";
			} ?><br>
		<strong>Comments:</strong> <?php echo $_POST["comment"]; ?><br>
	</body>
</html>