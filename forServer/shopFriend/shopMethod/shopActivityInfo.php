<?php
	require(dirname( __FILE__ ).'/shopMethods.php');
	$shopMethod=new shopMethods;
	$result=$shopMethod->shopActivityInfo($_POST['shopID']);
	echo $result;
?>