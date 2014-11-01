<?php
	namespace YageCMS\Core\DomainAccess;
	
	use \YageCMS\Core\Domain\Permission;
	use \YageCMS\Core\Domain\DomainObject;
	use \YageCMS\Core\DatabaseInterface\Access;
	use \YageCMS\Core\DatabaseInterface\Record;
	use \YageCMS\Core\Exception\NoConfigurationParametersFoundByScopevalueException;
	use \YageCMS\Core\Tools\LogManager;
	use \YageCMS\Core\Domain\Website;
	use \YageCMS\Core\DomainAccess\UserGroupAccess;
	use \YageCMS\Core\Domain\UserGroup;
	
	class PermissionAccess
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
			$fromCache = DomainObjectAccess::Instance()->GetFromCache("YageCMS\\Core\\Domain\\Permission.ByID",$value);
			
			if(!is_null($fromCache))
			{
				return $fromCache;
			}
			
			$sqlQuery = "SELECT * FROM permission WHERE id = :value";
			$result = Access::Instance()->ReadSingle($sqlQuery, array("value" => $value));
			
			if(!$result)
			{
				$logcode = LogManager::_("Permission with ID '".$value."' not found");
				throw new UserNotFoundException($logcode);
			}
			
			$object = new Permission;
			DomainObjectAccess::Instance()->AddToCache("ByID", $value, $object);
			
			$this->ConvertRecordToObject($result, $object);
			
			return $object;
		}
		
		public function GetByUserGroup(UserGroup $value)
		{
			$sqlQuery = "SELECT * FROM permission WHERE usergroup = :value AND website = :website AND deleted IS NULL";
			$parameters = array("value" => $value, "website" => Website::GetCurrentWebsite());
			
			$result = Access::Instance()->Read($sqlQuery, $parameters);
			
			if(!$result || !$result->HasRecords)
			{
				$logcode = LogManager::_("No permission for group '".$value."' not found");
				throw new NoConfigurationParametersFoundByScopevalueException($logcode);
			}
			
			$objects = array();
			while($result->MoveToNextRecord() == true)
			{
				$record = $result->CurrentRecord;
				
				$object = new Permission;
				
				$id = $record->id->String;
				DomainObjectAccess::Instance()->AddToCache("ByID", $id, $object);
				
				$this->ConvertRecordToObject($record, $object);
				$objects[] = $object;
			}
			
			return $objects;
		}
		
		private function ConvertRecordToObject(Record $record, Permission $object)
		{
			$usergroup = $record->usergroup->String;
			$name = $record->name->String;
			$value = $record->value->Boolean;
			
			$usergroup = UserGroupAccess::Instance()->GetByID($usergroup);
			
			$object->UserGroup = $usergroup;
			$object->Name = $name;
			$object->Value = $value;
			
			WebsiteDomainObjectAccess::Instance()->ConvertRecordToObject($record, $object);
			
			return $object;
		}
		
		  //
		 // VARIABLES
		//
		
		private static /*(PermissionAccess)*/ $instance;
		
		  //
		 // FUNCTIONS
		//
		
		public static function Instance()
		{
			if(is_null(self::$instance))
			{
				self::$instance = new PermissionAccess;
			}
			
			return self::$instance;
		}
	}
?>