<?php
	require(dirname( __FILE__ ).'/shopMethods.php');
	$info=array();
	$info['shopID']=$_POST['shopID'];
	$info['number']=$_POST['number'];
	$shopMethod=new shopMethods;
	$result=$shopMethod->shopWindowDelete($info);
	echo $result;
?>