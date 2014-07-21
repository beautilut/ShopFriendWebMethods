<?php
	require(dirname( __FILE__ ).'/couponMethod.php');
	$couponMethod=new couponMethods;
	$result=$couponMethod->getCouponShopList($_POST['couponID']);
	echo $result;
?>