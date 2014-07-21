<?php
	require(dirname( __FILE__ ).'/shopMethods.php');
	$image=$_FILES['shopLogo']['tmp_name'];
	$shopID=$_POST['shopID'];
	$shopMethod=new shopMethods;
	$result=$shopMethod->changeShopLogo($image,$shopID);
	echo $result;
?>