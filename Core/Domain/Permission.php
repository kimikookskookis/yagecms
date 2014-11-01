<?php
	namespace YageCMS\Core\Domain;
	
	use \YageCMS\Core\Tools\ConfigurationManager;
	use \YageCMS\Core\Domain\UserGroup;
	
	class Permission extends WebsiteDomainObject
	{
		  //
		 // ATTRIBUTES
		//
		
		private /*(UserGroup)*/ $usergroup;
		private /*(string)*/ $name;
		private /*(boolean)*/ $value;
		
		  //
		 // GETTERS/SETTERS
		//
		
		# UserGroup
		
		private function GetUserGroup()
		{
			return $this->usergroup;
		}
		
		private function SetUserGroup(UserGroup $value)
		{
			$this->usergroup = $value;
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