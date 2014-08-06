<?php
	require(dirname( __FILE__ ).'/userMethods.php');
	$info=array();
	$info['userName']=$_POST['userName'];
	$info['userPassword']=$_POST['userPassword'];
	$info['userPhone']=$_POST['userPhone'];
	$info['userImage']=$_FILES['userImage']['tmp_name'];
	$userMethod=new userMethods;
	$userID=$userMethod->userRegister($info);
	echo $userID;
?>
