<?php
	require(dirname( __FILE__ ).'/otherMethod.php');
	$info=array();
	$info['userID']=$_POST['userID'];
	$info['feedBackInfo']=$_POST['feedBack'];
	$Method=new otherMethod;
	$result=$Method->feedBack($info);
	echo json_encode($result);
?>