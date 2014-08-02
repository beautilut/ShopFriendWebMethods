<?php
	require(dirname( __FILE__ ).'/couponMethod.php');
	$couponMethod=new couponMethods;
	$result=$couponMethod->getCoupon($_POST['userID']);
	echo $result;
?>