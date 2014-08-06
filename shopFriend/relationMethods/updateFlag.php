<?php
	require(dirname( __FILE__ ).'/relationShip.php');
	$info=array();
	$info['userID']=$_POST['userID'];
	$info['shopID']=$_POST['shopID'];
	$info['flag']=$_POST['flag'];
	$relationMethod=new relationShip;
	$result=$relationMethod->USrelationUpdate($info);
	echo $result;
?>