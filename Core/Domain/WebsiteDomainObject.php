<?php
	namespace YageCMS\Core\Domain;
	
	use YageCMS\Core\Domain\Website;
	use YageCMS\Core\Tools\LogManager;
	use YageCMS\Core\Exception\SetterNotDeclaredException;
	use YageCMS\Core\Exception\GetterNotDeclaredException;
	
	abstract class WebsiteDomainObject extends DomainObject
	{
		  //
		 // ATTRIBUTES
		//
		
		private /*(Website)*/ $website;
		
		  //
		 // METHODS
		//
		
		public function Create()
		{
			$this->Website = Website::GetCurrentWebsite();
			parent::Create();
		}
		
		public function VarDump($html = true)
		{
			$dump = parent::VarDump($html);
			
			if($html)
			{
				$hostname = (!is_null($this->Website) ? $this->Website->Hostname : "Not assigned");
				
				$dump .= "<p><strong>Website:</strong> ".$hostname."</p>";
			}
			else
			{
				
			}
			
			return $dump;
		}
		
		  //
		 // GETTERS/SETTERS
		//
		
		# Website
		
		private function GetWebsite()
		{
			return $this->website;
		}
		
		/**
		 * @param \YageCMS\Core\Domain\Website $value
		 */
		private function SetWebsite(Website $value)
		{
			$this->website = $value;
		}
		
	}
?>