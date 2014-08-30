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
			$shopName=$info['shop_name'];
			$userID=$info['user_ID'];
			$userName=$info['user_name'];
			$userLocation=$info['user_location'];
			$serverID=$info['server_ID'];
			$orderTotalPrice;
			$orderDetailArray=json_decode($info['orderDetail'],true);
			//
			date_default_timezone_set("Asia/Shanghai");
			$time=date('Y-m-d h:i:sa');
			if(empty($shopID)||empty($userID)||empty($serverID))
			{
				return ;
			}
			for($i=0;$i<count($orderDetailArray);$i++)
			{
				$piecePrice=$orderDetailArray[$i]['good_price'];
				$orderTotalPrice=$orderTotalPrice+$piecePrice;			
			}
			$sql="insert into orderTable (order_ID,order_createtime,server_ID,user_ID,user_name,user_location,shop_ID,shop_name,order_status,order_total_price) values (null,'".$time."','".$serverID."','".$userID."','".$userName."','".$userLocation."','".$shopID."','".$shopName."',1,'".$orderTotalPrice."')";
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
					$good_name=$orderDetailArray[$i]['good_name'];
					$orderDetailSQL="insert into orderDetail (order_ID,good_ID,good_name,good_number,good_price) values ('".$orderID."','".$good_ID."','".$good_name."','".$good_number."','".$good_price."')";
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
			$sql="update orderTable set order_status='".$orderStatus."' where order_ID='".$orderID."'";
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
			$sql="select * from orderTable where user_ID='".$userID."'";
			$stmt=$db->query($sql);
			$result=$stmt->fetchAll();
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
			$sql="select * from orderTable where shop_ID='".$shopID."'";
			$stmt=$db->query($sql);
			$result=$stmt->fetchAll();
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
				$backArray=array("back"=>1);
				$sql="select * from orderTable,serverTable where orderTable.server_ID=serverTable.server_ID and order_ID='".$orderID."'";
				$stmt=$db->query($sql);
				$backArray['order']=$stmt->fetchObject();
				$sql="select * from orderDetail where order_ID='".$orderID."'";
				$stmt=$db->query($sql);
				$backArray['orderDetail']=$stmt->fetchAll();
				return json_encode($backArray);
			}catch(Exception $e)
			{
				$backArray=array("back"=>0);
				return json_encode($backArray);
			}
		}
		public function getAllOrders($info)
		{
			try{
				$db=$this->DBMethods();
				//data
				$shopID=$info['shop_ID'];
				//
				$backArray=array("back"=>1);
				$sql="select * from orderTable as o,serverTable as s where o.server_ID=s.server_ID and o.shop_ID='".$shopID."' and order_status<3 and order_status !=0 order by order_createtime asc";
				$stmt=$db->query($sql);
				$orderList=$stmt->fetchall();
				for($i=0;$i<count($orderList);$i++)
				{
					$sql="select * from orderDetail where order_ID='".$orderList[$i]['order_ID']."'";
					$stmt=$db->query($sql);
					$result=$stmt->fetchall();
					$orderList[$i]['detail']=$result;
				}
				$backArray['order']=$orderList;
				return json_encode($backArray);
			}catch(Exception $e)
			{
				$backArray=array("back"=>0);
				return json_encode($backArray);
			}
		}
	}
?>