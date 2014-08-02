<?php
	$time=date('YmdHis');
	$from=$_POST['from'];
	$to=$_POST['to'];
	$imageName=$time.$from.$to;
	$image=$_FILES['file']['tmp_name'];
	move_uploaded_file($image,"/mysite/res/".$imageName.".jpg");
	if(!empty($image))
	{		
	$back=array();
	$back['name']=$imageName;
	echo json_encode($back);
	}else
	{
	$back=array();
	$back['name']='fail';
	echo json_encode($back);
	}
?>
