<?php
	namespace YageCMS\Core\Domain;
	
	use \YageCMS\Core\Tools\ConfigurationManager;
	use \YageCMS\Core\Tools\RequestHeader;
	
	class User extends WebsiteDomainObject
	{
		  //
		 // ATTRIBUTES
		//
		
		private /*(string)*/ $loginname;
		private /*(string)*/ $password;
		private /*(string)*/ $passwordsalt;
		private /*(string)*/ $emailaddress;
		
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
				
			}
			
			var_dump($identifier);
		}
	}
?>