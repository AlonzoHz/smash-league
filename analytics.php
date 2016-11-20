<?php
	include 'admin/inc/connect.php';
	include 'admin/inc/nested_query.php';
	
	require 'admin/inc/Rating.php';
	require 'admin/inc/jpgraph/jpgraph.php';
	require 'admin/inc/jpgraph/jpgraph_scatter.php';

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
		
		<!--<div>-->
			<form action="analytics.php" method="post">
				<?php
					include 'admin/inc/header.php';
					
					echo "<div class='section'>";
					echo "<h3>Query</h3>";
					
					$query = "SELECT id, name, screenname FROM players ORDER BY name";
					
					$options = "";
							
					if($query_run = mysqli_query($con, $query)) {
						while($query_row = mysqli_fetch_assoc($query_run)) {
							$playerid = $query_row['id'];
							$name = $query_row['name'];
							$screenname = $query_row['screenname'];
								
							$options = $options.'<option value="'.$playerid.'">'.$name.' ('.$screenname.')</option>';
						}
					}
					
					echo "Get information about <select name='id'>";
					echo $options;
					echo "</select> versus <select name='oppid'>";
					echo "<option value='0'>n/a</option>$options</select>";
				?>
				</select>
				<input type="submit">
			</form>
		</div>
		
		<?php
			if (!isset($_POST['id'])) {
				echo "<div class='section'>";
				echo "<h3>Info</h3>";
				echo "-To view complete elo and matchup data about an individual player, set the 'versus' player to 'n/a'</br>";
				echo "-To view head-to-head elo and matchup data comparing two players, select a second player from the 'versus' menu</br>";
				echo "-Have a cool idea for a graph or statistic? Please suggest it to Alonzo</br>";
				echo "</div>";
			} else if (isset($_POST['id'])){
				$id = $_POST['id'];
				$oppid = $_POST['oppid'];
				
				//Sets up the player information table
				echo "<div class='section'>";
				echo "<h3>Stats</h3>";
				echo "<table>";
				echo "<tr><th>Name</th><th>Main</th><th>Wins</th><th>Losses</th><th>KOs</th><th>Falls</th><th>Win %</th><th>Rating</th></tr>";
				
				//Gets information about the main player
				$query = "SELECT * FROM players WHERE id=$id";
				if($result = mysqli_query($con, $query)) {
					$name = func_mysqli_result($result, 0, "name");
					$screenname = func_mysqli_result($result, 0, "screenname");
					$main = func_mysqli_result($result, 0, "main");
					$wins = func_mysqli_result($result, 0, "wins");
					$losses = func_mysqli_result($result, 0, "loses");
					$kos = func_mysqli_result($result, 0, "kos");
					$falls = func_mysqli_result($result, 0, "falls");
					$winpercent= func_mysqli_result($result, 0, "winpercent");
					$rating = func_mysqli_result($result, 0, "rating");
					
					echo "<tr>";
					echo "<td>$name ($screenname)</td>";
					echo "<td><img src='charicons/$main.png' alt='$main'/></td>";
					echo "<td>$wins</td>";
					echo "<td>$losses</td>";
					echo "<td>$kos</td>";
					echo "<td>$falls</td>";
					echo "<td>$winpercent</td>";
					echo "<td>$rating</td>";
					echo "</tr>";
					
					//If the opponent is set, queries and echos information about the opponent
					if($oppid != 0)
					{
						$oppquery = "SELECT * FROM players WHERE id=$oppid";
						$oppresult = mysqli_query($con, $oppquery);
						
						$oppname = func_mysqli_result($oppresult, 0, "name");
						$oppscreenname = func_mysqli_result($oppresult, 0, "screenname");
						$oppmain = func_mysqli_result($oppresult, 0, "main");
						$oppwins = func_mysqli_result($oppresult, 0, "wins");
						$opplosses = func_mysqli_result($oppresult, 0, "loses");
						$oppkos = func_mysqli_result($oppresult, 0, "kos");
						$oppfalls = func_mysqli_result($oppresult, 0, "falls");
						$oppwinpercent= func_mysqli_result($oppresult, 0, "winpercent");
						$opprating = func_mysqli_result($oppresult, 0, "rating");
						
						echo "<tr>";
						echo "<td>$oppname ($oppscreenname)</td>";
						echo "<td><img src='charicons/$oppmain.png' alt='$oppmain'/></td>";
						echo "<td>$oppwins</td>";
						echo "<td>$opplosses</td>";
						echo "<td>$oppkos</td>";
						echo "<td>$oppfalls</td>";
						echo "<td>$oppwinpercent</td>";
						echo "<td>$opprating</td>";
						echo "</tr>";
					}
				}	
			
				echo "</table>";
				echo "</div>";
				
				$elodata = Array();
				$elodata[0] = 1200;
				$matchdata[0] = 0;
				
				$oppelodata = Array();
				$oppelodata[0] = 1200;
				$oppmatchdata[0] = 0;
				
				$winsversusopp  = 0;
				$lossesversusopp = 0; 
				
				$mapsplayed = Array(); 
				$mapsrecord = Array();
				
				$individual_mapsplayed = Array();
				$individual_mapsrecord = Array();
				
				$charactersplayed = Array();
				$charactersrecord = Array();
				
				$elo_won = 0; 
				
				$matchtable = "<table><tr><th>Winner</th><th>Character</th><th>KOs</th><th>Loser</th><th>Character</th><th>KOs</th><th>Map</th><th>Video</th></tr>";
						
				$query = "SELECT * FROM matches";
				
				if($result = mysqli_query($con, $query)) {
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
					
						if(!isset($arr[$winnerid]["elo"]))
						{
							$arr[$winnerid]["elo"] = 1200;
						}
						
						if(!isset($arr[$loserid]["elo"]))
						{
							$arr[$loserid]["elo"] = 1200;
						}
							
						$rating = new Rating($arr[$winnerid]["elo"], $arr[$loserid]["elo"], 1, 0);
						$newelos = $rating->getNewRatings();
						
						$delta_elo = $arr[$loserid]["elo"] - $newelos['b'];
						
						$arr[$winnerid]["elo"] = $newelos['a'];
						$arr[$loserid]["elo"]  = $newelos['b'];
								
						if($winnerid == $id)
						{
							array_push($elodata, $arr[$winnerid]["elo"]);
							array_push($matchdata, $i);
							
							if($oppid == 0)
							{
								if(!in_array($map, $individual_mapsplayed))
								{
									array_push($individual_mapsplayed, $map);
									$individual_mapsrecord[$map]["wins"] = 0;
									$individual_mapsrecord[$map]["losses"] = 0;
								}
								
								$individual_mapsrecord[$map]["wins"] += 1;
								
								if(!in_array($losermain, $charactersplayed))
								{
									array_push($charactersplayed, $losermain);
									$charactersrecord[$losermain]["wins"] = 0;
									$charactersrecord[$losermain]["losses"] = 0;
								}
								
								$charactersrecord[$losermain]["wins"] += 1;
							}
						} else if ($loserid == $id) {
							array_push($elodata, $arr[$loserid]["elo"]);
							array_push($matchdata, $i);
							
							if($oppid == 0)
							{
								if(!in_array($map, $individual_mapsplayed))
								{
									array_push($individual_mapsplayed, $map);
									$individual_mapsrecord[$map]["wins"] = 0;
									$individual_mapsrecord[$map]["losses"] = 0;
								}
								
								$individual_mapsrecord[$map]["losses"] += 1;
								
								if(!in_array($winnermain, $charactersplayed))
								{
									array_push($charactersplayed, $winnermain);
									$charactersrecord[$winnermain]["wins"] = 0;
									$charactersrecord[$winnermain]["losses"] = 0;
								}
								
								$charactersrecord[$winnermain]["losses"] += 1;
							}
						} 	
					
						if($winnerid == $oppid)
						{
							array_push($oppelodata, $arr[$winnerid]["elo"]);
							array_push($oppmatchdata, $i);
						} else if ($loserid == $oppid) {
							array_push($oppelodata, $arr[$loserid]["elo"]);
							array_push($oppmatchdata, $i);
						}
						
						if($winnerid == $id && $loserid == $oppid) {
							$winsversusopp += 1;
							
							if(!in_array($map, $mapsplayed))
							{
								array_push($mapsplayed, $map);
								$mapsrecord[$map]["wins"] = 0;
								$mapsrecord[$map]["losses"] = 0;
							}
							
							$mapsrecord[$map]["wins"] += 1;
							
							$matchtable = $matchtable."<tr><td>$name ($screenname)</td><td><img src='charicons/$winnermain.png' alt='$winnermain'/></td><td>$winnerkos</td>";
							$matchtable = $matchtable."<td>$oppname ($oppscreenname)</td><td><img src='charicons/$losermain.png' alt='$losermain'/></td><td>$loserkos</td>";
							$matchtable = $matchtable."<td>$map</td><td>$video</td></tr>";
							
							$elo_won += $delta_elo;
						} else if ($winnerid == $oppid && $loserid == $id) {
							$lossesversusopp += 1;
							
							if(!in_array($map, $mapsplayed))
							{
								array_push($mapsplayed, $map);
								$mapsrecord[$map]["wins"] = 0;
								$mapsrecord[$map]["losses"] = 0;
							}
							
							$mapsrecord[$map]["losses"] += 1;
							
							$matchtable = $matchtable."<tr><td>$oppname ($oppscreenname)</td><td><img src='charicons/$winnermain.png' alt='$winnermain'/></td><td>$winnerkos</td>";
							$matchtable = $matchtable."<td>$name ($screenname)</td><td><img src='charicons/$losermain.png' alt='$losermain'/></td><td>$loserkos</td>";
							$matchtable = $matchtable."<td>$map</td><td>$video</td></tr>";
							
							$elo_won -= $delta_elo;
						}
							
						$i++;
					}
				}
				
				if(!file_exists("graphs/{$id}vs{$oppid}_$num.png"))
				{
					$graph = new Graph(640, 480);
					$graph->SetScale('intint');
					$graph->title->Set("Elo Rating over Time");
					$graph->xaxis->title->Set("Matches");
					$graph->yaxis->title->Set("Elo Rating");
				
					$player = new ScatterPlot($elodata, $matchdata);
					$graph->Add($player);
					$player->mark->SetType(MARK_IMG, "charicons/$main.png", 0.5);
					$player->SetLegend("$name ($screenname)");
					$player->link->Show();
				
					if($oppid != 0) {
						$player2 = new ScatterPlot($oppelodata, $oppmatchdata);
						$graph->Add($player2);
						$player2->mark->SetType(MARK_IMG, "charicons/$oppmain.png", 0.5);
						$player2->SetLegend("$oppname ($oppscreenname)");
						$player2->link->Show();
					}
			
					$graph->Stroke("graphs/{$id}vs{$oppid}_$num.png");
				}
				
				echo "<div class='section'>";
				echo "<h3>Elo History</h3>";
				echo "<img src='graphs/{$id}vs{$oppid}_$num.png'/>";
				
				if($oppid != 0) {
                    if(!($winsversusopp + $lossesversusopp == 0)) {
                        echo "<br/>$name ($screenname)'s elo has changed by $elo_won from matches with $oppname ($oppscreenname)";
                        echo "</div>";
                        
                        echo "<div class='section'>";
                        echo "<h3>Matches</h3>";
                        $matchtable = $matchtable."</table>";
                        echo $matchtable;
                        echo "</div>";
                        
                        echo "<div class='section'>";
                        echo "<h3>Matchups</h3>";
                        echo "$name ($screenname) has $winsversusopp wins and $lossesversusopp losses against $oppname ($oppscreenname)<br/>";
                        
                        foreach($mapsplayed as $mapindex)
                        {
                            $winsonmap = $mapsrecord[$mapindex]["wins"];
                            $lossesonmap = $mapsrecord[$mapindex]["losses"];
                            
                            echo "$name ($screenname) has $winsonmap wins and $lossesonmap losses on <strong>$mapindex</strong> against $oppname ($oppscreenname)<br/>";
                        }
                        
                        echo "</div>";
                    } else {
                        echo "<br/>These players have never faced eachother.</div>";
                    }
				} else {
					echo "</div>";
					
					echo "<div class='section'>";
					echo "<h3>Matchups</h3>";
					
					foreach($individual_mapsplayed as $mapindex)
					{
						$winsonmap = $individual_mapsrecord[$mapindex]["wins"];
						$lossesonmap = $individual_mapsrecord[$mapindex]["losses"];
						
						echo "$name ($screenname) has $winsonmap wins and $lossesonmap losses on <strong>$mapindex</strong><br/>";
					}
					
					foreach($charactersplayed as $characterindex)
					{
						$winsvscharacter = $charactersrecord[$characterindex]["wins"];
						$lossesvscharacter = $charactersrecord[$characterindex]["losses"];
						
						echo "$name ($screenname) has $winsvscharacter wins and $lossesvscharacter losses against <img src='charicons/$characterindex.png' alt='$characterindex'/><br/>";
					}
					
					echo "</div>";
				}	
			}
			
			mysqli_close($con);
		?>
		
		<div class="footer">
			2016 (c) GSMST Smash League 
		</div>
	</body>
</html>