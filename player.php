<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">

<?php
	include 'admin/inc/connect.php';
	
	include 'admin/inc/nested_query.php';

    include 'admin/inc/config.php';
?>

<html>
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
		<link href='https://fonts.googleapis.com/css?family=Lato' rel='stylesheet' type='text/css'>
		<title><?php echo $config['leaguename'];?></title>
		<link rel="stylesheet" type="text/css" <?php echo "href='admin/themes/" . $config['theme'] . "/style.css'";?>>
	</head>
	
	<body>
		<a href="index.php">Return to home page</a>
		
		<?php
			include 'admin/inc/header.php';
		
			$player_id = 1;
			if (isset($_GET['id'])) {
				$player_id = $_GET['id'];
			}

			echo '<div class="section">';
			echo '<h3>Stats</h3>';
			echo '<table>';
			echo '<tr><th>Name</th><th>Main</th><th>Wins</th><th>Loses</th><th>KOs</th><th>Falls</th><th>Win %</th><th>Rating</th></tr>';
			
			$query = "SELECT * FROM players WHERE id=".$player_id." LIMIT 1";
			
			if($query_run = mysqli_query($con, $query)) {
				while($query_row = mysqli_fetch_assoc($query_run)) {
					$name = $query_row['name'];
					$screenname = $query_row['screenname'];
					$main = $query_row['main'];
					$wins = $query_row['wins'];
					$loses = $query_row['loses'];
					$kos = $query_row['kos'];
					$falls = $query_row['falls'];
					$winpercent= $query_row['winpercent'];
					$rating = $query_row['rating'];
					
					echo '<tr><td>'.$name.' ('.$screenname.')</td><td><img src="charicons/'.$main.'.png" alt="'.$main.'"/></td><td>'.$wins.'</td><td>'.$loses.'</td><td>'.$kos.'</td><td>'.$falls.'</td><td>'.$winpercent.'</td><td>'.$rating.'</td></tr>';
				}
			}
			
			echo '</table>';
			echo '</div>';
			
			echo '<div class="section">';
			echo '<h3>Tournament Placings</h3>';
			echo '<table>';
			echo '<tr><th>Tournament</th><th>Date</th><th>Place</th></tr>';
			
			$query = "SELECT * FROM tournamentplayer WHERE player=$player_id";
			
			if($result = mysqli_query($con, $query)){
				$num = mysqli_num_rows($result);
				$i = 0;
				
				while($i < $num) {
					$tournament_id = func_mysqli_result($result, $i, "tournament");
					$place = func_mysqli_result($result, $i, "place");
					
					//find the tournament matching the id
					$sub_query = "SELECT name, date FROM tournaments WHERE id=$tournament_id";
					if($sub_result = mysqli_query($con, $sub_query)) {
						$tournament_name = func_mysqli_result($sub_result, 0, "name");
						$tournament_date = func_mysqli_result($sub_result, 0, "date");
						
						echo "<tr><td>$tournament_name</td><td>$tournament_date</td><td>$place</td></tr>";
					}
					
					$i++;
				}
			}
			
			echo '</table>';			
			echo '</div>';
			
			echo '<div class="section">';
			echo '<h3>Matches</h3>';
			echo '<table><tr><th>Winner</th><th>Character</th><th>KOs</th><th>Loser</th><th>Character</th><th>KOs</th><th>Map</th><th>Video</th><th>Tournament</th></tr>';
			
			$query = "SELECT * FROM matches WHERE winnerid=$player_id OR loserid=$player_id";
			if($result = mysqli_query($con, $query)){
				$num = mysqli_num_rows($result);
				$i = 0;
				
				while($i < $num) {
					$winnerid   = func_mysqli_result($result, $i, "winnerid");
					$loserid    = func_mysqli_result($result, $i, "loserid");
					$winnermain = func_mysqli_result($result, $i, "winnermain");
					$losermain  = func_mysqli_result($result, $i, "losermain");
					$winnerkos  = func_mysqli_result($result, $i, "winnerkos");
					$loserkos   = func_mysqli_result($result, $i, "loserkos");
					$map        = func_mysqli_result($result, $i, "map");
					$video      = func_mysqli_result($result, $i, "video");
					$tournament = func_mysqli_result($result, $i, "tournament");
					
					$sub_query = "SELECT name, screenname FROM players WHERE id=$winnerid";
					if($sub_result = mysqli_query($con, $sub_query)){
						$winnername = func_mysqli_result($sub_result, 0, "name");
						$winnerscreenname = func_mysqli_result($sub_result, 0, "screenname");
					}
					
					$sub_query = "SELECT name, screenname FROM players WHERE id=$loserid";
					if($sub_result = mysqli_query($con, $sub_query)){
						$losername = func_mysqli_result($sub_result, 0, "name");
						$loserscreenname = func_mysqli_result($sub_result, 0, "screenname");
					}
					
					if($tournament != 0) {
						$sub_query = "SELECT name FROM tournaments WHERE id=$tournament";
						if($sub_result = mysqli_query($con, $sub_query)){
							$tournamentname = func_mysqli_result($sub_result, 0, "name");
						}
					} else {
						$tournamentname = "Challenge";
					}
					
					$match_row = "";
					
					if($player_id == $winnerid){
						$match_row = $match_row."<tr><td class='winner'>$winnername</td><td><img src='charicons/$winnermain.png' alt='$winnermain'/></td><td>$winnerkos</td>";
						$match_row = $match_row."<td>$losername</td><td><img src='charicons/$losermain.png' alt='$losermain'/></td><td>$loserkos</td>";
					} else {
						$match_row = $match_row."<tr><td>$winnername</td><td><img src='charicons/$winnermain.png' alt='$winnermain'/></td><td>$winnerkos</td>";
						$match_row = $match_row."<td class='loser'>$losername</td><td><img src='charicons/$losermain.png' alt='$losermain'/></td><td>$loserkos</td>";
					}
					
					$match_row = $match_row."<td>$map</td><td>$video</td><td>$tournamentname</td></tr>";
					
					echo $match_row;
					
					$i++; 
				}
			}
			
			echo '</table>';
			echo '</div>';
			
			mysqli_close($con);
			?>
		
		<div class="footer">
			2016 (c) GSMST Smash League
		</div>
	</body>
</html>