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
			
			if (isset($_POST['name']))
			{
				$name = $_POST['name'];
				$date = $_POST['date'];
				$host = $_POST['host'];
				$type = $_POST['type'];
				$winner = $_POST['winner'];
				$runnerup = $_POST['runnerup'];
				
				$sql = "INSERT INTO `tournaments` (`name`, `date`, `host`, `type`, `winner`, `runnerup`) VALUES ('$name', '$date', '$host', '$type', '$winner', '$runnerup');";

                if (!mysqli_query($con, $sql))
                {
                    die('Error: ' . mysqli_error($con));
                }
				
				echo 'Submission successful. <br/>';
			}
		?>
		<table>
			<form action="addtournament.php" method="post">
				<tr>
					<td>
						Name: <input type="text" name="name"> 
					</td>
					<td>
						Date: <input type="date" name="date"> 
					</td>
					<td>
						Host: 
						<select name="host">
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
						Type:
						<select name="type">
							<option value="Invite (7)">Invite (7)</option>
						</select>
					</td>
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
						Runnerup:
						<select name="runnerup">
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