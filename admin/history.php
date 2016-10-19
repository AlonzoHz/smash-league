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
			include 'connect.php';
			include '../inc/nested_query.php';
			
			require 'Rating.php';
			
			if (isset($_POST['upto']))
			{
				$upto = $_POST['upto'];
				
				$query = "SELECT * FROM matches WHERE id<=$upto";
				
				if($result = mysqli_query($con, $query)) {
					$i = 0;
					while($i <= $upto) {
						$winnerid = func_mysqli_result($result, $i, "winnerid");
						$loserid = func_mysqli_result($result, $i, "loserid");
						$winnerkos = func_mysqli_result($result, $i, "winnerkos");
						$loserkos = func_mysqli_result($result, $i, "loserkos");
						
						if(!isset($arr[$winnerid]["wins"]))
						{
							$arr[$winnerid]["wins"] = 0;
							$arr[$winnerid]["loses"] = 0;
							$arr[$winnerid]["kos"] = 0;
							$arr[$winnerid]["falls"] = 0;
							$arr[$winnerid]["win"] = 0;
							$arr[$winnerid]["elo"] = 1200;
						}
						
						if(!isset($arr[$loserid]["wins"]))
						{
							$arr[$loserid]["wins"] = 0;
							$arr[$loserid]["loses"] = 0;
							$arr[$loserid]["kos"] = 0;
							$arr[$loserid]["falls"] = 0;
							$arr[$loserid]["win"] = 0;
							$arr[$loserid]["elo"] = 1200;
						}
						
						$rating = new Rating($arr[$winnerid]["elo"], $arr[$loserid]["elo"], 1, 0);
						$newelos = $rating->getNewRatings();
						
						$arr[$winnerid]["wins"]  += 1;
						$arr[$winnerid]["kos"]   += $winnerkos;
						$arr[$winnerid]["falls"] += $loserkos;
						$arr[$winnerid]["win"]   = 100 * $arr[$winnerid]["wins"] / ($arr[$winnerid]["wins"] + $arr[$winnerid]["loses"]);
						$arr[$winnerid]["elo"]   = $newelos['a'];
						
						$arr[$loserid]["loses"]  += 1;
						$arr[$loserid]["kos"]   += $loserkos;
						$arr[$loserid]["falls"] += $winnerkos;
						$arr[$loserid]["win"]   = 100 * $arr[$loserid]["wins"] / ($arr[$loserid]["wins"] + $arr[$loserid]["loses"]);
						$arr[$loserid]["elo"]   = $newelos['b'];
						
						$i++;
					}
				}
				
				echo '<table><tr><th>ID</th><th>Wins</th><th>Losses</th><th>KOs</th><th>Falls</th><th>Win %</th><th>Rating</th></tr>';
				
				$i2 = 1;
				while($i2 <= 15)
				{
					echo '<tr><td>'.$i2.'</td><td>'.$arr[$i2]["wins"].'</td><td>'.$arr[$i2]["loses"].'</td><td>'.$arr[$i2]["kos"].'</td><td>'.$arr[$i2]["falls"].'</td><td>'.$arr[$i2]["win"].'</td><td>'.$arr[$i2]["elo"].'</td></tr>';
					
					$i2++;
				}
				
				echo '</table>';
				
				echo 'Refactoring successful. <br/>';
			}
		?>
		
		<form action="history.php" method="post">
			History up to match number: <input type="number" name="upto"> 
			<input type="submit">
		</form>
		
		<?php
			mysqli_close($con);
		?>
	</body>
</html>