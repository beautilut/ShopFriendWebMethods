<?php
	header('Content-Type: text/html; charset=utf8');
	require_once('/mysite/shopFriend/connectDB.php');
	class pushMethods
	{
		public function DBMethods()
		{
			$connect=new connectDB();
			$db=$connect->connnectWithDB();
			return $db;
		}
		public function myPushTest($info)
		{
			$deviceToken=$info['deviceToken'];
			$body=$info['body'];
			$ctx=stream_context_create();
			$pem=dirname( __FILE__ )."/key/ck.pem";
			stream_context_set_option($ctx,"ssl","local_cert",$pem);
			$pass="241495174";
			stream_context_set_option($ctx,'ssl','passphrase',$pass);
			$fp=stream_socket_client("ssl://gateway.sandbox.push.apple.com:2195",$err,$errstr,60,STREAM_CLIENT_CONNECT,$ctx);
			if(!$fp)
			{
				echo 'connect fail';
				return ;
			}
			$payload=json_encode($body);
			$msg=chr(0).pack("n",32).pack("H*",str_replace('','',$deviceToken)).pack("n",strlen($payload)).$payload;
			fwrite($fp,$msg);
			fclose($fp);
			return true;
		}
		public function orderShopPush($shopID,$message,$api,$data)
		{
			$body=array("aps"=>array("alert"=>$message,"badge"=>1,"sound"=>'default',"api"=>$api,"data"=>$data));
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
		public function orderUserPush($userID,$message,$api,$data)
		{
			$body=array("aps"=>array("alert"=>$message,"badge"=>1,"sound"=>'default',"api"=>$api,"data"=>$data));
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