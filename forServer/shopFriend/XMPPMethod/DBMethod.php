<?php
	header('Content-Type: text/html; charset=utf8');
	require('/mysite/shopFriend/connectDB.php');
	class XMPPMethod
	{
		public function DBMethods()
		{
			$connect=new connectDB();
			$db=$connect->connnectWithDB();
			return $db;
		}
		public function InfoGet($info)
		{
			$postID=$info['postID'];
			$kind=$info['kind'];
			$sql;
			if($kind=="shop")
			{
				$sql="select shop_ID,shop_name from shop where shop_ID='".$postID."'";
			}else
			{
				$sql="select user_ID,user_name from user where user_ID='".$postID."'";
			}
			$db=$this->DBMethods();
			$stmt=$db->query($sql);
			$result=$stmt->fetchall();
			$newResult=$this->uniqueArray($result);
			$callBackArray =array();
			$callBackArray['back']=1;
			$callBackArray['data']=$newResult;
			return json_encode($callBackArray);
				
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