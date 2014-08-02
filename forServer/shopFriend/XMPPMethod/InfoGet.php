<?php
	require(dirname( __FILE__ ).'/DBMethod.php');
	$info=array();
	$info['postID']=$_POST['postID'];
	$info['kind']=$_POST['kind'];
	$DBMethod=new XMPPMethod;
	$back=$DBMethod->InfoGet($info);
	echo $back;
?>
