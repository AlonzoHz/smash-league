<?php
	$conn_error = 'Please contact the website administrator to report this error';
	
	$mysql_host = '';
	$mysql_user = '';
	$mysql_pass = '';
	$mysql_db = '';
	
	$con = new mysqli($mysql_host, $mysql_user, $mysql_pass, $mysql_db);
	if($con->connect_errno)
	{
		die($conn_error);
	}
?>