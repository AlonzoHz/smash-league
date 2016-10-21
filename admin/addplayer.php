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
			
			if (isset($_POST['name']))
			{
				$name = $_POST['name'];
				$screenname = $_POST['screenname'];
				$main = $_POST['main'];
				
				$sql = "INSERT INTO `players` (`name`, `screenname`, `main`) VALUES ('$name', '$screenname', '$main');";

                if (!mysqli_query($con, $sql))
                {
                    die('Error: ' . mysqli_error($con));
                }
				
				echo 'Submission successful. <br/>';
			}
		?>
		<table>
			<form action="addplayer.php" method="post">
				<tr>
					<td>
						Name: <input type="text" name="name"> 
					</td>
					<td>
						Screenname: <input type="text" name="screenname"> 
					</td>
					<td>
						Main:
						<select name="main">
							<option value="Bowser">Bowser</option>
							<option value="CaptainFalcon">Captain Falcon</option>
							<option value="DonkeyKong">Donkey Kong</option>
							<option value="DrMario">Dr. Mario</option>
							<option value="Falco">Falco</option>
							<option value="Fox">Fox</option>
							<option value="Ganondorf">Ganondorf</option>
							<option value="IceClimbers">Ice Climbers</option>
							<option value="Jigglypuff">Jigglypuff</option>
							<option value="Kirby">Kirby</option>
							<option value="Link">Link</option>
							<option value="Luigi">Luigi</option>
							<option value="Mario">Mario</option>
							<option value="Marth">Marth</option>
							<option value="Mewtwo">Mewtwo</option>
							<option value="MrGame&Watch">Mr. Game and Watch </option>
							<option value="Ness">Ness</option>
							<option value="Peach">Peach</option>
							<option value="Pichu">Pichu</option>
							<option value="Pikachu">Pikachu</option>
							<option value="Roy">Roy</option>
							<option value="Samus">Samus</option>
							<option value="Sheik">Sheik</option>
							<option value="Yoshi">Yoshi</option>
							<option value="YoungLink">Young Link</option>
							<option value="Zelda">Zelda</option>
						</select>
					</td>
					<td>
						<input type="submit">
					</td>
				</tr>
			</form>
		</table>
		
		<?php
			mysqli_close($con);
		?>
	</body>
</html>