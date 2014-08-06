<?php
	require(dirname( __FILE__ ).'/shopMethods.php');
	$info=array();
	$info['shopID']=$_POST['shopID'];
	$info['number']=$_POST['number'];
	$info['image']=$_FILES['image']['tmp_name'];
	$shopMethod=new shopMethods;
	$result=$shopMethod->changeShopWindowImage($info);
	echo $result;
?>