<?php
	require(dirname( __FILE__ ).'/userMethods.php');
	$image=$_FILES['userImage']['tmp_name'];
	$userID=$_POST['userID'];
	$userMethod=new userMethods;
	$back=$userMethod->changeUserImage($image,$userID);
	echo $back;
?>
