<?php
	namespace YageCMS\Core\Tools\Module;
	
	use \YageCMS\Core\Tools\StringTools;
	use \YageCMS\Core\DomainAccess\ModuleAccess;
	use \YageCMS\Core\Tools\EventManager;
	use \YageCMS\Core\Tools\RequestHeader;
	
	class ModuleView
	{
		  //
		 // ATTRIBUTES
		//
		
		private /*(string)*/ $output;
		
		  //
		 // METHODS
		//
		
		protected function AppendOutput($value)
		{
			$this->output .= $value;
		}
		
		protected function PrependOutput($value)
		{
			$this->output = $value.$this->output;
		}
		
		  //
		 // PROPERTIES
		//
		
		public function __get($field)
		{
			switch($field)
			{
				case "Output": return $this->GetOutput();
			}
		}
		
		public function __set($field, $value)
		{
			switch($field)
			{
				case "Output": $this->SetOutput($value); break;
			}
		}
		
		  //
		 // GETTERS / SETTERS
		//
		
		private function GetTitle()
		{
			return $this->title;
		}
		
		private function SetTitle($value)
		{
			$this->title = $value;
		}
		
		  //
		 // VARIABLES
		//
		
		private static /*(ModuleView)*/ $current;
		
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
		
		public static function CallModule($module, $view = "standard", $action = "default")
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