<?php
    include "../config.php";

    $title = $config['leaguename'];
	echo 
		"<div id='header'>
			<span id='title'>$title</span> <span id='linkbar'>| <a href='analytics.php'>Analytics</a></span>
		</div>";
?>