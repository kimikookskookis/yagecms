<?php
	namespace YageCMS\Core\DomainAccess;
	
	use \YageCMS\Core\Domain\Cookie;
	use \YageCMS\Core\Domain\DomainObject;
	use \YageCMS\Core\DatabaseInterface\Access;
	use \YageCMS\Core\DatabaseInterface\Record;
	use \YageCMS\Core\Exception\CookieNotFoundException;
	use \YageCMS\Core\Tools\LogManager;
	use \YageCMS\Core\Domain\Website;
	
	class CookieAccess
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
			$fromCache = DomainObjectAccess::Instance()->GetFromCache("YageCMS\\Core\\Domain\\Cookie.ByID",$value);
			
			if(!is_null($fromCache))
			{
				return $fromCache;
			}
			
			$sqlQuery = "SELECT * FROM cookie WHERE id = :value";
			$result = Access::Instance()->ReadSingle($sqlQuery, array("value" => $value));
			
			if(!$result)
			{
				$logcode = LogManager::_("Cookie with ID '".$value."' not found");
				throw new UserNotFoundException($logcode);
			}
			
			$object = new Cookie;
			DomainObjectAccess::Instance()->AddToCache("ByID", $value, $object);
			
			$this->ConvertRecordToObject($result, $object);
			
			return $object;
		}
		
		public function GetByIdentifier($value)
		{
			$sqlQuery = "SELECT * FROM cookie WHERE identifier = :value AND website = :website AND deleted = '9999-12-31 23:59:59'";
			$result = Access::Instance()->Read($sqlQuery, array("value" => $value, "website" => Website::GetCurrentWebsite()));
			
			if(!$result || !$result->HasRecords)
			{
				$logcode = LogManager::_("No Cookies with Identifier '".$value."' found");
				throw new NoCookieFoundByIdentifierException($logcode);
			}
			
			$objects = array();
			
			while($result->MoveToNextRecord() == true)
			{
				$record = $result->CurrentRecord;
				
				$object = new Cookie;
				
				$id = $record->id->String;
				DomainObjectAccess::Instance()->AddToCache("ByID", $id, $object);
				
				$this->ConvertRecordToObject($record, $object);
				
				$objects[] = $object;
			}
			
			return $objects;
		}

		public function GetByIdentifierAndName($identifier, $name)
		{
			$value = $identifier.":".$name;
			
			$fromCache = DomainObjectAccess::Instance()->GetFromCache("YageCMS\\Core\\Domain\\Cookie.ByIdentifierAndName",$value);
			
			if(!is_null($fromCache))
			{
				return $fromCache;
			}
			
			$sqlQuery = "SELECT * FROM cookie WHERE identifier = :identifier AND name = :name AND website = :website AND deleted = '9999-12-31 23:59:59'";
			$result = Access::Instance()->ReadSingle($sqlQuery, array("identifier" => $identifier, "name" => $name, "website" => Website::GetCurrentWebsite()));
			
			if(!$result)
			{
				$logcode = LogManager::_("Cookie with Name '".$identifier.":".$name."' not found");
				throw new CookieNotFoundException($logcode);
			}
			
			$object = new Cookie;
			DomainObjectAccess::Instance()->AddToCache("ByIdentifierAndName", $value, $object);
			
			$this->ConvertRecordToObject($result, $object);
			
			return $object;
		}
		
		private function ConvertRecordToObject(Record $record, Cookie $object)
		{
			$identifier = $record->identifier->String;
			$name = $record->name->String;
			$value = $record->value->String;
			$expiration = (!$record->expiration->IsNull ? $record->expiration->Timestamp : null);
			
			$object->Identifier = $identifier;
			$object->Name = $name;
			$object->Value = $value;
			$object->Expiration = $expiration;
			
			WebsiteDomainObjectAccess::Instance()->ConvertRecordToObject($record, $object);
			
			return $object;
		}
		
		  //
		 // VARIABLES
		//
		
		private static /*(CookieAccess)*/ $instance;
		
		  //
		 // FUNCTIONS
		//
		
		public static function Instance()
		{
			if(is_null(self::$instance))
			{
				self::$instance = new CookieAccess;
			}
			
			return self::$instance;
		}
	}
?>