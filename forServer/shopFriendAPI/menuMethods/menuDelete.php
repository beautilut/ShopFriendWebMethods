<?php
	require(dirname( __FILE__ ).'/menu.php');
	$info=array();
	$info['goodCategory']=$_POST['goodCategory'];
	$info['goodID']=$_POST['goodID'];
	//$info['goodName']=$_POST['goodName'];
	$info['shopID']=$_POST['shopID'];
	$info['count']=$_POST['count'];
	$menuMethod=new MenuMethods;
	$result=$menuMethod->deleteMenu($info);
	echo $result;
?>