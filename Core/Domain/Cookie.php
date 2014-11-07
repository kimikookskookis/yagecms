<?php
	namespace YageCMS\Core\Domain;
	
	use \YageCMS\Core\Tools\ConfigurationManager;
	
	class Cookie extends WebsiteDomainObject
	{
		  //
		 // ATTRIBUTES
		//
		
		private /*(string)*/ $identifier;
		private /*(string)*/ $name;
		private /*(string)*/ $value;
		private /*(int)*/ $expiration;
		
		  //
		 // GETTERS/SETTERS
		//
		
		# Identifier
		
		private function GetIdentifier()
		{
			return $this->identifier;
		}
		
		/**
		 * @param string $value
		 */
		private function SetIdentifier($value)
		{
			$this->identifier = $value;
		}
		
		# Name
		
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
		
		# Value
		
		private function GetValue()
		{
			return $this->value;
		}
		
		/**
		 * @param string $value
		 */
		private function SetValue($value)
		{
			$this->value = $value;
		}
		
		# Expiration
		
		private function GetExpiration()
		{
			return $this->expiration;
		}
		
		/**
		 * @param string/int $value
		 */
		private function SetExpiration($value)
		{
			$this->expiration = $value;
		}
		
	}
?>