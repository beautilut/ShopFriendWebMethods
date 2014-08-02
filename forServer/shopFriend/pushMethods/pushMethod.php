<?php
	require('/mysite/shopFriend/connectDB.php');
	class pushMethods
	{
		public function DBMethods()
		{
			$connect=new connectDB();
			$db=$connect->connectWithDB();
			return $db;
		}	
		public function myPush($info)
		{
			
		}
		public function myPushTest($info)
		{
			$deviceToken=$info['deviceToken'];
			$body=$info['body'];
			$ctx=stream_context_create();
			$pem="/key/ck.pem";
			stream_context_set_option($ctx,"ssl","local_cert",$pem);
			$pass="241495174";
			stream_context_set_option($ctx,'ssl','passphrase',$pass);
			$fp=stream_socket_client("ssl://gateway.sandbox.push.apple.com:2195",$err,$errstr,60,STREAM_CLIENT_CONNECT,$ctx);
			if(!$fp)
			{
			return false;
			}
			//print "连接OK\n";
			$payload=json_encode($body);
			$msg=chr(0).pack("n",32).pack("H*)",str_replace('','',$deviceToken)).pack("n",strlen($payload)).$payload;
			fwrite($fp,$msg);
			fclose($fp);
			return true;
		}
		public function orderShopPush($shopID,$message)
		{
			$body=array("aps"=>array("alert"=>$message),"badge"=>1,"sound"=>'default');
			$deviceToken=$this->getDeviceToken($shopID,"shop");
			$info=array("body"=>$body,"deviceToken"=>$deviceToken);
			$work=$this->myPushTest($info);
			if($work)
			{
				return true;
			}else{
				return false;
			}
		}
		public function orderUserPush($userID,$message)
		{
			$body=array("aps"=>array("alert"=>$message),"badge"=>1,"sound"=>'default');
			$deviceToken=$this->getDeviceToken($userID,"user");
			$info=array("body"=>$body,"deviceToken"=>$deviceToken);
			$work=$this->myPushTest($info);
			if($work)
			{
				return true;
			}else{
				return false;
			}
		}
		public function getDeviceToken($id,$kind)
		{
			$db=$this->DBMethods();
			$sql;
			if($kind=="shop")
			{
				$sql="select Device_token from DeviceToken where shop_ID='".$id."'";
			}else{
				$sql="select Device_token from DeviceToken where user_ID='".$id."'";
			}
			$stmt=$db->query($sql);
			$result=$stmt->fetchall();
			$deviceToken=$result[0]['Device_token'];
			return $deviceToken;
		}
	}
?>