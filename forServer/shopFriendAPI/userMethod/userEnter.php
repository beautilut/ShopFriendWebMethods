<?php
	require(dirname( __FILE__ ).'/userMethods.php');
	$info=array();
	$info['userName']=$_POST['userName'];
	$info['userPassword']=$_POST['userPassword'];
	$userMethod=new userMethods;
	$result=$userMethod->userEnter($info);
	echo $result;
?>