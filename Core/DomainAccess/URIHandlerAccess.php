<?php
	namespace YageCMS\Core\DomainAccess;
	
	use \YageCMS\Core\Domain\URIHandler;
	use \YageCMS\Core\Domain\DomainObject;
	use \YageCMS\Core\DatabaseInterface\Access;
	use \YageCMS\Core\DatabaseInterface\Record;
	use \YageCMS\Core\Exception\NoConfigurationParametersFoundByScopevalueException;
	use \YageCMS\Core\Tools\LogManager;
	use \YageCMS\Core\Domain\Website;
	
	class URIHandlerAccess
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
			$fromCache = DomainObjectAccess::Instance()->GetFromCache("YageCMS\\Core\\Domain\\URIHandler.ByID",$value);
			
			if(!is_null($fromCache))
			{
				return $fromCache;
			}
			
			$sqlQuery = "SELECT * FROM urihandler WHERE id = :value";
			$result = Access::Instance()->ReadSingle($sqlQuery, array("value" => $value));
			
			if(!$result)
			{
				$logcode = LogManager::_("URI-Handler with ID '".$value."' not found");
				throw new URIHandlerNotFoundException($logcode);
			}
			
			$object = new URIHandler;
			DomainObjectAccess::Instance()->AddToCache("ByID", $value, $object);
			
			$this->ConvertRecordToObject($result, $object);
			
			return $object;
		}
		
		public function GetAll()
		{
			$sqlQuery = "SELECT * FROM urihandler WHERE website = :website AND deleted IS NULL";
			$parameters = array("website" => Website::GetCurrentWebsite());
			
			$result = Access::Instance()->Read($sqlQuery, $parameters);
			
			if(!$result || !$result->HasRecords)
			{
				$logcode = LogManager::_("No URI-Handlers found");
				throw new NoURIHandlersFoundException($logcode);
			}
			
			$objects = array();
			
			while($result->MoveToNextRecord() == true)
			{
				$record = $result->CurrentRecord;
				
				$object = new URIHandler;
				
				$id = $record->id->String;
				DomainObjectAccess::Instance()->AddToCache("ByID", $id, $object);
				
				$this->ConvertRecordToObject($record, $object);
				$objects[] = $object;
			}
			
			return $objects;
		}
		
		private function ConvertRecordToObject(Record $record, URIHandler $object)
		{
			$name = $record->name->String;
			$pattern = $record->pattern->String;
			$method = $record->method->String;
			$handler = $record->handler->String;
			$position = $record->position->String;
			
			$object->Name = $name;
			$object->Pattern = $pattern;
			$object->Method = $method;
			$object->Handler = $handler;
			$object->Position = $position;
			
			WebsiteDomainObjectAccess::Instance()->ConvertRecordToObject($record, $object);
			
			return $object;
		}
		
		  //
		 // VARIABLES
		//
		
		private static /*(URIHandlerAccess)*/ $instance;
		
		  //
		 // FUNCTIONS
		//
		
		public static function Instance()
		{
			if(is_null(self::$instance))
			{
				self::$instance = new URIHandlerAccess;
			}
			
			return self::$instance;
		}
	}
?>