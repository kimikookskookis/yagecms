<?php
	namespace YageCMS\Core\Tools\Module;
	
	use \YageCMS\Core\Tools\StringTools,
	    \YageCMS\Core\DomainAccess\ModuleAccess,
	    \YageCMS\Core\Tools\EventManager,
	    \YageCMS\Core\Tools\RequestHeader,
	    \YageCMS\Core\Tools\Module\View;
	
	abstract class ModuleView extends View
	{
		
		  //
		 // VARIABLES
		//
		
		private static /*(ModuleView)*/ $current;
		
		  //
		 // METHODS
		//
		
		public function GetSortableListKey()
		{
			return "YageCMS.Core.ModuleView";
		}
		
		public function LoadSetup()
		{
			// Qualified name of setting for setup
			$settingName = "Views.".$this->GetViewName().".Setup";
			$scope = "module-".$this->GetModuleName();
			
			
		}
		
		public function GetViewName()
		{
			$class = explode("\\",get_called_class());
			
			$view = $class[4];
			
			return $view;
		}
		
		public function GetModuleName()
		{
			$class = explode("\\",get_called_class());
			
			$module = $class[2];
			
			return $module;
		}
		
		  //
		 // FUNCTIONS
		//
		
		public static function GetCurrentModuleView()
		{
			return self::$current;
		}
		
		public static function SetCurrentModuleView(ModuleView $value)
		{
			self::$current = $value;
			
			EventManager::Instance()->TriggerEvent("YageCMS.Core.ModuleViewSet");
		}
		
		public static function CallModuleView($module, $view = "standard", $action = "default")
		{
			if(!is_null(self::$current))
			{
				throw new CannotCallMultipleModuleViewsException();
			}
			
			$module = StringTools::CamelCase($module);
			$view = StringTools::CamelCase($view);
			$action = StringTools::CamelCase($action);
			$httpMethod = RequestHeader::Instance()->RequestMethod;
			
			$class = "\\YageCMS\\Modules\\".$module."\\Views\\".$view."\\View";
			$class = new \ReflectionClass($class);
			
			$moduleView = $class->newInstance();
			
			self::SetCurrentModuleView($moduleView);
			
			$viewMethod = $httpMethod."_Do".$action;
			
			if(!method_exists($moduleView, $viewMethod))
			{
				$viewMethod = "Do".$action;
			}
			
			$result = $moduleView->$viewMethod();
			
			if(is_null($result))
			{
				$result = $moduleView->Output;
			}
			
			return $result;
		}
	}
?>