<?php
	require(dirname( __FILE__ ).'/userMethods.php');
	$info=array();
	$info['userID']=$_POST['userID'];
	$info['key']=$_POST['key'];
	$info['data']=$_POST['data'];
	$userMethods=new userMethods;
	$result=$userMethods->changeUserInfo($info);
	echo $result;
?>