<?php
	require(dirname( __FILE__ ).'/otherMethod.php');
	$Method=new otherMethod;
	$result=$Method->getInvitation($_POST['phoneNumber']);
?>