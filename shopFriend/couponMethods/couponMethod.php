<?php
	header('Content-Type: text/html; charset=utf8');
	define('ImageLocation', "/mysite/shopFriendDatabase/shopDatabase/"); 
	define('webImageLocation',"http://www.beautilut.com/shopFriendDatabase/shopDatabase/");
	require('/mysite/shopFriend/connectDB.php');
	class couponMethods
	{
		public function DBMethods()
		{
			$connect=new connectDB();
			$db=$connect->connnectWithDB();
			return $db;
		}
		public function shopCouponGet($shopID)
		{
			$db=$this->DBMethods();
			$sql="select * from couponModel,couponModel_Shop where couponModel.couponModel_ID=couponModel_Shop.couponModel_ID and couponModel_Shop.shop_ID='".$shopID."' and couponModel_EndTime>now() order by couponModel_EndTime asc";
			$stmt=$db->query($sql);
			$result=$stmt->fetchall();
			$callBack=array();
			$callBack['back']=1;
			$callBack['coupon']=$result;
			return json_encode($callBack);
		}
		public function getCoupon($userID)
		{
			$db=$this->DBMethods();
			$sql="select * from coupon where user_ID='".$userID."' and Coupon_used=1";
			$stmt=$db->query($sql);
			$result=$stmt->fetchall();
			$nowDate=date('Y-m-d');
			for($i=0;$i<count($result);$i++)
			{
				$newSql="select * from couponModel where couponModel_ID='".$result[$i]['CouponModel_ID']."' and couponModel_EndTime>='".$nowDate."'";
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
			$sql="select s.shop_ID,s.shop_name,s.shop_address from (select shop_ID from coupon as c,couponModel_Shop as s where c.couponModel_ID=s.couponModel_ID and c.coupon_ID='".$couponID."') as t,shop as s where s.shop_ID=t.shop_ID";
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
			$shopID=$info['shop_ID'];
			$name=$info['CouponModel_Name'];
			$couponinfo=$info['CouponModel_Info'];
			$beginTime=$info['CouponModel_BeginTime'];
			$endTime=$info['CouponModel_EndTime'];
			$useInfo=$info['CouponModel_useInfo'];
			$image=$info['CouponModel_Image'];
			$sql;
			if(!empty($beginTime))
			{
				$sql="insert into couponModel (couponModel_ID,couponModel_Name,couponModel_Info,couponModel_BeginTime,couponModel_EndTime,couponModel_useInfo,couponModel_Kind) values (null,'".$name."','".$couponinfo."','".$beginTime."','".$endTime."','".$useInfo."',1)";
			}else{
				$sql="insert into couponModel (couponModel_ID,couponModel_Name,couponModel_Info,couponModel_EndTime,couponModel_useInfo,couponModel_Kind) values (null,'".$name."','".$couponinfo."','".$endTime."','".$useInfo."',1)";
			}
			$db->query($sql);
			$couponSql="SELECT LAST_INSERT_ID()";
			$stmt=$db->query($couponSql);
			$result=$stmt->fetchall();
			$couponID=$result[0][0];
			if(!empty($couponID))
			{
				if(!empty($image))
				{
				$imageLocation=constant("ImageLocation").$shopID."/coupon/".$couponID.".jpg";
				$webLocation=constant("webImageLocation").$shopID."/coupon/".$couponID.".jpg";
				$imageSql="update couponModel set couponModel_Image='".$webLocation."' where couponModel_ID='".$couponID."'";
					if($db->exec($imageSql))
					{
						move_uploaded_file($image,$imageLocation);
					}
				}
				$couponShopSql="insert into couponModel_Shop (couponModel_ID,shop_ID,couponModel_status,couponModel_shop_number) values('".$couponID."','".$shopID."',2,0)";
				$db->query($couponShopSql);
			}
			$callBack=array("back"=>1,"CouponID"=>$couponID);
			$json=json_encode($callBack);
			return $json;
		}catch(Exception $e)
		{
				$callBack=array("back"=>0,"info"=>$e);
				$json=json_encode($callBack);
				return $json;
		}
		}
		public function useCoupon($info)
		{
			try{
				$db=$this->DBMethods();
				
			}catch(Exception $e){
				$callBack=array("back"=>0,"info"=>$e);
				return json_encode($callBack);
			}
		}
	}
?>