<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">

<?php
	include 'admin/inc/connect.php';
	
	include 'admin/inc/nested_query.php';

    $config = include 'admin/inc/config.php';
?>

<html>
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
		<link href='https://fonts.googleapis.com/css?family=Lato' rel='stylesheet' type='text/css'>
		<title><?php echo $config['leaguename'];?></title>
		<link rel="stylesheet" type="text/css" href="style.css">
	</head>
	
	<body>
		<a href="/smash/">Return to home page</a>
		
		<?php
			include 'admin/inc/header.php';
			
			$tournament_id = 2;
			if (isset($_GET['id'])) {
				$tournament_id = $_GET['id'];
			}
			
			echo "<div class='section'>";
			echo "<table><tr><th>Name</th><th>Date</th><th>Host</th><th>Type</th></tr>";
			
			$query = "SELECT * FROM tournaments WHERE id=".$tournament_id." LIMIT 1";
			
			if($result = mysqli_query($con, $query)) {
				$name = func_mysqli_result($result, 0, "name");
				$date = func_mysqli_result($result, 0, "date");
				$host = func_mysqli_result($result, 0, "host");
				
				$sub_query = "SELECT name, screenname FROM players WHERE id=$host";
				if($sub_result = mysqli_query($con, $sub_query)) {
					$hname = func_mysqli_result($sub_result, 0, "name");
					$hscreename = func_mysqli_result($sub_result, 0, "screenname");
				}
				
				echo "<tr><td>$name</td><td>$date</td><td>$hname ($hscreenname)</td><td></td></tr>";
			}
			

			
			echo "</table>";
			echo "</div>";
			
			//Match section
			echo '<div class="section">';
			echo '<h3>Matches</h3>';
			echo '<table><tr><th>Winner</th><th>Character</th><th>KOs</th><th>Loser</th><th>Character</th><th>KOs</th><th>Map</th><th>Video</th><th>Tournament</th></tr>';
			
			$query = "SELECT * FROM matches WHERE tournament=$tournament_id";
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
					
					$match_row = "";
					
					$match_row = $match_row."<tr><td>$winnername</td><td><img src='charicons/$winnermain.png' alt='$winnermain'/></td><td>$winnerkos</td>";
					$match_row = $match_row."<td>$losername</td><td><img src='charicons/$losermain.png' alt='$losermain'/></td><td>$loserkos</td>";
					$match_row = $match_row."<td>$map</td><td>$video</td><td>$name</td></tr>";
					
					echo $match_row;
					
					$i++; 
				}
			}
			
			echo '</table>';
			echo '</div>';
			
			?>
		
		<div class="footer">
			2016 (c) GSMST Smash League
		</div>
	</body>
</html>