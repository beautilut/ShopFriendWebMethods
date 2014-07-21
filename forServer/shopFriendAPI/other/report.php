<?php
	require(dirname( __FILE__ ).'/otherMethod.php');
	$info=array();
	$info['couponID']=$_POST['couponID'];
	$info['report']=$_POST['report'];
	$info['reportInfo']=$_POST['reportInfo'];
	$Method=new otherMethod;
	$result=$Method->couponReport($info);
?>