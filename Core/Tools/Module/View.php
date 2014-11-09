<?php
	namespace YageCMS\Core\Tools\Module;
	
	use \YageCMS\Core\Tools\StringTools,
	    \YageCMS\Core\DomainAccess\ModuleAccess,
	    \YageCMS\Core\Tools\EventManager,
	    \YageCMS\Core\Tools\RequestHeader,
		\YageCMS\Core\Tools\SortableListItem;
	
	abstract class View implements SortableListItem
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
		
		private function GetOutput()
		{
			return $this->output;
		}
		
		private function SetOutput($value)
		{
			$this->output = $value;
		}
	}
?>