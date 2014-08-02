<?php
	require(dirname( __FILE__ ).'/menu.php');
	$menuMethod=new MenuMethods;
	$result=$menuMethod->deleteCategory($_POST['categoryID'],$_POST['shopID']);
	echo $result;
?>