<?php
	require(dirname( __FILE__ ).'/OrderMethods.php');
	$info=array();
	$info['from']=$_POST['from'];
	$info['to']=$_POST['to'];
	$info['order_ID']=$_POST['order_ID'];
	$info['kind']=$_POST['kind'];
	$info['order_status']=$_POST['order_status'];
	$orderMethod=new OrderMethods;
	$result=$orderMethod->updateOrder($info);
	echo $result;
?>