<?php
	require(dirname( __FILE__ ).'/menu.php');
	$info=array();
	$info['shopID']=$_POST['shopID'];
	$info['array']=json_decode($_POST['array']);
	$menuMethod=new MenuMethods;
	$result=$menuMethod->categoryRank($info);
	echo $result;
?>