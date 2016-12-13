<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">

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
			if (isset($_POST['username'])) {
                include 'inc/connect.php';
                include 'inc/nested_query.php';

                $username = $_POST['username'];
                $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

                $query = "SELECT * FROM users WHERE username='$username'";
                if ($result = mysqli_query($con, $query)) {
                    $num = mysqli_num_rows($result);
                    if ($num <= 0) {
                        $query1 = "INSERT INTO `users` (`username`, `password`) VALUES ('$username', '$password')";
                        
                        if (mysqli_query($con, $query1)) {
                            echo "New user $username created!";
                        } else {
                            echo 'Signup failed...';
                        }
                    } else {
                        echo 'Username is already taken!';
                    }
                } else {
                    echo 'Signup unsuccessful...';
                }
            }
        ?>
        <div class="form">
            <div class="form-elements">
                Create a user:
                <form action="adduser.php" method="post">
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