<?php
	require(dirname( __FILE__ ).'/menu.php');
	$info=array();
	$info['categoryID']=$_POST['categoryID'];
	$info['shopID']=$_POST['shopID'];
	$info['categoryName']=$_POST['categoryName'];
	$menuMethod=new MenuMethods;
	$result=$menuMethod->changeCategory($info);
	echo $result;
?>