<?php
	require_once('/mysite/shopFriend/connectDB.php');
	require_once('/mysite/shopFriend/pushMethods/pushMethod.php');
	class OrderMethods
	{
		public function DBMethods()
		{
			$connect=new connectDB();
			$db=$connect->connnectWithDB();
			return $db;
		}
		public function newOrder($info)
		{
			try{
			$db=$this->DBMethods();
			//data
			$shopID=$info['shop_ID'];
			$userID=$info['user_ID'];
			$serverID=$info['server_ID'];
			$orderDetailArray=json_decode($info['orderDetail'],true);
			//
			date_default_timezone_set("Asia/Shanghai");
			$time=date('Y-m-d h:i:sa');
			if(empty($shopID)||empty($userID)||empty($serverID))
			{
				return ;
			}
			$sql="insert into OrderTable (order_ID,order_createtime,server_ID,user_ID,shop_ID,order_status) values (null,'".$time."','".$serverID."','".$userID."','".$shopID."',1)";
			$db->query($sql);
			$orderSQL="SELECT LAST_INSERT_ID()";
			$stmt=$db->query($orderSQL);
			$result=$stmt->fetchall();
			$orderID=$result[0][0];
			if(!empty($orderID))
			{
				for($i=0;$i<count($orderDetailArray);$i++)
				{
			
					$good_ID=$orderDetailArray[$i]['good_ID'];
					$good_number=$orderDetailArray[$i]['good_number'];
					$good_price=$orderDetailArray[$i]['good_price'];
					$orderDetailSQL="insert into OrderDetail (order_ID,good_ID,good_number,good_price) values ('".$orderID."','".$good_ID."','".$good_number."','".$good_price."')";
					$db->query($orderDetailSQL);
				}
			}
			$push=new pushMethods;
			$message="收到一新订单";
			$back=$push->orderShopPush($shopID,$message,"newOrder",$orderID);
			if($back)
			{
				$backArray=array("back"=>1,"orderID"=>$orderID);
				return json_encode($backArray);
			}
			}catch(Exception $e)
			{
				$backArray=array("back"=>0);
				return json_encode($backArray);
			}
		}
		public function updateOrder($info)
		{
			try{
			$db=$this->DBMethods();
			//data
			$infoFrom=$info['from'];
			$infoTo=$info['to'];
			$orderID=$info['order_ID'];
			$kind=$info['kind'];
			$orderStatus=$info['order_status'];
			//web 
			if(empty($infoTo)||empty($kind)||empty($orderID)||empty($infoFrom))
			{
				return;
			}
			$sql="update OrderTable set order_status='".$orderStatus."' where order_ID='".$orderID."'";
			$db->query($sql);
			$push=new pushMethods;
			$message="订单状态更新";
			$back;
			if($kind=="shop")
			{
			$back=$push->orderUserPush($infoTo,$message,"OrderUpdate",$orderID);
			}else{
			$back=$push->orderShopPush($infoTo,$message,"OrderUpdate",$orderID);
			}
			if($back)
			{
				$backArray=array("back"=>1);
				return json_encode($backArray);
			}
			}catch(Exception $e){
				$backArray=array("back"=>0);
				return json_encode($backArray);
			}
		}
		public function orderUserShow($userID)
		{
			try{
			$db=$this->DBMethods();
			//data
			$sql="select * from OrderTable where user_ID='".$userID."'";
			$stmt=$db->query($sql);
			$result=$stmt->fetchObject();
			$backArray=array("back"=>1,"order"=>$result);
			return json_encode($backArray);
			}catch(Exception $e)
			{
			$backArray=array("back"=>0);
			return json_encode($backArray);
			}
		}
		public function orderShopShow($shopID)
		{
			try{
			$db=$this->DBMethods();
			//data
			$sql="select * from OrderTable where shop_ID='".$shopID."'";
			$stmt=$db->query($sql);
			$result=$stmt->fetchObject();
			$backArray=array("back"=>1,"order"=>$result);
			return json_encode($backArray);
			}catch(Exception $e)
			{
			$backArray=array("back"=>0);
			return json_encode($backArray);
			}
		}
		public function getOrder($orderID)
		{
			try{
				$db=$this->DBMethods();
				//
				if(empty($orderID))
				{
					return ;
				}
				$sql="select * from OrderTable,OrderDetail,ServerTable where OrderTable.server_ID=ServerTable.server_ID and OrderTable.order_ID=OrderDetail.order_ID and OrderTable.order_ID='".$orderID."'";
				$stmt=$db->query($sql);
				$result=$stmt->fetchObject();
				$backArray=array("back"=>1,"order"=>$result);
				return json_encode($backArray);
			}catch(Exception $e)
			{
				$backArray=array("back"=>0);
				return json_encode($backArray);
			}
		}
	}
?>