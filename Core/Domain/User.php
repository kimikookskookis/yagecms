<?php
	namespace YageCMS\Core\Domain;
	
	use \YageCMS\Core\Tools\ConfigurationManager;
	use \YageCMS\Core\Tools\RequestHeader;
	use \YageCMS\Core\Domain\UserGroup;
	use \YageCMS\Core\Tools\StringTools;
	use \YageCMS\Core\Tools\SaltManager;
	
	class User extends WebsiteDomainObject
	{
		  //
		 // ATTRIBUTES
		//
		
		private /*(string)*/ $loginname;
		private /*(string)*/ $password;
		private /*(string)*/ $passwordsalt;
		private /*(string)*/ $emailaddress;
		private /*(UserGroup)*/ $usergroup;
		
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
		 // FUNCTIONS
		//
		
		public static function SignIn()
		{
			$cookiePrefix = ConfigurationManager::Instance()->GetParameter("CookiePrefix","local");
			
			$idCookie = $cookiePrefix."id";
			$cookies = RequestHeader::Instance()->Cookies;
			
			$identifier = null;
			
			if(array_key_exists($idCookie, $cookies))
			{
				$identifier = $cookies->$idCookie;
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
				
				$newUser->Loginname = "guest-".strtolower(StringTools::GenerateGUID());
				$newUser->Password = $hashedPassword;
				$newUser->PasswordSalt = $localSalt;
				$newUser->EmailAddress = $newUser->Loginname."@guest.com";
				$newUser->UserGroup = UserGroup::GetGuestUserGroup();
				
				$newUser->Create();
				
				// Create Cookies
				#$newUser->ID;
				#$password;
			}
			
			var_dump($identifier);
		}
	}
?>