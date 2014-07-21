<?php
	require(dirname( __FILE__ ).'/shopMethods.php');
	$shopMethod=new shopMethods;
	$result=$shopMethod->getShopActivity($_POST['userID']);
	echo json_encode($result);
?>