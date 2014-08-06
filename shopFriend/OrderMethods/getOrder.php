<?php
	require(dirname( __FILE__ ).'/OrderMethods.php');
	$orderMethod=new OrderMethods;
	$result=$orderMethod->getOrder($_POST['order_ID']);
	echo $result;
?>