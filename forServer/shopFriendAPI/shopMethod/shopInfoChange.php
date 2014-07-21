<?php
	require(dirname( __FILE__ ).'/shopMethods.php');
	$info=array();
	$info['data1']=$_POST['data'];
	$info['data2']=$_POST['data2'];
	$info['host']=$_POST['host'];
	$info['key']=$_POST['key'];
	$shopMethod=new shopMethods;
	$result=$shopMethod->shopInfoChange($info);
	echo $result;
?>