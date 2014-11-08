<?php
	namespace YageCMS\Core\Domain;
	
	use \YageCMS\Core\Tools\ConfigurationManager,
	    \YageCMS\Core\Tools\EventManager,
	    \YageCMS\Core\Tools\RequestHeader,
	    \YageCMS\Core\Domain\UserGroup,
	    \YageCMS\Core\Tools\StringTools,
	    \YageCMS\Core\Tools\SaltManager,
	    \YageCMS\Core\Domain\Website,
	    \YageCMS\Core\Domain\Cookie,
	    \YageCMS\Core\DomainAccess\CookieAccess,
	    \YageCMS\Core\DomainAccess\UserAccess,
	    \YageCMS\Core\DomainAccess\UserGroupAccess;
	
	class User extends WebsiteDomainObject
	{
		  //
		 // ATTRIBUTES
		//
		
		/**
		 * @var string
		 */
		private $loginname;
		
		/**
		 * @var string
		 */
		private $password;
		
		/**
		 * @var string
		 */
		private $passwordsalt;
		
		/**
		 * @var int
		 */
		private $lastpasswordchange;
		
		/**
		 * @var string
		 */
		private $emailaddress;
		
		/**
		 * @var array
		 */
		private $usergroups;
		
		  //
		 // METHODS
		//
		
		public function Create()
		{
			$result = parent::Create();
			
			if(!$result) return false;
			
			foreach($this->UserGroups as $group)
			{
				$this->AddToUserGroup($group, true);
			}
			
			return true;
		}
		
		public function Modify()
		{
			$result = parent::Modify();
			
			if(!$result) return false;
			
			foreach($this->UserGroups as $group)
			{
				$this->AddToUserGroup($group, true);
			}
			
			return true;
		}
		
		/**
		 * Iterates over all groups of this user and checks if a certain permission is given
		 * @param string $permission
		 * @return boolean Whether the permission is given
		 */
		public function HasPermission($permission)
		{
			foreach($this->UserGroups as $group)
			{
				$haspermission = $group->HasPermission($permission);
				
				// Stop when finding a group that has this permission
				if($haspermission) return true;
			}
			
			return false;
		}
		
		/**
		 * Add the user to a group
		 * 
		 * @param \YageCMS\Core\Domain\UserGroup $group
		 * @param boolean $save Set to true to write this change to the database immediatelly, else it won't be saved unless you call Modify() on this object
		 */
		public function AddToUserGroup($group, $save = false)
		{
			$this->UserGroups; // Load the original group list
			
			// Check if the user is already in this group
			foreach($this->usergroups as $agroup)
			{
				if($group->ID == $agroup->ID) return false;
			}
			
			$this->usergroups[] = $group;
			
			if($save)
			{
				UserGroupAccess::AddUserToUserGroup($group, $this);
			}
		}
		
		public function VarDump($html = true)
		{
			$dump = parent::VarDump($html);
			
			if($html)
			{
				$dump .= "<p><strong>Loginname:</strong> ".$this->Loginname;
				$dump .= "<br/><strong>Email-Address:</strong> ".$this->EmailAddress."</p>";
			}
			else
			{
				
			}
			
			return $dump;
		}
		
		/*private function ValidatePassword($password)
		{
			
			// Hash with global salt
				$globalSalt = SaltManager::Instance()->GetSalt(time());
				// Generate local salt
				$localSalt = md5(StringTools::GenerateGUID());
				
				$hashedPassword = crypt($password, "\$6\$".$globalSalt);
				$hashedPassword = crypt($hashedPassword, "\$6\$".$localSalt);
				$hashedPassword = md5($hashedPassword);
				
		}*/
		
		  //
		 // GETTERS/SETTERS
		//
		
		# Loginname
		
		/**
		 * @return string
		 */
		private function GetLoginname()
		{
			return $this->loginname;
		}
		
		/**
		 * @param string $value
		 */
		private function SetLoginname($value)
		{
			$this->loginname = $value;
		}
		
		# Password
		
		/**
		 * @return string
		 */
		private function GetPassword()
		{
			return $this->password;
		}
		
		/**
		 * @param string $value
		 */
		private function SetPassword($value)
		{
			$this->password = $value;
		}
		
		# PasswordSalt
		
		/**
		 * @return string
		 */
		private function GetPasswordSalt()
		{
			return $this->passwordsalt;
		}
		
		/**
		 * @param string $value
		 */
		private function SetPasswordSalt($value)
		{
			$this->passwordsalt = $value;
		}
		
		# LastPasswordChange
		
		/**
		 * @return string
		 */
		private function GetLastPasswordChange()
		{
			return date("Y-m-d H:i:s",$this->lastpasswordchange);
		}
		
		/**
		 * @param string/int $value
		 */
		private function SetLastPasswordChange($value)
		{
			if(!is_int($value))
				$value = strtotime($value);
			
			$this->lastpasswordchange = $value;
		}
		
		# EmailAddress
		
		/**
		 * @return string
		 */
		private function GetEmailAddress()
		{
			return $this->emailaddress;
		}
		
		/**
		 * @param string $value
		 */
		private function SetEmailAddress($value)
		{
			$this->emailaddress = $value;
		}
		
		/*
		 * User Groups
		 */
		
		/**
		 * @return array
		 */
		private function GetUserGroups()
		{
			if($this->IsPersistent && is_null($this->usergroups))
			{
				$this->usergroups = array();
			
				try
				{
					$this->usergroups = UserGroupAccess::Instance()->GetByUser($this);
				}
				catch(\Exception $e)
				{
					// ignore
				}
			}
				
			return $this->usergroups;
		}
		
		  //
		 // VARIABLES
		//
		
		/**
		 * @var \YageCMS\Core\Domain\User
		 */
		private static $current;
		
		  //
		 // FUNCTIONS
		//
		
		/**
		 * @return \YageCMS\Core\Domain\User
		 */
		public static function GetCurrentUser()
		{
			return self::$current;
		}
		
		/**
		 * @param \YageCMS\Core\Domain\User $value
		 */
		public static function SetCurrentUser(User $value)
		{
			self::$current = $value;
			
			EventManager::Instance()->TriggerEvent("YageCMS.Core.CurrentUserSet");
		}
		
		public static function SignIn()
		{
			$cookiePrefix = ConfigurationManager::Instance()->GetParameter("CookiePrefix","local");
			
			$idCookie = $cookiePrefix."id";
			$cookies = RequestHeader::Instance()->Cookies;
			
			$cookieIdentifier = null;
			
			if(array_key_exists($idCookie, $cookies))
			{
				$cookieIdentifier = $cookies[$idCookie];
				
				// Get Cookies with the User ID and the Password
				$cookieUserID = CookieAccess::Instance()->GetByIdentifierAndName($cookieIdentifier, "userid");
				$cookieUserPassword = CookieAccess::Instance()->GetByIdentifierAndName($cookieIdentifier, "userpassword");
				
				$userid = $cookieUserID->Value;
				$password = $cookieUserPassword->Value;
				
				$user = UserAccess::Instance()->GetByID($userid);
				
				if($user->Password == $password)
				{
					self::SetCurrentUser($user);	
				}
			}
			else
			{
				$newUser = new User;
				
				$password = md5(StringTools::GenerateGUID());
				
				// Hash with global salt
				$globalSalt = SaltManager::Instance()->GetSalt(time());
				// Generate local salt
				$localSalt = md5(StringTools::GenerateGUID());
				
				$hashedPassword = crypt($password, "\$6\$".$globalSalt);
				$hashedPassword = crypt($hashedPassword, "\$6\$".$localSalt);
				$hashedPassword = md5($hashedPassword);
				
				//$newUser->Website = Website::GetCurrentWebsite();
				$newUser->Loginname = "guest-".strtolower(StringTools::GenerateGUID());
				$newUser->Password = $hashedPassword;
				$newUser->PasswordSalt = $localSalt;
				$newUser->EmailAddress = $newUser->Loginname."@guest.com";
				$newUSer->AddToUserGroup(UserGroup::GetGuestUserGroup());
				
				$newUser->Create();
				
				// Create Cookies
				$userID = $newUser->ID;
				$userPassword = $newUser->Password;
				
				$cookieIdentifier = StringTools::GenerateGUID();
				
				$cookieUserID = new Cookie();
				$cookieUserID->Identifier = $cookieIdentifier;
				$cookieUserID->Name = "userid";
				$cookieUserID->Value = $userID;
				$cookieUserID->Create();
				
				$cookieUserPassword = new Cookie();
				$cookieUserPassword->Identifier = $cookieIdentifier;
				$cookieUserPassword->Name = "userpassword";
				$cookieUserPassword->Value = $userPassword;
				$cookieUserPassword->Create();
				
				setcookie($idCookie, $cookieIdentifier, time()+time());
				
				self::SetCurrentUser($newUser);
			}
			
			// Import User specific parameters
			ConfigurationManager::Instance()->LoadConfiguration();
		}
	}
?>