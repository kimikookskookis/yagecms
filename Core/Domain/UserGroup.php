<?php
	namespace YageCMS\Core\Domain;
	
	use \YageCMS\Core\Tools\ConfigurationManager;
	use \YageCMS\Core\Tools\RequestHeader;
	use \YageCMS\Core\DomainAccess\UserGroupAccess;
	use \YageCMS\Core\DomainAccess\PermissionAccess;
	
	class UserGroup extends WebsiteDomainObject
	{
		  //
		 // ATTRIBUTES
		//
		
		private /*(string)*/ $name;
		
		private /*(array<string, boolean>)*/ $permissions;
		
		  //
		 // METHODS
		//
		
		public function HasPermission($permission)
		{
			$permissions = $this->Permissions;
			
			if(!array_key_exists($permission, $this->permissions))
				return false;
			
			return $this->permissions[$permission];
		}
		
		  //
		 // GETTERS/SETTERS
		//
		
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
		
		# Permissions
		
		private function GetPermissions()
		{
			if($this->IsPersistent && is_null($this->permissions))
			{
				$this->permissions = array();
				
				try
				{
					$permissions = PermissionAccess::Instance()->GetByUserGroup($this);
					
					if(count($permissions))
					{
						foreach($permissions as $permission)
						{
							$this->permissions[$permission->Name] = $permission->Value;
						}
					}
				}
				catch(\Exception $e)
				{
					// ignore
				}
			}
			
			return $this->permissions;
		}
		
		private function SetPermissions()
		{
			throw new Exception("AAA");
		}
		
		  //
		 // FUNCTIONS
		//
		
		public static function GetGuestUserGroup()
		{
			$groupid = ConfigurationManager::Instance()->GetParameter("YageCMS.Core.GuestUserGroup");
			
			$group = UserGroupAccess::Instance()->GetByID($groupid);
			
			return $group;
		}
	}
?>