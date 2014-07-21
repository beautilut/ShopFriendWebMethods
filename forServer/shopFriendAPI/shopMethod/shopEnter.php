<?php
	require(dirname( __FILE__ ).'/shopMethods.php');
	$info=array();
	$info['shopID']=$_POST['shopID'];
	$info['shopPassword']=$_POST['shopPassword'];
	$shopMethod=new shopMethods;
	$result=$shopMethod->shopEnter($info);
	echo $result;
?>