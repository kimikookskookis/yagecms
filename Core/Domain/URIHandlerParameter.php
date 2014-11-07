<?php
	namespace YageCMS\Core\Domain;
	
	use \YageCMS\Core\Tools\ConfigurationManager;
	
	class URIHandlerParameter extends WebsiteDomainObject
	{
		  //
		 // ATTRIBUTES
		//
		
		private /*(URIHandler)*/ $urihandler;
		private /*(string)*/ $name;
		private /*(string)*/ $pattern;
		
		  //
		 // CONSTRUCTOR
		//
		
		public function __construct($name = null, $pattern = null)
		{
			$this->name = $name;
			$this->pattern = $pattern;
		}
		
		  //
		 // GETTERS/SETTERS
		//
		
		# URIHandler
		
		/**
		 * @return \YageCMS\Core\Domain\URIHandler
		 */
		private function GetURIHandler()
		{
			return $this->urihandler;
		}
		
		/**
		 * @param \YageCMS\Core\Domain\URIHandler $value
		 */
		private function SetURIHandler(URIHandler $value)
		{
			$this->urihandler = $value;
		}
		
		# Name
		
		/**
		 * @return string
		 */
		private function GetName()
		{
			return $this->name;
		}
		
		/**
		 * @param string $value
		 */
		private function SetName($value)
		{
			$this->name = $value;
		}
		
		# Pattern
		
		/**
		 * @return string
		 */
		private function GetPattern()
		{
			return $this->pattern;
		}
		
		/**
		 * @param string $value
		 */
		private function SetPattern($value)
		{
			$this->pattern = $value;
		}
		
	}
?>