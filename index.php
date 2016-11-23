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
		<?php
			include 'admin/inc/header.php';
		
			echo '<div class="section">';
			echo '<h3>Power Rankings</h3>';
			echo '<table>';
			echo '<tr><th>Rank</th><th>Name</th><th>Main</th><th>Wins</th><th>Losses</th><th>KOs</th><th>Falls</th><th>Win %</th><th>Rating</th></tr>';
			
			$unranked_table = "<table><tr><th>Name</th><th>Main</th><th>Wins</th><th>Losses</th><th>KOs</th><th>Falls</th><th>Win %</th><th>Rating</th></tr>";
			
			
			$query = "SELECT * FROM players ORDER BY rating DESC, winpercent DESC, wins DESC LIMIT 50";
			
			$rank = 1;
			if($query_run = mysqli_query($con, $query)) {
				while($query_row = mysqli_fetch_assoc($query_run)) {
					$id = $query_row['id'];
					$name = $query_row['name'];
					$screenname = $query_row['screenname'];
					$main = $query_row['main'];
					$wins = $query_row['wins'];
					$loses = $query_row['loses'];
					$kos = $query_row['kos'];
					$falls = $query_row['falls'];
					$winpercent= $query_row['winpercent'];
					$rating = $query_row['rating'];
					
					if($wins + $loses > 5)
					{
						echo '<tr><td>'.$rank.'</td><td><a href="player.php?id='.$id.'">'.$name.' ('.$screenname.')</a></td><td><img src="charicons/'.$main.'.png" alt="'.$main.'"/></td><td>'.$wins.'</td><td>'.$loses.'</td><td>'.$kos.'</td><td>'.$falls.'</td><td>'.$winpercent.'</td><td>'.$rating.'</td></tr>';
						$rank++;
					} else {
						$unranked_table = $unranked_table.'<tr><td><a href="player.php?id='.$id.'">'.$name.' ('.$screenname.')</a></td><td><img src="charicons/'.$main.'.png" alt="'.$main.'"/></td><td>'.$wins.'</td><td>'.$loses.'</td><td>'.$kos.'</td><td>'.$falls.'</td><td>'.$winpercent.'</td><td>'.$rating.'</td></tr>';
					}
				}
			}
			
			echo '</table><br/>';
			
			$unranked_table = $unranked_table."</table>";
			echo "Unranked players: (Fewer than six matches) <br/>";
			echo $unranked_table;
			
			echo '</div>';
			
			
			//tournament stuff
			echo '<div class="section"><h2>Tournaments</h2></div>';
			
			$query = "SELECT * FROM tournaments ORDER BY date DESC LIMIT 10";
			if($result = mysqli_query($con, $query)) {
				$num = mysqli_num_rows($result);
				$i = 0;
				
				while($i < $num){
					$id = func_mysqli_result($result, $i, "id");
					$name = func_mysqli_result($result, $i, "name");
					$date = func_mysqli_result($result, $i, "date");
					$host = func_mysqli_result($result, $i, "host");
					$type = func_mysqli_result($result, $i, "type");
					$winner = func_mysqli_result($result, $i, "winner");
					$runnerup = func_mysqli_result($result, $i, "runnerup");
				
					echo '<div class="section">';
					echo "<h3><a href='tournament.php?id=$id'>$name</a></h3>";
					echo "<strong>Date:</strong> $date </br>";
					
					//for host
					echo "<strong>Host:</strong> ";
					$sub_query = "SELECT id, name, screenname FROM players WHERE id=$host";
					if($sub_result = mysqli_query($con, $sub_query)) {
						$hid = func_mysqli_result($sub_result, 0, "id");
						$hname = func_mysqli_result($sub_result, 0, "name");
						$hscreenname = func_mysqli_result($sub_result, 0, "screenname");
						
						echo "<a href='player.php?id=$hid'>$hname ($hscreenname)</a> <br/>";
					}
					
					echo "<strong>Type:</strong> $type </br>";
					
					//for winner
					echo "<strong>Winner:</strong> ";
					$sub_query = "SELECT id, name, screenname FROM players WHERE id=$winner";
					if($sub_result = mysqli_query($con, $sub_query)) {
						$wid = func_mysqli_result($sub_result, 0, "id");
						$wname = func_mysqli_result($sub_result, 0, "name");
						$wscreenname = func_mysqli_result($sub_result, 0, "screenname");
						
						echo "<a href='player.php?id=$wid'>$wname ($wscreenname)</a> <br/>";
					}
					
					//for loser
					echo "<strong>Runner-up:</strong> ";
					$sub_query = "SELECT id, name, screenname FROM players WHERE id=$runnerup";
					if($sub_result = mysqli_query($con, $sub_query)) {
						$rid = func_mysqli_result($sub_result, 0, "id");
						$rname = func_mysqli_result($sub_result, 0, "name");
						$rscreenname = func_mysqli_result($sub_result, 0, "screenname");
						
						echo "<a href='player.php?id=$rid'>$rname ($rscreenname)</a><br/>";
					}
					
					echo '</div>';
					
					$i++;
				}	
			}
				
			echo '</div>';
			
			mysqli_close($con);
		?>
		
		<div class="footer">
			2016 (c) GSMST Smash League 
		</div>
	</body>
</html>