
<?php
	class connectDB
	{
		public function connnectWithDB()
		{
		//$link = mysqli_connect('localhost', 'root', '241495174', 'ShopFriend');
    		//mysqli_set_charset($link, "utf8");
		$dsn = "mysql:host=localhost;dbname=ShopFriend";
		$db=new pdo($dsn,'root','241495174',array (PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES 'UTF8';"));
		return $db;
		}
	}
?>
