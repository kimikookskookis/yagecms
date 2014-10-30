<?php
	namespace YageCMS\Core\Tools;
	
	use \YageCMS\Core\Tools\StringTools;
	use \YageCMS\Core\DomainAccess\ModuleAccess;
	
	class ModuleView
	{
		  //
		 // ATTRIBUTES
		//
		
		private /*(string)*/ $title;
		private /*(string)*/ $output;
		
		  //
		 // FUNCTIONS
		//
		
		public static function CallModule($module, $view = "default", $action = "default")
		{
			$module = StringTools::CamelCase($module);
			$view = StringTools::CamelCase($view);
			$action = StringTools::CamelCase($action);
			
			$class = "\\YageCMS\\Modules\\".$module."\\".$view;
			$class = new \ReflectionClass($class);
			
			$moduleView = $class->newInstance();
			
			$viewMethod = "Do".$action;
			
			return $moduleView->$viewMethod();
		}
	}
?>