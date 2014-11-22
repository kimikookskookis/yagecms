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
		
		/**
		 * @return string
		 */
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
		
		# Value
		
		/**
		 * @return string
		 */
		private function GetValue()
		{
			return $this->value;
		}
		
		/**
		 * @param object $value
		 */
		private function SetValue($value)
		{
			$this->value = (string) $value;
		}
		
		# Expiration
		
		/**
		 * @return int
		 */
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