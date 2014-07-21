<?php
	require(dirname( __FILE__ ).'/menu.php');
	$info=array();
	$info['shopID']=$_POST['shopID'];
	$info['categoryName']=$_POST['categoryName'];
	$menuMethod=new MenuMethods;
	$result=$menuMethod->insertCategory($info);
	echo $result;
?>