<?php
	namespace YageCMS\Core\Domain;
	
	class Website extends DomainObject
	{
		  //
		 // ATTRIBUTES
		//
		
		private /*(string)*/ $hostname;
		
		  //
		 // GETTERS/SETTERS
		//
		
		# Hostname
		
		private function GetHostname()
		{
			return $this->hostname;
		}
		
		private function SetHostname($value)
		{
			$this->hostname = $value;
		}
		
		  //
		 // VARIABLES
		//
		
		private static /*(Website)*/ $current;
		
		  //
		 // FUNCTIONS
		//
		
		public static function GetCurrentWebsite()
		{
			return self::$current;
		}
		
		public static function SetCurrentWebsite(Website $value)
		{
			self::$current = $value;
		}
	}
?>