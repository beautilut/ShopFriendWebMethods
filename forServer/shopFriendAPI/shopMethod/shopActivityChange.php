<?php
	require(dirname( __FILE__ ).'/shopMethods.php');
	$info=array();
	$info['shopID']=$_POST['shopID'];
	$info['activityInfo']=$_POST['activityInfo'];
	$shopMethod=new shopMethods;
	$result=$shopMethod->shopActivityChange($info);
	echo $result;
?>