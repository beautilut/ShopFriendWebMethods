<?php
	require(dirname( __FILE__ ).'/menu.php');
	$info=array();
	$info['shopID']=$_POST['shopID'];
	$menuMethod=new MenuMethods;
	$result=$menuMethod->showMenu($info);
	echo $result;
?>	