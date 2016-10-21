<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">

<html>
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
		<link href='https://fonts.googleapis.com/css?family=Lato' rel='stylesheet' type='text/css'>
		<title>GSMST Smash League</title>
		<link rel="stylesheet" type="text/css" href="style.css">
	</head>
	
	<body>
		<a href="index.php">Return to index</a> <br/>
		<?php
			include 'inc/connect.php';
			
			if (isset($_POST['upto']))
			{
				$upto = $_POST['upto'];
				
				echo 'Submission successful. <br/>';
			}
		?>
		
		<form action="rankings.php" method="post">
			Up to: <input type="number" name="upto"> 
			<input type="submit">
		</form>
		
		<?php
			mysqli_close($con);
		?>
	</body>
</html>