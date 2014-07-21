<?php
	require(dirname( __FILE__ ).'/menu.php');
	$info=array();
	$info['shopID']=$_POST['shopID'];
	$info['goodName']=$_POST['goodName'];
	$info['goodPrice']=$_POST['goodPrice'];
	$info['goodCategory']=$_POST['goodCategory'];
	$info['photoCount']=$_POST['photoCount'];
	$info['goodInfo']=$_POST['goodInfo'];
	$array=array();
	for($i=0;$i<$info['photoCount'];$i++)
	{
		$array[$i]=$_FILES["photo".$i]['tmp_name'];
	}
	$info['photo']=$array;
	$menuMethod=new MenuMethods;
	$result=$menuMethod->insertMenu($info);
	echo $result;
?>