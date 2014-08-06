<?php
	require(dirname( __FILE__ ).'/pushMethod.php');
	$push=new pushMethods;
	$message="收到一新订单";
	$back=$push->orderShopPush("18964712120",$message);
?>