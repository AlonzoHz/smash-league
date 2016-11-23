<?php
	$str = file_get_contents("config/config.json", true);
    $config = json_decode($str, true);
?>