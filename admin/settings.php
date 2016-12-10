<?php
    include 'inc/logged_in.php';

    include 'inc/config.php';
?>

<html>
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
		<link href='https://fonts.googleapis.com/css?family=Lato' rel='stylesheet' type='text/css'>
		<title><?php echo $config['leaguename'];?></title>
		<link rel="stylesheet" type="text/css" <?php echo "href='themes/" . $config['theme'] . "/style.css'";?>>
	</head>

    <body>
        <?php
			if (isset($_POST['leaguename'])) {
                $path = "inc/config/config.json";
                $new_config = array (
                    'leaguename' => $_POST['leaguename'],
                    'theme' => $_POST['theme'],
                    'rankThreshold' => $_POST['rankThreshold'],
                );
                file_put_contents($path, json_encode($new_config));
            }

            include 'inc/config.php';
        ?>
        
        <div class="form">
            <div class="form-elements">
                <form action="settings.php" method="post">
                    League Name: 
                    <input type="text" name="leaguename" <?php echo "value='".$config['leaguename']."'";?>><br/>
                    Theme: 
                    <input type="text" name="theme" <?php echo "value='".$config['theme']."'";?>><br/>
                    Match threshold for unranked players (exclusive): 
                    <input type="number" name="rankThreshold" <?php echo "value='".$config['rankThreshold']."'";?>><br/>
                    <input type="submit" value="submit">
                </form>
            </div>
        </div>
    </body>
</html>