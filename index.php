<?php
	require_once "Core/Yage.php";
	require_once "Core/Tools/Classloader.php";
	require_once "Core/Tools/LogManager.php";
	require_once "Core/Tools/Log.php";
	
	use YageCMS\Core\Tools\Classloader;
	
	try
	{
		function __autoload($class)
		{
			Classloader::Instance()->LoadClass($class);
		}
		
		YageCMS\Core\Yage::Main();
		
	} catch(\Exception $e) {
		echo "<h1>Uncaught Exception!</h1>	<p><strong>Message:</strong> ".$e->getMessage()."</p>";
		echo "<p>Line ".$e->getLine()." in ".$e->getFile()."</p>";
		echo "<p><pre><code>".print_r($e->getTrace(),true)."</code></pre></p>";
	}
?>