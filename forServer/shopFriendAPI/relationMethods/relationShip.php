<?php
	header('Content-Type: text/html; charset=utf8');
	require('/mysite/shopFriend/connectDB.php');
	class relationShip
	{
		public function DBMethods()
		{
			$connect=new connectDB();
			$db=$connect->connnectWithDB();
			return $db;
		}
		public function USrelationInsert($info)
		{
			$db=$this->DBMethods();
			$userID=$info['userID'];
			$shopID=$info['shopID'];
			
			$sql="SELECT relationship_state FROM user_shop_relationship WHERE user_ID ='".$userID."' AND shop_ID='".$shopID."'";
			$stmt=$db->query($sql);
			$result=$stmt->fetchall();;
			if(!empty($result[0]))
			{
			$sql="UPDATE user_shop_relationship SET relationship_state =1 WHERE user_ID='".$userID."' AND shop_ID='".$shopID."'";
			try{
					$db->query($sql);
					$callBack=array("back"=>1);
					$json=json_encode($callBack);
					return $json;
			}catch(Exception $e){
					$callBack=array("back"=>0);
					$json=json_encode($callBack);
					return $json;
			}
			}
			if(!empty($userID)&&!empty($shopID))
			{
			$sql="INSERT INTO user_shop_relationship (user_ID,shop_ID,relationship_state) VALUES('".$userID."','".$shopID."',1)";
			try{
					$db->query($sql);
					$callBack=array("back"=>1);
					$json=json_encode($callBack);
					return $json;
			}catch(Exception $e){
					$callBack=array("back"=>0);
					$json=json_encode($callBack);
					return $json;
			}
			}
		}
		public function USrelationUpdate($info)
		{
			$db=$this->DBMethods();
			$userID=$info['userID'];
			$shopID=$info['shopID'];
			$flag=$info['flag'];
			if(!empty($userID)&&!empty($shopID))
			{
			$sql="UPDATE user_shop_relationship SET relationship_state ='".$flag."' WHERE user_ID='".$userID."' AND shop_ID='".$shopID."'";
			try{
					$db->query($sql);
					$callBack=array("back"=>1);
					$json=json_encode($callBack);
					return $json;
			}catch(Exception $e){
					$callBack=array("back"=>0);
					$json=json_encode($callBack);
					return $json;
			}
			}
		}
		public function getShopFriendList($userID)
		{
			$db=$this->DBMethods();
			$sql="SELECT shop_ID FROM user_shop_relationship WHERE user_ID ='".$userID."' AND relationship_state>0";
			$stmt=$db->query($sql);
			$userIDArray=$stmt->fetchall();
			$shopInfo=array();
			for($i=0;$i<count($userIDArray);$i++)
			{
				$sql="select shop_ID,shop_name from shop where shop_ID='".$userIDArray[$i]['shop_ID']."'";
				$stmt=$db->query($sql);
				$result=$stmt->fetchall();
				array_push($shopInfo,$result[0]);
			}
			$callBack=array();
			$callBack['back']=1;
			$callBack['shop']=$shopInfo;
			return json_encode($callBack);
		}
		public function getShopFans($shopID)
		{
			$db=$this->DBMethods();
			$sql="SELECT user_ID FROM user_shop_relationship WHERE shop_ID='".$shopID."' AND relationship_state>1";
			$stmt=$db->query($sql);
			$result=$stmt->fetchall();
			$userInfo=array();
			for($i=0;$i<count($result);$i++)
			{
				$sql="select user_ID,user_name,user_sex,user_birthday From user WHERE user_ID='".$result[$i]['user_ID']."'";
				$stmt=$db->query($sql);
				$result=$stmt->fetchall();
				array_push($userInfo,$result[0]);
			}
			$callBack=array();
			$callBack['back']=1;
			$callBack['user']=$userInfo;
			return json_encode($callBack);
		}
		public function deleteShopFriend($shopID,$userID)
		{
			$db=$this->DBMethods();
			$sql="DELETE FROM user_shop_relationship WHERE shop_ID='".$shopID."' AND user_ID='".$userID."'";
			$db->query($sql);
			$callBack=array();
			$callBack['back']=1;
			return json_encode($callBack);
		}
		public function updateUSrelationship($info)
		{
			$userID=$info['userID'];
			$shopID=$info['shopID'];
			$relationState=$info['state'];
			$sql="UPDATE user_shop_relationship SET relationshop_sate=".$relationState." WHERE user_ID='".$userID."' AND shop_ID='".$shopID."'";
			try{
					$db->query($sql);
					$callBack=array("back"=>1);
					$json=json_encode($callBack);
					return $json;
			}catch(Exception $e){
					$callBack=array("back"=>0);
					$json=json_encode($callBack);
					return $json;
			}
		}
		public function uniqueArray($array)
		{
			for($x=0;$x<count($array);$x++)
			{
				$back=array_unique($array[$x]);
				$array[$x]=$back;
			}
			return $array;
		}
	}
?>