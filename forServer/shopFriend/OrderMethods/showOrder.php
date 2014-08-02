<?php
	require(dirname( __FILE__ ).'/OrderMethods.php');
	$kind=$_POST['kind'];
	$orderMethod=new OrderMethods;
	$result;
	if($kind=="user"){
		$result=$orderMethod->orderUserShow($_POST['id']);
	}else if($kind=="shop")
	{
		$result=$orderMethod->orderShopShow($_POST['id']);
	}
	echo $result;
?>