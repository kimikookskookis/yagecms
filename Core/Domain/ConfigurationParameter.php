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
		
		/**
		 * @return string
		 */
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
		
		/**
		 * @return string
		 */
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
		 * @param string $value
		 */
		private function SetValue($value)
		{
			$this->value = $value;
		}
		
	}
?>