<?php
	namespace YageCMS\Core\DomainAccess;
	
	use \YageCMS\Core\Domain\User;
	use \YageCMS\Core\Domain\DomainObject;
	use \YageCMS\Core\DatabaseInterface\Access;
	use \YageCMS\Core\DatabaseInterface\Record;
	use \YageCMS\Core\Exception\UserNotFoundException;
	use \YageCMS\Core\Tools\LogManager;
	
	class UserAccess
	{
		  //
		 // CONSTRUCTOR
		//
		
		private function __construct() {}
		
		  //
		 // METHODS
		//
		
		public function GetByID($value)
		{
			$fromCache = DomainObjectAccess::Instance()->GetFromCache("YageCMS\\Core\\Domain\\User.ByID",$value);
			
			if(!is_null($fromCache))
			{
				return $fromCache;
			}
			
			$sqlQuery = "SELECT * FROM user WHERE id = :value";
			$result = Access::Instance()->ReadSingle($sqlQuery, array("value" => $value));
			
			if(!$result)
			{
				$logcode = LogManager::_("User with ID '".$value."' not found");
				throw new UserNotFoundException($logcode);
			}
			
			$object = new User;
			DomainObjectAccess::Instance()->AddToCache("ByID", $value, $object);
			
			$this->ConvertRecordToObject($result, $object);
			
			return $object;
		}
		
		private function ConvertRecordToObject(Record $record, User $object)
		{
			$loginname = $record->loginname->String;
			$password = $record->password->String;
			$passwordsalt = $record->passwordsalt->String;
			$lastpasswordchange = $record->lastpasswordchange->Timestamp;
			$emailaddress = $record->emailaddress->String;
			$usergroup = $record->usergroup->String;
			
			$usergroup = UserGroupAccess::Instance()->GetByID($usergroup);
			
			$object->Loginname = $loginname;
			$object->Password = $password;
			$object->PasswordSalt = $passwordsalt;
			$object->LastPasswordChange = $lastpasswordchange;
			$object->EmailAddress = $emailaddress;
			$object->UserGroup = $usergroup;
			
			WebsiteDomainObjectAccess::Instance()->ConvertRecordToObject($record, $object);
			
			return $object;
		}
		
		  //
		 // VARIABLES
		//
		
		private static /*(UserAccess)*/ $instance;
		
		  //
		 // FUNCTIONS
		//
		
		public static function Instance()
		{
			if(is_null(self::$instance))
			{
				self::$instance = new UserAccess;
			}
			
			return self::$instance;
		}
	}
?>