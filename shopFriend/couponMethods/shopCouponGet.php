<?php
	require(dirname( __FILE__ ).'/couponMethod.php');
	$couponMethod=new couponMethods;
	$result=$couponMethod->shopCouponGet($_POST['shopID']);
	echo $result;
?>