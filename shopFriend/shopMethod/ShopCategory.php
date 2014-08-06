<?php
	require(dirname( __FILE__ ).'/shopMethods.php');
	$shopMethod=new shopMethods;
	$result=$shopMethod->showShopCategory();
	echo json_encode($result);
?>
