<?php
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
			if (isset($_POST['username'])) {
                include 'inc/connect.php';
                include 'inc/nested_query.php';
                $username = $_POST['username'];
                $query = "SELECT * FROM users WHERE username='$username'";
                if ($result = mysqli_query($con, $query)) {
					$password = func_mysqli_result($result, 0, "password");
                    if (password_verify($_POST['password'], $password)) {
                        session_start();
                        $_SESSION['username'] = $username;
                        header('Location: index.php');
                        exit();
                    }
                }
                
                echo 'Login not found.';
            }
        ?>
        <div class="form">
            <div class="form-elements">
                <form action="login.php" method="post">
                    Username: 
                    <input type="text" name="username"><br/>
                    Password: 
                    <input type="password" name="password"><br/>
                    <input type="submit" value="submit">
                </form>
            </div>
        </div>
    </body>
</html>