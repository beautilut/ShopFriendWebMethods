<?php
	require(dirname( __FILE__ ).'/serverMethod.php');
	$serverMethod=new ServerMethods;
	$info=array();
	$info['shopID']=$_POST['shopID'];
	$info['serverID']=$_POST['serverID'];
	$info['range']=$_POST['range'];
	$info['serverInfo']=$_POST['serverInfo'];
	$result=$serverMethod->changeServerInfo($info);
	echo $result;
?>