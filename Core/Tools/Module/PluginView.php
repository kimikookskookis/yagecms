<?php
	namespace YageCMS\Core\Tools\Module;
	
	use \YageCMS\Core\Tools\StringTools;
	use \YageCMS\Core\DomainAccess\ModuleAccess;
	use \YageCMS\Core\Tools\EventManager;
	use \YageCMS\Core\Tools\RequestHeader;
	
	class PluginView extends View
	{
		  //
		 // FUNCTIONS
		//
		
		public static function CallModulePlugin($module, $plugin)
		{
			
			$class = "\\YageCMS\\Modules\\".$module."\\Plugins\\".$plugin."\\Plugin";
			$class = new \ReflectionClass($class);
			
			$plugin = $class->newInstance();
			
			self::SetCurrentModuleView($moduleView);
			
			$method = "Do";
			
			$result = $plugin->$method();
			
			if(is_null($result))
			{
				$result = $plugin->Output;
			}
			
			return $result;
		}
	}
?>