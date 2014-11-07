<?php
	namespace YageCMS\Core\Domain;
	
	use \YageCMS\Core\DomainAccess\WebsiteAccess;
	use \YageCMS\Core\Tools\ConfigurationManager;
	use \YageCMS\Core\Tools\EventManager;
	
	class Website extends DomainObject
	{
		  //
		 // ATTRIBUTES
		//
		
		private /*(string)*/ $hostname;
		
		  //
		 // METHODS
		//
		
		public function VarDump($html = true)
		{
			$dump = parent::VarDump($html);
			
			if($html)
			{
				$dump .= "<p><strong>Hostname:</strong> ".$this->Hostname."</p>";
			}
			else
			{
				
			}
			
			return $dump;
		}
		
		  //
		 // GETTERS/SETTERS
		//
		
		# Hostname
		
		/**
		 * @return string
		 */
		private function GetHostname()
		{
			return $this->hostname;
		}
		
		/**
		 * @param string $value
		 */
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
			
			EventManager::Instance()->TriggerEvent("YageCMS.Core.CurrentWebsiteSet");
		}
		
		public static function DetectHostname()
		{
			$website = WebsiteAccess::Instance()->GetByHostname($_SERVER["HTTP_HOST"]);
			
			if(!is_null($website))
			{
				Website::SetCurrentWebsite($website);
			}
			
			// Import Website specific parameters
			ConfigurationManager::Instance()->LoadConfiguration();
		}
	}
?>