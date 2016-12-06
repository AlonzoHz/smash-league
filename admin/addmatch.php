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
		<a href="index.php">Return to index</a> <br/>
		<?php
			include 'inc/connect.php';
			
			require 'inc/Rating.php';
			
			if (isset($_POST['winner']))
			{
				//get post info
				$winnerid = $_POST['winner'];
				$winnermain = $_POST['winnermain'];
				$winnerkos = $_POST['winnerkos'];
				$loserid = $_POST['loser'];
				$losermain = $_POST['losermain'];
				$loserkos = $_POST['loserkos'];
				$map = $_POST['map'];
				$video = $_POST['video'];
				$tournament = $_POST['tournament'];
				
				//get stats about winner based on id
				$query = "SELECT rating, wins, loses, kos, falls FROM players WHERE id=$winnerid";
								
				if($query_run = mysqli_query($con, $query)) {
					while($query_row = mysqli_fetch_assoc($query_run)) {
						$wrating = $query_row['rating'];
						$wwins = $query_row['wins'];
						$wloses = $query_row['loses'];
						$wkos = $query_row['kos'];
						$wfalls = $query_row['falls'];
					}
				}
				
				//get stats about loser based on id
				$query = "SELECT rating, wins, loses, kos, falls FROM players WHERE id=$loserid";
								
				if($query_run = mysqli_query($con, $query)) {
					while($query_row = mysqli_fetch_assoc($query_run)) {
						$lrating = $query_row['rating'];
						$lwins = $query_row['wins'];
						$lloses = $query_row['loses'];
						$lkos = $query_row['kos'];
						$lfalls = $query_row['falls'];
					}
				}
				
				//calculate elo 
				$rating = new Rating($wrating, $lrating, 1, 0);
				$results = $rating->getNewRatings();
				
				//gather new stats for winner and update
				$newwrating = $results['a'];
				$newwwins = $wwins + 1;
				$newwkos = $wkos + $winnerkos;
				$newwfalls = $wfalls + $loserkos;
				$newwpercent = 100*$newwwins/($newwwins+$wloses);
				$query = "UPDATE players SET rating=$newwrating, wins=$newwwins, kos=$newwkos, falls=$newwfalls, winpercent=$newwpercent WHERE id=$winnerid";
				
				if (!mysqli_query($con, $query))
                {
                    die('Error: ' . mysqli_error($con));
                }
				
				//gather new stats for loser and update
				$newlrating = $results['b'];
				$newlloses = $lloses + 1;
				$newlkos = $lkos + $loserkos;
				$newlfalls = $lfalls + $winnerkos;
				$newlpercent = 100*$lwins/($lwins+$newlloses);
				$query = "UPDATE players SET rating=$newlrating, loses=$newlloses, kos=$newlkos, falls=$newlfalls, winpercent=$newlpercent WHERE id=$loserid";
				
				if (!mysqli_query($con, $query))
                {
                    die('Error: ' . mysqli_error($con));
                }
				
				//insert match
				$sql = "INSERT INTO `matches` (`winnerid`,   `loserid`, 
												`winnermain`, `losermain`, 
												`winnerkos`,  `loserkos`,
												`map`, 
												`video`,
												`tournament`
												) VALUES (
												'$winnerid',   '$loserid',
												'$winnermain', '$losermain', 
												'$winnerkos',  '$loserkos',
												'$map',
												'$video',
												'$tournament'
												);";			
				
                if (!mysqli_query($con, $sql))
                {
                    die('Error: ' . mysqli_error($con));
                }
				
				echo 'Submission successful. <br/>';
				
				
			}
		?>
		<table>
			<form action="addmatch.php" method="post">
				<tr>
					<td>
						Winner:
						<select name="winner">
							<?php
								$query = "SELECT id, name, screenname FROM players ORDER BY rating DESC";
								
								if($query_run = mysqli_query($con, $query)) {
									while($query_row = mysqli_fetch_assoc($query_run)) {
										$id = $query_row['id'];
										$name = $query_row['name'];
										$screenname = $query_row['screenname'];
										
										echo '<option value="'.$id.'">'.$name.' ('.$screenname.')</option>';
									}
								}
							?>
						</select>
					</td>
					<td>
						Main:
						<select name="winnermain">
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
						KOs:
						<input type="number" name="winnerkos">
					</td>
					<td>
						Map:
						<input type="text" name="map">
					</td>
					<td>
						Video:
						<input type="text" name="video">
					</td>
					<td>
						Tournament:
						<select name="tournament">
							<option value="0">Challenge</option>
							<?php
								$query = "SELECT id, name FROM tournaments ORDER BY date DESC";
								
								if($query_run = mysqli_query($con, $query)) {
									while($query_row = mysqli_fetch_assoc($query_run)) {
										$id = $query_row['id'];
										$name = $query_row['name'];
										
										echo '<option value="'.$id.'">'.$name.'</option>';
									}
								}
							?>
						</select>
					</td>
				</tr>
				<tr>
					<td>
						Loser:
						<select name="loser">
							<?php
								$query = "SELECT id, name, screenname FROM players ORDER BY rating DESC";
								
								if($query_run = mysqli_query($con, $query)) {
									while($query_row = mysqli_fetch_assoc($query_run)) {
										$id = $query_row['id'];
										$name = $query_row['name'];
										$screenname = $query_row['screenname'];
										
										echo '<option value="'.$id.'">'.$name.' ('.$screenname.')</option>';
									}
								}
							?>
						</select>
					</td>
					<td>
						Main:
						<select name="losermain">
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
						KOs:
						<input type="number" name="loserkos">
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