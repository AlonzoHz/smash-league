<html>
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
        <form action="login.php" method="post">
            Username: 
            <input type="text" name="username"><br/>
            Password: 
            <input type="password" name="password"><br/>
            <input type="submit" value="submit">
        </form>
    </body>
</html>