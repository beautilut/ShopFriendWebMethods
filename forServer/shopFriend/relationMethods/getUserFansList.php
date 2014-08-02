<?php
	require(dirname( __FILE__ ).'/relationShip.php');
	$relationMethod=new relationShip;
	$result=$relationMethod->getShopFans($_POST['shopID']);
	echo $result;
?>