<?php
	require(dirname( __FILE__ ).'/relationShip.php');
	$relationMethod=new relationShip;
	$result=$relationMethod->deleteShopFriend($_POST['shopID'],$_POST['userID']);
	echo $result;
?>