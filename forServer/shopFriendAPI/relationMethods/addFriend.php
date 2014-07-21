<?php
	require(dirname( __FILE__ ).'/relationShip.php');
	$info=array();
	$info['userID']=$_POST['userID'];
	$info['shopID']=$_POST['shopID'];
	$relationMethod=new relationShip;
	$result=$relationMethod->USrelationInsert($info);
	echo $result;
?>
