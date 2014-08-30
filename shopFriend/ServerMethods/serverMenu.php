<?php
	require(dirname( __FILE__ ).'/serverMethod.php');
	$info=array();
	$info['shopID']=$_POST['shopID'];
	$info['goodID']=$_POST['goodID'];
	$server=new ServerMethods;
	$result=$server->GoodMenuGet($info);
	echo $result;
?>