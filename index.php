<?php
	require_once "Core/Yage.php";
	require_once "Core/Tools/Classloader.php";
	require_once "Core/Exception/BaseException.php";
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
		
		$code = $e->getMessage();
		
		#if(strlen($code) == 6)
		{
			$code = explode(":",$code);
			$log = $code[0];
			$code = $code[1];
				
			$log = YageCMS\Core\Tools\LogManager::Instance()->GetLog($log);
			
			if($log)
			{
				$item = $log->GetLogItemByCode($code);
			}else
			{
				$item = new YageCMS\Core\Tools\LogItem(1, $e->getMessage());
			}
		}
		
		echo "<h1>Uncaught Exception!</h1>	<p><strong>Type:</strong> ".get_class($e)."<br/><strong>Message:</strong> ".$item->Message."<br/><strong>Code:</strong> ".$item->Code."</p>";
		echo "<p>Line ".$e->getLine()." in ".$e->getFile()."</p>";
		
		echo "<p><pre><code>".print_r($e->getTrace(),true)."</code></pre></p>";
	}
?>