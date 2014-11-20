<?php
	mysql_connect("localhost","root",null);
	mysql_select_db("webshop");
	
	$query = "CALL `getNewID`('test2', '".md5(microtime().mt_rand(12000,24000).mt_rand(8000,16000).mt_rand(32000,64000))."', @p2, @p3);";
	//echo $query;
	echo date("H:i:s");
	mysql_query($query);
	
	mysql_close();
?>