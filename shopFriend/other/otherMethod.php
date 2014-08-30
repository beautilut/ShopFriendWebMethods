<?php
	header('Content-Type: text/html; charset=utf8');
	require('/mysite/shopFriend/connectDB.php');
	class otherMethod
	{
		public function DBMethods()
		{
			$connect=new connectDB();
			$db=$connect->connnectWithDB();
			return $db;
		}
		public function feedBack($info)	
		{
			$db=$this->DBMethods();
			if(!empty($info['userID'])&&!empty($info['feedBackInfo']))
			{
			$sql="INSERT INTO feedBack (feedBackID,user_ID,feedBackInfo) VALUES(null,'".$info['userID']."','".$info['feedBackInfo']."')";
			$db->query($sql);
			$userIDsql="SELECT LAST_INSERT_ID()";
			$stmt=$db->query($userIDsql);
			$result=$stmt->fetchall();
			$feedBackID=$result[0][0];
			if(!empty($feedBackID))
			{
				$callBack=array();
				$callBack['back']=1;
				return $callBack;
			}else{
			$callBack=array();
				$callBack['back']=0;
				return $callBack;
			}
			}
		}
		public function getInvitation($info)
		{
		if(!empty($info))
		{
			$db=$this->DBMethods();
			//data
			$phoneNumber=$info['phone'];
			$kind=$info['kind'];
			$sql;
			if($kind=="shop")
			{
			$sql="select invitation_number,used from shop_invitation where phone_number='".$phoneNumber."'";
			}else{
			$sql="select invitation_number,used from invitation where phone_number='".$phoneNumber."'";
			}	
			echo $sql;
			$stmt=$db->query($sql);
			$result=$stmt->fetchall();
			$array=array();
			if(!empty($result))
			{
				$array['param1']=$result[0]['invitation_number'];
			}else
			{
				$str="1234567890";//abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ";
   				$rndstr;
			    for($i=0;$i<6;$i++)
			    {
    				$rndcode=rand(0,9);
			    	$rndstr.=$str[$rndcode];
    			}	
    			$insertSql;
    			if($kind=="shop")
				{	
				$insertSql="insert into shop_invitation (phone_number,invitation_number,used) Values('".$phoneNumber."','".$rndstr."',false)";
				}else{
				$insertSql="insert into invitation (phone_number,invitation_number,used) Values('".$phoneNumber."','".$rndstr."',false)";
				}
				$db->query($insertSql);
				$array['param1']=$rndstr;
			}
			$url="http://api.189.cn/v2/emp/templateSms/sendSms";
			$param['app_id']="app_id=877477400000035219";
			$accessToken=$this->getAccessToken();
			$param['access_token']="access_token=".$accessToken;
			$timestamp = date("Y-m-d H:i:s",strtotime("+1 hour"));
			$param['timestamp'] = "timestamp=".$timestamp;
			$param['acceptor_tel']="acceptor_tel=".$phoneNumber;
			$param['template_id']="template_id=91000714";
			$param['template_param']="template_param=".json_encode($array);
		    ksort($param);
		    $plaintext = implode("&",$param);
		    $app_secret="4d88d863e59a6785ac4f89182f1e9b6b";
		    $param['sign'] = "sign=".rawurlencode(base64_encode(hash_hmac("sha1", $plaintext, $app_secret, $raw_output=True)));
		    ksort($param);
		    $str = implode("&",$param);
		    $result =$this->curl_post($url,$str);
		    $resultArray = json_decode($result,true);
		    echo $result;
		    }
		}
		function getAccessToken()
		{
			$url="https://oauth.api.189.cn/emp/oauth2/v3/access_token";
			$param['grant_type']="grant_type=client_credentials";
			$param['app_id']="app_id=877477400000035219";
			$param['app_secret']="app_secret=4d88d863e59a6785ac4f89182f1e9b6b";
			ksort($param);
			$str = implode("&",$param);
			$result =$this->curl_post($url,$str);
		    $resultArray = json_decode($result,true);
		    return $resultArray['access_token'];
		}
		function curl_post($url='', $postdata='', $options=array()){
    		$ch = curl_init($url);
		    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		    curl_setopt($ch, CURLOPT_POST, 1);
		    curl_setopt($ch, CURLOPT_POSTFIELDS, $postdata);
		    curl_setopt($ch, CURLOPT_TIMEOUT, 5);
		    if (!empty($options)){
	        curl_setopt_array($ch, $options);
		    }
		    $data = curl_exec($ch);
		    curl_close($ch);
		    return $data;
		}
		function checkInvitation($phoneNumber,$invitationNumber,$kind)
		{
			$db=$this->DBMethods();
			$sql;
			if($kind=="shop")
			{
			$sql="select invitation_number,used from shop_invitation where phone_number='".$phoneNumber."'";
			}else{
			$sql="select invitation_number,used from invitation where phone_number='".$phoneNumber."'";
			}
			$stmt=$db->query($sql);
			$result=$stmt->fetchall();
			$back=array();
			if(!empty($result))
			{
			if(strcmp($result[0]['invitation_number'],$invitationNumber)==0)
			{
				if($result[0]['used']==1)
				{
				$back['result']=0;
				$back['note']="该验证码已被激活";
				}else
				{
					$back['result']=1;
				}
			}else
			{
				$back['result']=0;
				$back['note']="验证码错误";
			}
			}else{
				$back['result']=0;
				$back['note']="此号码尚未拥有验证码";
			}
			return $back;
		}
		function couponReport($info)
		{
			$db=$this->DBMethods();
			$sql="insert into Coupon_report values(null,'".$info['couponID']."','".$info['report']."','".$info['reportInfo']."')";
			$db->qury($sql);
		}
	}
?>