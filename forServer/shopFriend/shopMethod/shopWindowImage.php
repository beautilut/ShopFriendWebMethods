<?php
	require(dirname( __FILE__ ).'/shopMethods.php');
	$shopMethod=new shopMethods;
	$result=$shopMethod->showShopWindowImages($_POST['shopID']);
	echo json_encode($result);
?>