<?php
	namespace YageCMS\Core\Tools;
	
	class Module
	{
		public static function CallModule($module, $action)
		{
			echo "Call Module ".$module."->".$action;
			
			return 1234;
		}
	}
?>