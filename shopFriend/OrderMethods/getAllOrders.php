<?php
	require(dirname( __FILE__ ).'/OrderMethods.php');
	$orderMethod=new OrderMethods;
	$info=array();
	$info['shop_ID']=$_POST['shop_ID'];
	$result=$orderMethod->getAllOrders($info);
	echo $result;
?>