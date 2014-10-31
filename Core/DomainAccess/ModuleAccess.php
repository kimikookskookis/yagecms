<?php
	namespace YageCMS\Core\DomainAccess;
	
	use YageCMS\Core\Domain\Module;
	use YageCMS\Core\Domain\DomainObject;
	use YageCMS\Core\DatabaseInterface\Access;
	use YageCMS\Core\DatabaseInterface\Record;
	use YageCMS\Core\Exception\UserNotFoundException;
	use YageCMS\Core\Tools\LogManager;
	
	class ModuleAccess
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
			$fromCache = DomainObjectAccess::Instance()->GetFromCache("YageCMS\\Core\\Domain\\Module.ByID",$value);
			
			if(!is_null($fromCache))
			{
				return $fromCache;
			}
			
			$sqlQuery = "SELECT * FROM module WHERE id = :value";
			$result = Access::Instance()->ReadSingle($sqlQuery, array("value" => $value));
			
			if(!$result)
			{
				$logcode = LogManager::_("Module with ID '".$value."' not found");
				throw new ModuleNotFoundException($logcode);
			}
			
			$object = new Module;
			DomainObjectAccess::Instance()->AddToCache("ByID", $value, $object);
			
			$this->ConvertRecordToObject($result, $object);
			
			return $object;
		}
		
		public function GetByName($value)
		{
			$fromCache = DomainObjectAccess::Instance()->GetFromCache("YageCMS\\Core\\Domain\\Module.ByName",$value);
			
			if(!is_null($fromCache))
			{
				return $fromCache;
			}
			
			$sqlQuery = "SELECT * FROM module WHERE name = :value AND website = :website AND deleted IS NULL";
			$result = Access::Instance()->ReadSingle($sqlQuery, array("value" => $value, "Website" => Website::GetCurrentWebsite()));
			
			if(!$result)
			{
				$logcode = LogManager::_("Module with name '".$value."' not found");
				throw new ModuleNotFoundException($logcode);
			}
			
			$object = new Module;
			DomainObjectAccess::Instance()->AddToCache("ByName", $value, $object);
			
			$this->ConvertRecordToObject($result, $object);
			
			return $object;
		}
		
		private function ConvertRecordToObject(Record $record, Module $object)
		{
			$name = $record->name->String;
			$location = $record->location->String;
			$status = $record->status->String;
			
			$object->Name = $name;
			$object->Location = $location;
			$object->Status = $status;
			
			WebsiteDomainObjectAccess::Instance()->ConvertRecordToObject($record, $object);
			
			return $object;
		}
		
		  //
		 // VARIABLES
		//
		
		private static /*(ModuleAccess)*/ $instance;
		
		  //
		 // FUNCTIONS
		//
		
		public static function Instance()
		{
			if(is_null(self::$instance))
			{
				self::$instance = new ModuleAccess;
			}
			
			return self::$instance;
		}
	}
?>