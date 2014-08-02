<?php
	require(dirname( __FILE__ ).'/menu.php');
	$info=array();
	$info['categoryID']=$_POST['categoryID'];
	$info['array']=json_decode($_POST['array']);
	$menuMethod=new MenuMethods;
	$result=$menuMethod->menuRank($info);
	echo $result;
?>