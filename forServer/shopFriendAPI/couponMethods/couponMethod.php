<?php
	header('Content-Type: text/html; charset=utf8');
	define('ImageLocation', "/mysite/shopFriendDatabase/shopDatabase/"); 
	require('/mysite/shopFriend/connectDB.php');
	class couponMethods
	{
		public function DBMethods()
		{
			$connect=new connectDB();
			$db=$connect->connnectWithDB();
			return $db;
		}
		public function getCoupon($userID)
		{
			$db=$this->DBMethods();
			$sql="select * from Coupon where user_ID='".$userID."' and Coupon_used=1";
			$stmt=$db->query($sql);
			$result=$stmt->fetchall();
			$nowDate=date('Y-m-d');
			for($i=0;$i<count($result);$i++)
			{
				$newSql="select * from CouponModel where CouponModel_ID='".$result[$i]['CouponModel_ID']."' and CouponModel_EndTime>='".$nowDate."'";
				$newStmt=$db->query($newSql);
				$rs=$newStmt->fetchall();
				$result[$i]['CouponModel']=$rs[0];
			}
			$callBack=array();
			$callBack['back']=1;
			$callBack['coupon']=$result;
			return json_encode($callBack);
		}
		public function getCouponShopList($couponID)
		{
			$db=$this->DBMethods();
			$sql="select s.shop_ID,s.shop_name,s.shop_address from (select shop_ID from Coupon as c,CouponModel_Shop as s where c.CouponModel_ID=s.CouponModel_ID and c.Coupon_ID='".$couponID."') as t,shop as s where s.shop_ID=t.shop_ID";
			$stmt=$db->query($sql);
			$rs=$stmt->fetchall();
			$callBack=array();
			$callBack['back']=1;
			$callBack['shopID']=$rs;
			return json_encode($callBack);
		}
		public function addCouponModel($info)
		{
		try{
			$db=$this->DBMethods();
			$shopID=$info['shopID'];
			$name=$info['name'];
			$info=$info['info'];
			$beginTime=$info['beginTime'];
			$endTime=$info['endTime'];
			$useInfo=$info['userInfo'];
			$image=$info['image'];
			$imageLocation=constant("ImageLocation").$shopID."/coupon/".md5($info).".jpg";
			$sql;
			if(!empty($beginTime))
			{
				$sql="insert into CouponModel (CouponModel_ID,CouponModel_Name,CouponModel_Info,CouponModel_BeginTime,CouponModel_EndTime,CouponModel_useInfo,CouponModel_Image) values (null,'".$name."','".$info."','".$beginTime."','".$endTime."','"$userInfo"','"$imageLocation"')";
			}else{
				$sql="insert into CouponModel (CouponModel_ID,CouponModel_Name,CouponModel_Info,CouponModel_EndTime,CouponModel_useInfo,CouponModel_Image) values (null,'".$name."','".$info."','".$endTime."','"$userInfo"','"$imageLocation"')";
			}
			$db->query($sql);
			$couponSql="SELECT LAST_INSERT_ID()";
			$stmt=$db->query($couponSql);
			$result=$stmt->fetchall();
			$couponID=$result[0][0];
			if(!empty($couponID))
			{
				move_uploaded_file($image,$imageLocation);
				$couponShopSql="insert into CouponModel_Shop values('".$couponID."','".$shopID."')";
				$db->query($sql);
			}
			$callBack=array("back"=>1,"CouponID"=>$couponID);
			$json=json_encode($callBack);
			return $json;
		}catch(Exception $e)
		{
			$callBack=array("back"=>0,"info"=$e);
				$json=json_encode($callBack);
				return $json;
		}
		}
	}
?>