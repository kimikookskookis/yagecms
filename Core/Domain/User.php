<?php
	namespace YageCMS\Core\Domain;
	
	use \YageCMS\Core\Tools\ConfigurationManager;
	use \YageCMS\Core\Tools\EventManager;
	use \YageCMS\Core\Tools\RequestHeader;
	use \YageCMS\Core\Domain\UserGroup;
	use \YageCMS\Core\Tools\StringTools;
	use \YageCMS\Core\Tools\SaltManager;
	use \YageCMS\Core\Domain\Website;
	use \YageCMS\Core\Domain\Cookie;
	use \YageCMS\Core\DomainAccess\CookieAccess;
	use \YageCMS\Core\DomainAccess\UserAccess;
	
	class User extends WebsiteDomainObject
	{
		  //
		 // ATTRIBUTES
		//
		
		private /*(string)*/ $loginname;
		private /*(string)*/ $password;
		private /*(string)*/ $passwordsalt;
		private /*(int)*/ $lastpasswordchange;
		private /*(string)*/ $emailaddress;
		private /*(UserGroup)*/ $usergroup;
		
		  //
		 // METHODS
		//
		
		public function HasPermission($permission)
		{
			return $this->usergroup->HasPermission($permission);
		}
		
		public function VarDump($html = true)
		{
			$dump = parent::VarDump($html);
			
			if($html)
			{
				$dump .= "<p><strong>Loginname:</strong> ".$this->Loginname;
				$dump .= "<br/><strong>Email-Address:</strong> ".$this->EmailAddress;
				$dump .= "<br/><strong>Group:</strong> ".$this->UserGroup->Name."</p>";
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
		
		private function GetLoginname()
		{
			return $this->loginname;
		}
		
		private function SetLoginname($value)
		{
			$this->loginname = $value;
		}
		
		# Password
		
		private function GetPassword()
		{
			return $this->password;
		}
		
		private function SetPassword($value)
		{
			$this->password = $value;
		}
		
		# PasswordSalt
		
		private function GetPasswordSalt()
		{
			return $this->passwordsalt;
		}
		
		private function SetPasswordSalt($value)
		{
			$this->passwordsalt = $value;
		}
		
		# LastPasswordChange
		
		private function GetLastPasswordChange()
		{
			return date("Y-m-d H:i:s",$this->lastpasswordchange);
		}
		
		private function SetLastPasswordChange($value)
		{
			if(!is_int($value))
				$value = strtotime($value);
			
			$this->lastpasswordchange = $value;
		}
		
		# EmailAddress
		
		private function GetEmailAddress()
		{
			return $this->emailaddress;
		}
		
		private function SetEmailAddress($value)
		{
			$this->emailaddress = $value;
		}
		
		# UserGroup
		
		private function GetUserGroup()
		{
			return $this->usergroup;
		}
		
		private function SetUserGroup(UserGroup $value)
		{
			$this->usergroup = $value;
		}
		
		  //
		 // VARIABLES
		//
		
		private static /*(User)*/ $current;
		
		  //
		 // FUNCTIONS
		//
		
		public static function GetCurrentUser()
		{
			return self::$current;
		}
		
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
				$newUser->UserGroup = UserGroup::GetGuestUserGroup();
				
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