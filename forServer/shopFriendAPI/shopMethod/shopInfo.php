<?php
	require(dirname( __FILE__ ).'/shopMethods.php');
	$shopMethod=new shopMethods;
	$result=$shopMethod->getShopInfo($_POST['shopID']);
	echo $result;
?>