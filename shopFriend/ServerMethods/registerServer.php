<?php
	require(dirname( __FILE__ ).'/serverMethod.php');
	$serverMethod=new ServerMethods;
	$info=array();
	$info['shop_ID']=$_POST['shopID'];
	$info['server_ID']=$_POST['serverID'];
	$result=$serverMethod->registerServer($info);
	echo $result;
?>