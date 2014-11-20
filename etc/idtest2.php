<?php
	mysql_connect("localhost","root",null);
	mysql_select_db("webshop");
	
	$query = "CALL `generateNewSequence`('test2', 100, @p3);";
	//echo $query;
	echo date("H:i:s");
	mysql_query($query);
	
	mysql_close();
?>