<?php
	namespace YageCMS\Core\Domain;
	
	use \YageCMS\Core\Tools\ConfigurationManager;
	use \YageCMS\Core\Tools\RequestHeader;
	use \YageCMS\Core\DomainAccess\UserGroupAccess;
	
	class UserGroup extends WebsiteDomainObject
	{
		  //
		 // ATTRIBUTES
		//
		
		private /*(string)*/ $name;
		
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
		
		  //
		 // FUNCTIONS
		//
		
		public static function GetGuestUserGroup()
		{
			$groupid = ConfigurationManager::Instance()->GetParameter("GuestUserGroup");
			
			$group = UserGroupAccess::Instance()->GetByID($groupid);
			
			return $group;
		}
	}
?>