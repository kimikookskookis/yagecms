<?php
	namespace YageCMS\Core\Tools;
	
	class URIParameter
	{
		  //
		 // ATTRIBUTES
		//
		
		private /*(string)*/ $name;
		private /*(string)*/ $pattern;
		
		  //
		 // CONSTRUCTOR
		//
		
		public function __construct($name, $pattern)
		{
			$this->name = $name;
			$this->pattern = $pattern;
		}
		
		  //
		 // PROPERTIES
		//
		
		public function __get($field)
		{
			switch($field)
			{
				case "Name": return $this->GetName();
				case "Pattern": return $this->GetPattern();
			}
		}
		
		  //
		 // GETTERS / SETTERS
		//
		
		private function GetName()
		{
			return $this->name;
		}
		
		private function GetPattern()
		{
			return $this->pattern;
		}
	}
?>