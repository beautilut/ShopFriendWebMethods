<?php
	require(dirname( __FILE__ ).'/serverMethod.php');
	$server=new ServerMethods;
	$info=array();
	$info['shop_ID']=$_POST['shopID'];
	$info['kind']=$_POST['kind'];
	$result=$server->getShopServer($info);
	echo $result;
?>