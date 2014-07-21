<?php
	header('Content-Type: text/html; charset=utf8');
	define('ImageLocation', "/mysite/shopFriendDatabase/shopDatabase/"); 
	define('webImageLocation',"http://www.beautilut.com/shopFriendDatabase/shopDatabase/");
	require('/mysite/shopFriend/connectDB.php');
	class shopMethods
	{
		public function DBMethods()
		{
			$connect=new connectDB();
			$db=$connect->connnectWithDB();
			return $db;
		}
		public function shopRegister($shopInfo)
		{
			$db=$this->DBMethods();
			$shopID=$shopInfo['shopID'];
			$shopName=$shopInfo['shopName'];
			$shopPassword=$shopInfo['shopPassword'];
			$shopCategory=$shopInfo['shopCategory'];
			$shopCategoryDetail=$shopInfo['shopCategoryDetail'];
			$shopAddress=$shopInfo['shopAddress'];
			$shopLastLaunchTime=date('Y-m-d');
			$shopLat=$shopInfo['shopLat'];
			$shopLon=$shopInfo['shopLon'];
			$shopTel=$shopInfo['shopTel'];
			$shopLogo=$shopInfo['shopLogo'];
			$sql="INSERT INTO shop (shop_ID,shop_name,shop_password,shop_category,shop_address,shop_state,shop_last_launch_time,shop_lat,shop_lon,shop_tel,shop_category_detail,shop_opening_time) VALUES('".$shopID."','".$shopName."','".$shopPassword."',".$shopCategory.",'".$shopAddress."','1','".$shopLastLaunchTime."',";
			if(!empty($shopLat))
			{
				$sql=$sql.$shopLat.",";
			}else{
				$sql=$sql."null,";
			}
			if(!empty($shopLon))
			{
				$sql=$sql.$shopLon.",";
			}else{
				$sql=$sql."null,";
			}
			if(!empty($shopTel))
			{
				$sql=$sql."'".$shopTel."',";
			}else{
				$sql=$sql."null,";
			}
			if(!empty($shopCategoryDetail))
			{
				$sql=$sql."'".$shopCategoryDetail."',";
			}else
			{
				$sql=$sql."null,";
			}
			$sql=$sql."null)";
			//创建shop用户文件夹
			mkdir(constant("ImageLocation").$shopID,0777);
			mkdir(constant("ImageLocation").$shopID."/menu",0777);
			mkdir(constant("ImageLocation").$shopID."/shopWindow",0777);
			mkdir(constant("ImageLocation").$shopID."/coupon",0777);
			move_uploaded_file($shopLogo,constant("ImageLocation").$shopID."/shopLogo.jpg");
			$this->shopOpenfire($shopID,$shopPassword,$shopName,null);
			try{
					$db->query($sql);
					$sql="insert into shop_activity (shop_ID,shop_activity_info,shop_activity_createTime) Values ('".$shopID."','','')";
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
		public function shopEnter($info)
		{
			$db=$this->DBMethods();
			$shopID=$info['shopID'];
			$shopPassword=$info['shopPassword'];
			$sql="SELECT shop_Password FROM shop WHERE shop_ID='".$shopID."'";
			$stmt=$db->query($sql);
			$result=$stmt->fetchall();
			if(!empty($shopPassword)){
				if($shopPassword==$result[0][0]){
				//change 2.28
					$shopLastLaunchTime=date('Y-m-d');
					$dateSql="UPDATE shop SET shop_last_launch_time='".$shopLastLaunchTime."' WHERE shop_ID='".$shopID."'";
					$db->query($dateSql);
					$callBack=array("back"=>1);
					$json=json_encode($callBack);
					return $json;
				}else{
					$callBack=array("back"=>0);
					$json=json_encode($callBack);
					return $json;
				}
			}
			return nil;
		}
		public function shopInfoChange($info)
		{
			$db=$this->DBMethods();
			$host=$info['host'];
			$theKey=$info['key'];
			$data1=$info['data1'];
			if(!empty($info['data2']))
			{
				$data2=$info['data2'];
			}
			$sql="UPDATE shop SET ".$theKey." ='".$data1."' WHERE shop_ID='".$host."'";
			if($theKey=='shop_name')
			{
				$key='name';
				$shop=$host."shop";
				$this->userOpenfireChange($shop,$key,$data1);
			}
			if($theKey=='shop_password')
			{
				$key='password';
				$shop=$host."shop";
				$this->userOpenfireChange($shop,$key,$data1);
			}
			if($theKey=='shop_category')
			{
				$sql="UPDATE shop SET shop_category='".$data1."',shop_category_detail='".$data2."' WHERE shop_ID='".$host."'";
			}
			$callBack=array();
			try{
			$db->query($sql);
			$callBack['back']=1;
			$callBack['info']=$info;
			return json_encode($callBack);
			}catch(Exception $e){
			$callBack['back']=0;
			return json_encode($callBack);
			}
		}
		public function userOpenfireChange($username,$key,$data)
		{
		$f=fopen('http://localhost:9090/plugins/userService/userservice?type=update&secret=241495174&username='.$username.'&'.$key.'='.$data,'r');
		$response=fread($f,1024);
		fclose($f);
		}
		public function changeShopLogo($image,$shopID)
		{
			move_uploaded_file($image,constant("ImageLocation").$shopID."/shopLogo.jpg");
			$callBack=array();
			$callBack['back']=1;
			return json_encode($callBack);
		}
		public function shopOpenfire($username,$password,$name,$email)
		{
		$f = fopen('http://localhost:9090/plugins/userService/userservice?type=add&secret=241495174&username='.$username.'shop&password='.$password.'&name='.$name.'&email='.$email,'r'); 
		$response = fread($f, 1024); 
		fclose($f); 
		}
		public function showShopList($info)
		{
			$lat=$info['userLat'];
			$lon=$info['userLon'];
			$page=15*$info['page'];
			$distance=$info['distance'];
			if(empty($page))
			{
				$page=0;
			}
			if(empty($distance))
			{
				$distance=10;
			}
			$db=$this->DBMethods();
			$sql="CALL pro_GetNearData('".$lat."','".$lon."','".$page."',15,'".$distance."')";
			$stmt=$db->query($sql);
			$result=$stmt->fetchall();
			$callBackArray=array();
			$callBackArray['back']=1;
			$callBackArray['data']=$result;
			return json_encode($callBackArray);
		}
		public function getShopActivity($userID)
		{
			$db=$this->DBMethods();
			$sql="SELECT shop_ID FROM user_shop_relationship WHERE user_ID ='".$userID."' AND relationship_state>0";
			$stmt=$db->query($sql);
			$userIDArray=$stmt->fetchall();
			$result=array();
			for($i=0;$i<count($userIDArray);$i++)
			{
				$sql="select shop_ID,shop_activity_info from shop_activity where shop_ID='".$userIDArray[$i]['shop_ID']."'";
				$stmt=$db->query($sql);
				$rs=$stmt->fetchall();
				array_push($result,$rs);
			}
			$callBackArray=array();
			$callBackArray['result']=1;
			$callBackArray['shop']=$result;
			return $callBackArray;
		}
		public function getShopInfo($shopID)
		{
			$db=$this->DBMethods();
			$sql="SELECT shop.shop_ID,shop.shop_name,shop.shop_category,shop.shop_category_detail,shop.shop_address,shop.shop_state,shop.shop_last_launch_time,shop.shop_tel,shop.shop_opening_time,shop_activity.shop_activity_info FROM shop,shop_activity WHERE shop.shop_ID='".$shopID."' and shop.shop_ID=shop_activity.shop_ID";
			$stmt=$db->query($sql);
			$result=$stmt->fetchall();
			//$newResult=$this->uniqueArray($result);
			$category=$this->showShopCategory();
			$number=$result[0]['shop_category'];
			$result[0]['shop_category_word']=$category[$number];
			$callBackArray=array();
			$callBackArray['back']=1;
			$callBackArray['data']=$result;
			return json_encode($callBackArray);
		}
		public function showShopWindowImages($shopID)
		{
			$dir=constant("ImageLocation").$shopID."/shopWindow/";
			$imageArray=scandir($dir);
			$imageURL=array();
			for($i=0;$i<count($imageArray);$i++)
			{
				
				if($i>1)
				{
				$imageDir=constant("webImageLocation").$shopID."/shopWindow/".$imageArray[$i];
				array_push($imageURL,$imageDir);
				}
			}
			$callBack=array();
			$callBack['back']=1;
			$callBack['imageURL']=$imageURL;
			return $callBack;
		}
		public function changeShopWindowImage($info)
		{
		try{
			$number=$info['number'];
			$host=$info['shopID'];
			$image=$info['image'];
			$imageURL=constant("ImageLocation").$host."/shopWindow/shopWindow".$number.".jpg";
			unlink($imageURL);
			move_uploaded_file($image,$imageURL);
			$callBack=array("back"=>1);
			$json=json_encode($callBack);
			return $json;
			}catch(Exception $e){
				$callBack=array("back"=>0);
				$json=json_encode($callBack);
				return $json;
			}
		}
		public function shopWindowDelete($info)
		{
			try{
			$number=$info['number'];
			$host=$info['shopID'];
			$imageURL=constant("ImageLocation").$host."/shopWindow/shopWindow".$number.".jpg";
			unlink($imageURL);
			$callBack=array("back"=>1);
			$json=json_encode($callBack);
			return $json;
			}catch(Exception $e){
				$callBack=array("back"=>0);
				$json=json_encode($callBack);
				return $json;
			}
		}
		public function shopActivityInfo($shopID)
		{
			try{
			$db=$this->DBMethods();
			$sql="select shop_activity_info from shop_activity where shop_ID='".$shopID."'";
			$stmt=$db->query($sql);
			$result=$stmt->fetchall();
			$callBackArray=array();
			$callBackArray['back']=1;
			$callBackArray['data']=$result;
			return json_encode($callBackArray);
			}catch(Exception $e){
				$callBack=array("back"=>0,"info"=>$e);
				$json=json_encode($callBack);
				return $json;
			}
		}
		public function shopActivityChange($info)
		{
			try{
				$shopLastLaunchTime=date('Y-m-d');
				$shopID=$info['shopID'];
				$activityInfo=$info['activityInfo'];
				$sql="update shop_activity set shop_activity_info='".$activityInfo."' where shop_ID='".$shopID."'";
				$db=$this->DBMethods();
				$db->query($sql);
				$callBack=array("back"=>1);
				$json=json_encode($callBack);
				return $json;
			}catch(Exception $e)
			{
				$callBack=array("back"=>0,"info"=>$e);
				$json=json_encode($callBack);
				return $json;
			}
		}
		//
		public function showShopCategory()
		{
			$back=array();
			$back[0]='餐饮';
			$back[1]='服饰';
			$back[2]='休闲';
			$back[126]='中介';
			$back[127]='其他';
			$range=array(0,1,2,126,127);
			$back['range']=$range;
			return $back;
		}
		//unique
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
