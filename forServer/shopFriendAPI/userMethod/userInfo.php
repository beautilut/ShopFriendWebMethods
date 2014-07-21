<?php
	require(dirname( __FILE__ ).'/userMethods.php');
	$userMethod=new userMethods;
	$result=$userMethod->getUserInfo($_POST['userID']);
	echo $result;
?>
