<?php
	require(dirname( __FILE__ ).'/otherMethod.php');
	$Method=new otherMethod;
	$info=array();
	$info['phone']=$_POST['phoneNumber'];
	$info['kind']=$_POST['kind'];
	$result=$Method->getInvitation($info);
?>