<?php
	require(dirname( __FILE__ ).'/OrderMethods.php');
	$info=array();
	$info['shop_ID']=$_POST['shop_ID'];
	$info['user_ID']=$_POST['user_ID'];
	$info['server_ID']=$_POST['server_ID'];
	$info['orderDetail']=$_POST['orderDetail'];
	$orderMethod=new OrderMethods;
	$result=$orderMethod->newOrder($info);
	echo $result;
?>