<?php
	$deviceToken="9f971422ec03a9217a200a2bb69fd7b9cd2da68b576225051d7de685ff87749a";
	$body=array("aps"=>array("alter"=>'收到订单',"badge"=>1,"sound"=>'default'));
	$ctx=stream_context_create();
	$pem="ck.pem";
	stream_context_set_option($ctx,"ssl","local_cert",$pem);
	$pass="241495174";
	stream_context_set_option($ctx,'ssl','passphrase',$pass);
	$fp=stream_socket_client("ssl://gateway.sandbox.push.apple.com:2195",$err,$errstr,60,STREAM_CLIENT_CONNECT,$ctx);
	if(!$fp)
	{
		echo "连接失败";
		return ;
	}
	print "连接OK\n";
	$payload=json_encode($body);
	$msg=chr(0).pack("n",32).pack("H*",str_replace('','',$deviceToken)).pack("n",strlen($payload)).$payload;
	echo "发送消息：".$payload."\n";
	fwrite($fp,$msg);
	fclose($fp);
?>