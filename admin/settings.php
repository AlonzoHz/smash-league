<html>
    <body>
        <?php
			if (isset($_POST['leaguename'])) {
                $path = "inc/config/config.json";
                $new_config = array (
                    'leaguename' => $_POST['leaguename'],
                    'theme' => $_POST['theme'],
                );
                file_put_contents($path, json_encode($new_config));
            }

            include 'inc/config.php';
        ?>
        <form action="settings.php" method="post">
            League Name: 
            <input type="text" name="leaguename" <?php echo "value='".$config['leaguename']."'";?>><br/>
            Theme: 
            <input type="text" name="theme" <?php echo "value='".$config['theme']."'";?>><br/>
            <input type="submit" value="submit">
        </form>
    </body>
</html>