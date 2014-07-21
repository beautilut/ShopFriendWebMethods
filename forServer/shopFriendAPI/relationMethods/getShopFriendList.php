<?php
	require(dirname( __FILE__ ).'/relationShip.php');
	$relationMethod=new relationShip;
	$result=$relationMethod->getShopFriendList($_POST['userID']);
	echo $result;
?>