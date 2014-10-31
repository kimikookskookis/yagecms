<?php
	namespace YageCMS\Core\Tools;
	
	use \YageCMS\Core\Tools\StringTools;
	use \YageCMS\Core\DomainAccess\ModuleAccess;
	
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
			
			$result = $moduleView->$viewMethod();
			
			if(is_null($result))
			{
				$result = $moduleView->Output;
			}
			
			return $result;
		}
	}
?>