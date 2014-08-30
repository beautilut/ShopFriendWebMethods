<?php
	define('ServerLocation',"http://www.beautilut.com/mysite/shopFriendDatabase/server/");
	require_once('/mysite/shopFriend/connectDB.php');
	class ServerMethods
	{
		public function DBMethods()
		{
			$connect=new connectDB();
			$db=$connect->connnectWithDB();
			return $db;
		}
		// public function registServer($info)
// 		{
// 			try{
// 				$db=$this->DBMethods();
// 				//data
// 				$shopID=$info['shop_ID'];
// 				$serverID=$info['server_ID'];
// 				$serverRang=$info['server_range'];
// 				//web
// 				$sql="insert into server_shop_detail (server_ID,shop_ID,server_range) values('".$serverID."','".$shopID."','".$serverRange."')";
// 				echo $sql;
// 				if($db->exec($sql))
// 				{
// 					$backArray=array("back"=>1);
// 					return json_encode($backArray);
// 				}else{
// 				$backArray=array("back"=>0);
// 				return json_encode($backArray);
// 				}
// 				
// 			}catch(Exception $e)
// 			{
// 				$backArray=array("back"=>0);
// 				return json_encode($backArray);
// 			}
// 		}
		public function showAllServer()
		{
			try{
				$db=$this->DBMethods();
				$sql="select * from serverTable";
				$stmt=$db->query($sql);
				$result=$stmt->fetchall();
				$imagePath=constant("ServerLocation");
				$backArray=array("back"=>1,"server"=>$result,"imagePath"=>$imagePath);
				return json_encode($backArray);
			}catch(Exception $e)
			{
				$backArray=array("back"=>0);
				return json_encode($backArray);
			}
		}
		// public function exceptShopServer($shopID)
// 		{
// 			try{
// 			$db=$this->DBMethods();
// 			if(empty($shopID)){
// 				return;
// 			}
// 			$sql="select * from serverTable where (server_ID) not in (select server_ID from server_shop_detail where shop_ID='".$shopID."');";
// 			$stmt=$db->query($sql);
// 			$result=$stmt->fetchall();
// 			$imagePath=constant("ServerLocation");
// 			$backArray=array("back"=>1,"server"=>result,"imagePath"=>$imagePath);
// 			}catch(Exception $e)
// 			{
// 				$backArray=array("back"=>0);
// 				return json_encode($backArray);
// 			}
// 		}
		public function getShopServer($info)
		{
			try{
				//data
				$shopID=$info['shop_ID'];
				$kind=$info['kind'];
				$db=$this->DBMethods();
				if (empty($shopID)) {
					return;
				}
				$sql;
				if($kind=="all"){
				$sql="select * from serverTable,server_shop_detail where serverTable.server_ID=server_shop_detail.server_ID and server_shop_detail.shop_ID='".$shopID."'";
				}
				if($kind=="text")
				{
				$sql="select server_name from serverTable,server_shop_detail where serverTable.server_ID=server_shop_detail.server_ID and server_shop_detail.shop_ID='".$shopID."'";
				$stmt=$db->query($sql);
				$result=$stmt->fetchall();
				$backArray=array("back"=>1,"server"=>$result);
				return json_encode($backArray);
				}
				$stmt=$db->query($sql);
				$result=$stmt->fetchall();
				$imagePath=constant("ServerLocation");
				$backArray=array("back"=>1,"server"=>$result,"imagePath"=>$imagePath);
				return json_encode($backArray);
			}catch(Exception $e)
			{
				$backArray=array("back"=>0);
				return json_encode($backArray);
			}
		}
		public function registerServer($info)
		{
			try{
				//data
				$shopID=$info['shop_ID'];
				$serverID=$info['server_ID'];
				//web
				$db=$this->DBMethods();
				if(empty($info))
				{
					return;
				}
				$sql="insert into server_shop_detail (server_ID,shop_ID,server_range,server_shop_info,server_shop_status) values('".$serverID."','".$shopID."','','',2)";
				if($db->exec($sql))
				{
					$backArray=array("back"=>1);
					return json_encode($backArray);
				}else{
					$backArray=array("back"=>0);
					return json_encode($backArray);
				}
			}catch(Exception $e)
			{
				$backArray=array("back"=>0);
				return json_encode($backArray);
			}
		}
		public function changeServerInfo($info)
		{
			try{
				//data
				$shopID=$info['shopID'];
				$serverID=$info['serverID'];
				$range=$info['range'];
				$serverInfo=$info['serverInfo'];
				//web
				$db=$this->DBMethods();
				$sql="update server_shop_detail set server_range='".$range."',server_shop_info='".$serverInfo."' where server_ID='".$serverID."' and shop_ID='".$shopID."'";
				if($db->exec($sql))
				{
					$backArray=array("back"=>1);
					return json_encode($backArray);
				}else{
					$backArray=array("back"=>0);
					return json_encode($backArray);
				}
			}catch(Exception $e)
			{
				$backArray=array("back"=>0);
				return json_encode($backArray);
			}
		}
		public function GoodMenuGet($info)
		{
			try{
			//data
			$goodID=$info['goodID'];
			$shopID=$info['shopID'];
			//web
			$db=$this->DBMethods();
			$serverSql="select * from serverTable,server_shop_detail where serverTable.server_ID=server_shop_detail.server_ID and server_shop_detail.shop_ID='".$shopID."'";
			$stmt=$db->query($serverSql);
			$result=$stmt->fetchall();
			for ($i=0;$i<count($result);$i++)
			{
				$goodDetail="select count(*) from server_menu_detail where good_ID='".$goodID."' and server_ID = '".$result[$i]['server_ID']."'";
				$stmt=$db->query($goodDetail);
				$goodRS=$stmt->fetchall();
				if($goodRS[0]['count(*)']!=0)
				{
					$result[$i]['registed']=true;
				}else{
					$result[$i]['registed']=false;
				}
			}
			$backArray=array("back"=>1,"myServer"=>$result);
			return json_encode($backArray);
			}catch(Exception $e)
			{
				$backArray=array("back"=>0);
				return json_encode($backArray);
			}
		}
	}
?>