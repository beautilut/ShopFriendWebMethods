<?php
	header('Content-Type: text/html; charset=utf8');
	define('ImageLocation', "/mysite/shopFriendDatabase/userDatabase/"); 
	require('/mysite/shopFriend/connectDB.php');
	class userMethods
	{
		public function DBMethods()
		{
			$connect=new connectDB();
			$db=$connect->connnectWithDB();
			return $db;
		}
		public function userRegister($userInfo)
		{
			$db=$this->DBMethods();
			$userName=$userInfo['userName'];
			if(empty($userName))
			{
			$userName="店小友";
			}
			$userPassword=$userInfo['userPassword'];
			if(empty($userPassword))
			{
			return;
			}
			$userPhone=$userInfo['userPhone'];
			$sql="INSERT INTO user (user_ID,user_name,user_password,user_email,user_phone,user_sex,user_birthday) VALUES(xnull,'".$userName."','".$userPassword."',null,'".$userPhone."',null,null)";
			$db->query($sql);
			$userIDsql="SELECT LAST_INSERT_ID()";
			$stmt=$db->query($userIDsql);
			$result=$stmt->fetchall();
			$userID=$result[0][0];
			$this->useOpenfire($userID,$userPassword,$userName,null);
			//image
			$userImage=$userInfo['userImage'];
			mkdir(constant("ImageLocation").$userID,0777);
			move_uploaded_file($userImage,constant("ImageLocation").$userID."/userImage.jpg");
			$sql="UPDATE invitation set used=true where phone_number='".$userPhone."'";
			$db->query($sql);
			$callBack=array();
			$callBack['userID']=$userID;
			$callBack['back']=1;
			return json_encode($callBack);
		}
		public function userEnter($info)
		{
			$db=$this->DBMethods();
			$userName=$info['userName'];
			$userPassword=$info['userPassword'];
			if(strlen($userName)==11)
			{
					$sql="SELECT user_password,user_ID FROM user WHERE user_phone ='".$userName."'";
			}else{
					$sql="SELECT user_password,user_ID FROM user WHERE user_ID ='".$userName."'";
			}
			$stmt=$db->query($sql);
			$result=$stmt->fetchall();
			if(!empty($userPassword))
			{
				if($userPassword==$result[0][0])
				{
					$callBack=array("back"=>1);
					$callBack['userID']=$result[0][1];
					$json=json_encode($callBack);
					return $json;
				}else{
					$callBack=array("back"=>0);
					$json=json_encode($callBack);
					return $json;
				}
			}
		}
		public function changeUserInfo($info)
		{
			$db=$this->DBMethods();
			$sql="UPDATE user SET ".$info['key']." = '".$info['data']."' WHERE user_ID='".$info['userID']."'";
			$key=$info['key'];
			if($key=='user_name')
			{
				$key='name';
				$this->userOpenfireChange($info['userID'],$key,$info['data']);
			}
			if($key='user_email')
			{
				$key='email';
				$this->userOpenfireChange($info['userID'],$key,$info['data']);
			}
			if($key='user_password')
			{
				$key='password';
				$this->userOpenfireChange($info['userID'],$key,$info['data']);
			}
			$callBack=array();
			try{
			$db->query($sql);
			$callBack['result']=1;
			return json_encode($callBack);
			}catch(Exception $e){
			$callBack['result']=0;
			return json_encode($callBack);
			}
		}
		public function userOpenfireChange($username,$key,$data)
		{
		$f=fopen('http://localhost:9090/plugins/userService/userservice?type=update&secret=241495174&username='.$username.'&'.$key.'='.$data,'r');
		$response=fread($f,1024);
		fclose($f);
		}
		public function changeUserImage($image,$userID)
		{
			if(empty($image)||empty($userID))
			{
				$callBack=array();
				$callBack['back']=0;
				return json_encode($callBack);
			}else
			{
				move_uploaded_file($image,constant("ImageLocation").$userID."/userImage.jpg");
				$callBack=array();
				$callBack['back']=1;
				return json_encode($callBack);
			}
		}
		public function useOpenfire($username,$password,$name,$email)
		{
		$f = fopen('http://localhost:9090/plugins/userService/userservice?type=add&secret=241495174&username='.$username.'&password='.$password.'&name='.$name.'&email='.$email,'r'); 
		$response = fread($f, 1024); 
		fclose($f); 
		}
		
		public function getUserInfo($number)
		{
		 $db=$this->DBMethods();
		 if(empty($number))
		 {
		 $back=array();
		 $back['back']=0;
		 return json_encode($back);
		 }
		 $sql="SELECT user_ID,user_name,user_email,user_phone,user_sex,user_birthday FROM user WHERE user_ID='".$number."'";
		 $stmt=$db->query($sql);
		 $result=$stmt->fetchall();
		 $newResult=$this->uniqueArray($result);
		 $callBackArray=array();
		$callBackArray['back']=1;
		$callBackArray['data']=$newResult;
		return json_encode($callBackArray);
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
