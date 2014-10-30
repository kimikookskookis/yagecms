<?php
	namespace YageCMS\Core\DomainAccess;
	
	use YageCMS\Core\Domain\UserGroup;
	use YageCMS\Core\Domain\DomainObject;
	use YageCMS\Core\DatabaseInterface\Access;
	use YageCMS\Core\DatabaseInterface\Record;
	use YageCMS\Core\Exception\UserNotFoundException;
	use YageCMS\Core\Tools\LogManager;
	
	class UserGroupAccess
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
			$fromCache = DomainObjectAccess::Instance()->GetFromCache("YageCMS\\Core\\Domain\\UserGroup.ByID",$value);
			
			if(!is_null($fromCache))
			{
				return $fromCache;
			}
			
			$sqlQuery = "SELECT * FROM usergroup WHERE id = :value";
			$result = Access::Instance()->ReadSingle($sqlQuery, array("value" => $value));
			
			if(!$result)
			{
				$logcode = LogManager::_("User-Group with ID '".$value."' not found");
				throw new UserGroupNotFoundException($logcode);
			}
			
			$object = new UserGroup;
			DomainObjectAccess::Instance()->AddToCache("ByID", $value, $object);
			
			$this->ConvertRecordToObject($result, $object);
			
			return $object;
		}
		
		private function ConvertRecordToObject(Record $record, UserGroup $object)
		{
			$name = $record->name->String;
			
			$object->Name = $name;
			
			WebsiteDomainObjectAccess::Instance()->ConvertRecordToObject($record, $object);
			
			return $object;
		}
		
		  //
		 // VARIABLES
		//
		
		private static /*(UserGroupAccess)*/ $instance;
		
		  //
		 // FUNCTIONS
		//
		
		public static function Instance()
		{
			if(is_null(self::$instance))
			{
				self::$instance = new UserGroupAccess;
			}
			
			return self::$instance;
		}
	}
?>