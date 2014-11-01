<?php
	namespace YageCMS\Core\Domain;
	
	use \YageCMS\Core\Tools\ConfigurationManager;
	
	class URIHandler extends WebsiteDomainObject
	{
		  //
		 // ATTRIBUTES
		//
		
		private /*(string)*/ $name;
		private /*(string)*/ $pattern;
		private /*(string)*/ $method;
		private /*(string)*/ $handler;
		private /*(string)*/ $position;
		
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
		
		# Pattern
		
		private function GetPattern()
		{
			return $this->pattern;
		}
		
		private function SetPattern($value)
		{
			$this->pattern = $value;
		}
		
		# Method
		
		private function GetMethod()
		{
			return $this->method;
		}
		
		private function SetMethod($value)
		{
			$this->method = $value;
		}
		
		# Handler
		
		private function GetHandler()
		{
			return $this->handler;
		}
		
		private function SetHandler($value)
		{
			$this->handler = $value;
		}
		
		# Position
		
		private function GetPosition()
		{
			return $this->position;
		}
		
		private function SetPosition($value)
		{
			$this->position = $value;
		}
		
	}
?>