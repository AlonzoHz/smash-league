<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">

<?php
    include 'inc/logged_in.php';
?>

<html>
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
		<link href='https://fonts.googleapis.com/css?family=Lato' rel='stylesheet' type='text/css'>
		<title>GSMST Smash League</title>
		<link rel="stylesheet" type="text/css" href="style.css">
	</head>
	
	<body>
        <?php echo "Welcome $username!<br/>"?>
		<a href="addplayer.php">Add a new player</a> <br/>
		<a href="addtournament.php">Add a new tournament</a> <br/>
		<a href="addmatch.php">Add a new match</a> <br/>
		<a href="refactor.php">Refactor</a> <br/>
		<a href="history.php">Historical records of ratings</a> <br/>
		<a href="settings.php">Settings</a> <br/>
		<a href="adduser.php">Add an administrative user</a> <br/>
		<a href="logout.php">Logout</a> <br/>
		<a href="..">Return to public page</a> <br/>
	</body>
</html>