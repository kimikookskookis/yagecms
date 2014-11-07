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
		
		/**
		 * @return \YageCMS\Core\Domain\UserGroup
		 */
		private function GetUserGroup()
		{
			return $this->usergroup;
		}
		
		/**
		 * @param \YageCMS\Core\Domain\UserGroup $value
		 */
		private function SetUserGroup(UserGroup $value)
		{
			$this->usergroup = $value;
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
		 * @return boolean
		 */
		private function GetValue()
		{
			return $this->value;
		}
		
		/**
		 * @param boolean $value
		 */
		private function SetValue($value)
		{
			$this->value = $value;
		}
		
	}
?>