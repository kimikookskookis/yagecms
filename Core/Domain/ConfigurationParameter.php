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
		
		private function SetScope($value)
		{
			$this->scope = $value;
		}
		
		# ScopeValue
		
		private function GetScopeValue()
		{
			return $this->scope;
		}
		
		private function SetScopeValue($value)
		{
			$this->scopevalue = $value;
		}
		
		# Name
		
		private function GetName()
		{
			return $this->name;
		}
		
		private function SetName($value)
		{
			$this->name = $value;
		}
		
		# Value
		
		private function GetValue()
		{
			return $this->value;
		}
		
		private function SetValue($value)
		{
			$this->value = $value;
		}
		
	}
?>