<?php
	require(dirname( __FILE__ ).'/serverMethod.php');
	$server=new ServerMethods;
	$result=$server->showAllServer();
	echo $result;
?>