<?php
	namespace YageCMS\Core\Domain;
	
	use \YageCMS\Core\Tools\ConfigurationManager;
	
	class ConfigurationParameter extends WebsiteDomainObject
	{
		  //
		 // ATTRIBUTES
		//
		
		private /*(string)*/ $scope;
		private /*(string)*/ $scopevalue;
		private /*(string)*/ $name;
		private /*(string)*/ $value;
		
		  //
		 // GETTERS/SETTERS
		//
		
		# Scope
		
		private function GetScope()
		{
			return $this->scope;
		}
		
		/**
		 * @param string $value
		 */
		private function SetScope($value)
		{
			$this->scope = $value;
		}
		
		# ScopeValue
		
		private function GetScopeValue()
		{
			return $this->scope;
		}
		
		/**
		 * @param string $value
		 */
		private function SetScopeValue($value)
		{
			$this->scopevalue = $value;
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
		
	}
?>