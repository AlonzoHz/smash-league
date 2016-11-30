<?php
    function loggedIn() {
        echo "hi";
        return isset($_SESSION['username']);
    }
?>