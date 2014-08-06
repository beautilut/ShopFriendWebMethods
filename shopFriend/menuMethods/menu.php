<?php
	define('ImageLocation', "/mysite/shopFriendDatabase/shopDatabase/"); 
	require('/mysite/shopFriend/connectDB.php');
	class MenuMethods
	{
		public function DBMethods()
		{
			$connect=new connectDB();
			$db=$connect->connnectWithDB();
			return $db;
		}	
		public function insertMenu($info)
		{
			//change
			$db=$this->DBMethods();
			$good_name=$info['goodName'];
			$good_price=$info['goodPrice'];
			$menu_categoryID=$info['goodCategory'];
			$good_photo=$info['photo'];
			$good_info=$info['goodInfo'];
			$shopID=$info['shopID'];
			$imageFile=constant("ImageLocation").$shopID."/menu/".$menu_categoryID;
			$sql="INSERT INTO menu (good_ID,menu_categoryID,good_name,good_price,good_photo_count,good_info,good_onsale) VALUES(null,".$menu_categoryID.",'".$good_name."','".$good_price."',".count($good_photo).",'".$good_info."',true)";
			$db->query($sql);
			$sql="select last_insert_id()";
			$stmt=$db->query($sql);
			$result=$stmt->fetchall();
			$goodID=$result[0][0];
			$image=$imageFile."/".$goodID;
			for($i=0;$i<count($good_photo);$i++)
			{
				move_uploaded_file($good_photo[$i],$image.$i.".jpg");
			}
			try{
					
					$callBack=array("back"=>1,"goodID"=>$goodID);
					$json=json_encode($callBack);
					return $json;
			}catch(Exception $e){
					$callBack=array("back"=>0);
					$json=json_encode($callBack);
					return $json;;
			}
		}
		public function updateMenu($info)
		{
			$db=$this->DBMethods();
			//source
			$good_ID=$info['goodID'];
			$photo_oldCount=$info['oldCount'];
			$good_name=$info['goodName'];
			$good_price=$info['goodPrice'];
			$menu_categoryID=$info['goodCategory'];
			$good_photo=$info['photo'];
			$good_info=$info['goodInfo'];
			$shopID=$info['shopID'];
			//method
			$sql="UPDATE menu SET good_name='".$good_name."',good_price='".$good_price."',good_photo_count='".$count."','".$good_info."' where good_ID='".$good_ID."'";
			try{
				$db->query($sql);
				$imageFile=constant("ImageLocation").$shopID."/menu/".$menu_categoryID;
				$image=$imageFile."/".$good_ID;
				for($i=0;$i<$photo_oldCount;$i++)
				{	
					unlink($image.$i.".jpg");
				}
				for($i=0;$i<count($good_photo);$i++)
				{
					move_uploaded_file($good_photo[$i],$image.$i.".jpg");
				}
				$callBack=array("back"=>1);
				$json=json_encode($callBack);
				return $json;
			}catch(Exception $e){
				$callBack=array("back"=>0);
				$json=json_encode($callBack);
				return $json;
			}
		}
		public function deleteMenu($info){
			$db=$this->DBMethods();
			//souce
			$shopID=$info['shopID'];
			$good_ID=$info['goodID'];
			$menu_categoryID=$info['goodCategory'];
			$count=$info['count'];
			//method
			$sql="DELETE FROM menu where good_ID='".$good_ID."'";
			$imageFile=constant("ImageLocation").$shopID."/menu/".$menu_categoryID;
			$image=$imageFile."/".$good_ID;
			for($i=0;$i<$count;$i++)
			{
				unlink($image.$i.".jpg");
			}
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
		public function showMenu($info)
		{
			$db=$this->DBMethods();
			//baseInfo
			$shopID=$info['shopID'];
			$sql="SELECT menu_categoryID,menu_category,menu_rank FROM menu_category WHERE shop_ID='".$shopID."' order by menu_rank asc";
			$stmt=$db->query($sql);
			$result=$stmt->fetchall();
			$callBackArray['category']=$result;
			for($i=0;$i<count($result);$i++)
			{
				$menuCategory=$result[$i]['menu_categoryID'];
				$sql="SELECT good_ID,good_name,good_price,good_photo_count,good_info,good_rank FROM menu WHERE menu_categoryID='".$menuCategory."' order by good_rank asc";
				$stmt=$db->query($sql);
				$RowResult=$stmt->fetchall();
				$callBackArray[$menuCategory]=$RowResult;
			}
			$callBackArray['kind']="showMenu";
			return json_encode($callBackArray);
		}
		public function menuImageChange($info)
		{
			
		}
		//
		public function insertCategory($info)
		{
			$db=$this->DBMethods();
			$shopID=$info['shopID'];
			$name=$info['categoryName'];
			$sql="INSERT INTO menu_category (shop_ID,menu_categoryID,menu_category) VALUES('".$shopID."',null,'".$name."')";
			$db->query($sql);
			$userIDsql="SELECT LAST_INSERT_ID()";
			$stmt=$db->query($userIDsql);
			$result=$stmt->fetchall();
			$categoryID=$result[0][0];
			$callBack=array("categoryID"=>$categoryID);
			$callBack['categoryName']=$name;
			$callBack['back']=1;
			mkdir(constant("ImageLocation").$shopID."/menu/".$categoryID,0777);
			return json_encode($callBack);
		}
		public function changeCategory($info)
		{
			$db=$this->DBMethods();
			$categoryID=$info['categoryID'];
			$categoryName=$info['categoryName'];
			$sql="UPDATE menu_category SET menu_category='".$categoryName."' WHERE menu_categoryID ='".$categoryID."'";
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
		public function deleteCategory($categoryID,$shopID)
		{
			$db=$this->DBMethods();
			$sql="SELECT good_ID,good_photo_count FROM menu WHERE menu_categoryID='".$categoryID."'";
			$stmt=$db->query($sql);
			$result=$stmt->fetchall();
			$count=count($result);
			for($i=0;$i<$count;$i++)
			{
				$menu=$result[$i];
				$photoCount=$menu['good_photo_count'];
				$imageFile=constant("ImageLocation").$shopID."/menu/".$categoryID;
				$image=$imageFile."/".$menu['good_ID'];
				for($x=0;$x<$photoCount;$x++)
				{
					unlink($image.$x.".jpg");
				}
			}
			$sql="DELETE FROM menu WHERE menu_categoryID='".$categoryID."'";
			$db->query($sql);
			$sql="DELETE FROM menu_category WHERE menu_categoryID='".$categoryID."'";
			try{
					$db->query($sql);
					$callBack=array("back"=>1);
					$callBack['id']=$categoryID;
					$callBack['shop']=$shopID;
					$json=json_encode($callBack);
					return $json;
			}catch(Exception $e){
					$callBack=array("back"=>0);
					$json=json_encode($callBack);
					return $json;
			}
		}
		public function categoryRank($info)
		{
			try{
			$db=$this->DBMethods();
			$shopID=$info['shopID'];
			$categoryArray=$info['array'];
			for($i=0;$i<count($categoryArray);$i++)
			{
				$sql="update menu_category set menu_rank='".$categoryArray[$i][1]."' where shop_ID='".$shopID."' and menu_categoryID='".$categoryArray[$i][0]."'";
				$db->query($sql);
			}
				$callBack=array("back"=>1);
				$json=json_encode($callBack);
				return $json;
			}catch(Exception $e){
				$callBack=array("back"=>0);
				$json=json_encode($callBack);
				return $json;
			}
		}
		public function menuRank($info)
		{
			try{
			$db=$this->DBMethods();
			$categoryID=$info['categoryID'];
			$menuArray=$info['array'];
			for($i=0;$i<count($menuArray);$i++)
			{
				$sql="update menu set good_rank='".$menuArray[$i][1]."' where good_ID='".$menuArray[$i][0]."'";
				$db->query($sql);
			}
				$callBack=array("back"=>1);
				$json=json_encode($callBack);
				return $json;
			}catch(Exception $e){
				$callBack=array("back"=>0);
				$json=json_encode($callBack);
				return $json;
			}
		}
		//
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
