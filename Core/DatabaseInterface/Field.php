<?php
	namespace YageCMS\Core\DatabaseInterface;
	
	use \DateTime;
	
	class Field
	{
		  //
		 // ATTRIBUTES
		//
		
		private /*(object)*/ $data;
		
		  //
		 // CONSTRUCTOR
		//
		
		public function __construct($data)
		{
			$this->data = $data;
		}
		
		  //
		 // PROPERTIES
		//
		
		public function __get($field)
		{
			switch($field)
			{
				case "IsNull": return $this->GetIsNull();
				case "String": return $this->GetString();
				case "Integer": return $this->GetInteger();
				case "Decimal": return $this->GetDecimal();
				case "Boolean": return $this->GetBoolean();
				case "Generic": return $this->GetGeneric();
				case "Timestamp": return $this->GetTimestamp();
				case "DateTime": return $this->GetDateTime();
			}
		}
		
		  //
		 // GETTERS / SETTERS
		//
		
		private function GetIsNull()
		{
			return (is_null($this->data) ? true : false);
		}
		
		private function GetString()
		{
			return (string) $this->data;
		}
		
		private function GetInteger()
		{
			return (int) $this->data;
		}
		
		private function GetDecimal()
		{
			return (float) $this->data;
		}
		
		private function GetBoolean()
		{
			$data = strtolower($this->GetString());
			
			if(in_array($data, array("false","0","n","no","denied")))
			{
				return false;
			}
			
			return true;
		}
		
		private function GetGeneric()
		{
			return (object) $this->data;
		}
		
		private function GetTimestamp()
		{
			return (int) strtotime($this->GetString());
		}
		
		private function GetDateTime()
		{
			return new DateTime($this->GetString());
		}
	}
?>