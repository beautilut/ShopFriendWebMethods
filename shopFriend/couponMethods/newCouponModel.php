<?php
	require(dirname( __FILE__ ).'/couponMethod.php');
	$couponMethod=new couponMethods;
	$info=array();
	$info['shop_ID']=$_POST['shop_ID'];
	$info['CouponModel_Name']=$_POST['CouponModel_Name'];
	$info['CouponModel_Info']=$_POST['CouponModel_Info'];
	$info['CouponModel_BeginTime']=$_POST['CouponModel_BeginTime'];
	$info['CouponModel_EndTime']=$_POST['CouponModel_EndTime'];
	$info['CouponModel_useInfo']=$_POST['CouponModel_useInfo'];
	if(!empty($_FILES["CouponModel_Image"]['tmp_name']))
	{
	$info['CouponModel_Image']=$_FILES["CouponModel_Image"]['tmp_name'];
	}
	$result=$couponMethod->addCouponModel($info);
	echo $result;
?>