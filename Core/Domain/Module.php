<?php
	namespace YageCMS\Core\Domain;
	
	use \YageCMS\Core\Tools\ConfigurationManager;
	
	class Module extends WebsiteDomainObject
	{
		  //
		 // ATTRIBUTES
		//
		
		private /*(string)*/ $name;
		private /*(string)*/ $location;
		private /*(int)*/ $status;
		
		  //
		 // GETTERS/SETTERS
		//
		
		# Name
		
		private function GetName()
		{
			return $this->name;
		}
		
		private function SetName($value)
		{
			$this->name = $value;
		}
		
		# Location
		
		private function GetLocation()
		{
			switch($this->location)
			{
				case self::LOCATION_CORE: return "CORE";
				case self::LOCATION_GLOBAL: return "GLOBAL";
				case self::LOCATION_LOCAL: return "LOCAL";
			}
		}
		
		private function SetLocation($value)
		{
			switch($value)
			{
				case "CORE": $value = self::LOCATION_CORE; break;
				case "GLOBAL": $value = self::LOCATION_GLOBAL; break;
				case "LOCAL": $value = self::LOCATION_LOCAL; break;
			}
			
			$this->location = $value;
		}
		
		# Status
		
		private function GetStatus()
		{
			switch($this->status)
			{
				case self::STATUS_ACTIVE: return "ACTIVE";
				case self::STATUS_DEACTIVATED: return "DEACTIVATED";
			}
		}
		
		private function SetStatus($value)
		{
			switch($value)
			{
				case "ACTIVE": $value = self::STATUS_ACTIVE; break;
				case "DEACTIVATED": $value = self::STATUS_DEACTIVATED; break;
			}
			
			$this->status = $value;
		}
		
		  //
		 // CONSTANTS
		//
		
		const STATUS_ACTIVE = 1;
		const STATUS_DEACTIVATED = 2;
		
		const LOCATION_CORE = 1;
		const LOCATION_GLOBAL = 2;
		const LOCATION_LOCAL = 4;
	}
?>