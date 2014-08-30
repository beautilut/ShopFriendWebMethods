<?php
	require(dirname( __FILE__ ).'/couponMethod.php');
	$couponMethod=new couponMethods;
	$info=array();
	$info['shop_ID']=$_POST['shop_ID'];
	$info['CouponModel_Name']=$_POST['couponModel_Name'];
	$info['CouponModel_Info']=$_POST['couponModel_Info'];
	$info['CouponModel_BeginTime']=$_POST['couponModel_BeginTime'];
	$info['CouponModel_EndTime']=$_POST['couponModel_EndTime'];
	$info['CouponModel_useInfo']=$_POST['couponModel_useInfo'];
	if(!empty($_FILES["couponModel_Image"]['tmp_name']))
	{
	$info['CouponModel_Image']=$_FILES["couponModel_Image"]['tmp_name'];
	}
	$result=$couponMethod->addCouponModel($info);
	echo $result;
?>