<?php
	require(dirname( __FILE__ ).'/otherMethod.php');
	$Method=new otherMethod;
	$result=$Method->checkInvitation($_POST['phoneNumber'],$_POST['invitationNumber'],$_POST['kind']);
	echo json_encode($result);
?>