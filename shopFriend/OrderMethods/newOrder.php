<?php
	require(dirname( __FILE__ ).'/OrderMethods.php');
	$info=array();
	$info['shop_ID']=$_POST['shop_ID'];
	$info['shop_name']=$_POST['shop_name'];
	$info['user_ID']=$_POST['user_ID'];
	$info['user_name']=$_POST['user_name'];
	$info['user_location']=$_POST['user_location'];
	$info['server_ID']=$_POST['server_ID'];
	$info['orderDetail']=$_POST['orderDetail'];
	$orderMethod=new OrderMethods;
	$result=$orderMethod->newOrder($info);
	echo $result;
?>