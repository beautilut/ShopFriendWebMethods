<?php
	require(dirname( __FILE__ ).'/shopMethods.php');
	$info=array();
	$info['shopID']=$_POST['shopID'];
	$info['shopName']=$_POST['shopName'];
	$info['shopPassword']=$_POST['shopPassword'];
	$info['shopCategory']=$_POST['shopCategory'];
	$info['shopCategoryDetail']=$_POST['shopCategoryDetail'];
	$info['shopAddress']=$_POST['shopAddress'];
	$info['shopLat']=$_POST['shopLat'];
	$info['shopLon']=$_POST['shopLon'];
	$info['shopTel']=$_POST['shopTel'];
	$info['shopLogo']=$_FILES['shopLogo']['tmp_name'];
	$shopMethod=new shopMethods;
	$result=$shopMethod->shopRegister($info);
	echo $result;
?>