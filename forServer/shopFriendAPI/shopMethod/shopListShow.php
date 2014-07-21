<?php
	require(dirname( __FILE__ ).'/shopMethods.php');
	$info=array();
	$info['userLat']=$_POST['userLat'];
	$info['userLon']=$_POST['userLon'];
	$info['page']=$_POST['page'];
	$info['distance']=$_POST['distance'];
	$shopMethod=new shopMethods;
	$result=$shopMethod->showShopList($info);
	echo $result;
?>