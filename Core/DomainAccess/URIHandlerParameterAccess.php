<?php
	namespace YageCMS\Core\DomainAccess;
	
	use \YageCMS\Core\Domain\URIHandlerParameter;
	use \YageCMS\Core\Domain\URIHandler;
	use \YageCMS\Core\Domain\DomainObject;
	use \YageCMS\Core\DatabaseInterface\Access;
	use \YageCMS\Core\DatabaseInterface\Record;
	use \YageCMS\Core\Tools\LogManager;
	use \YageCMS\Core\Domain\Website;
	use \YageCMS\Core\DomainAccess\URIHandlerAccess;
	
	class URIHandlerParameterAccess
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
			$fromCache = DomainObjectAccess::Instance()->GetFromCache("YageCMS\\Core\\Domain\\URIHandlerParameter.ByID",$value);
			
			if(!is_null($fromCache))
			{
				return $fromCache;
			}
			
			$sqlQuery = "SELECT * FROM urihandlerparameter WHERE id = :value";
			$result = Access::Instance()->ReadSingle($sqlQuery, array("value" => $value));
			
			if(!$result)
			{
				$logcode = LogManager::_("URI-Handler Parameter with ID '".$value."' not found");
				throw new URIHandlerParameterNotFoundException($logcode);
			}
			
			$object = new URIHandlerParameter;
			DomainObjectAccess::Instance()->AddToCache("ByID", $value, $object);
			
			$this->ConvertRecordToObject($result, $object);
			
			return $object;
		}
		
		public function GetByURIHandler(URIHandler $value)
		{
			$sqlQuery = "SELECT * FROM urihandlerparameter WHERE urihandler = :value AND website = :website AND deleted = '9999-12-31 23:59:59'";
			$parameters = array("value" => $value, "website" => Website::GetCurrentWebsite());
			
			$result = Access::Instance()->Read($sqlQuery, $parameters);
			
			if(!$result || !$result->HasRecords)
			{
				$logcode = LogManager::_("No URI-Handler Parameters found");
				throw new NoURIHandlerParametersForURIHandlerFoundException($logcode);
			}
			
			$objects = array();
			
			while($result->MoveToNextRecord() == true)
			{
				$record = $result->CurrentRecord;
				
				$object = new URIHandlerParameter;
				
				$id = $record->id->String;
				DomainObjectAccess::Instance()->AddToCache("ByID", $id, $object);
				
				$this->ConvertRecordToObject($record, $object);
				$objects[] = $object;
			}
			
			return $objects;
		}
		
		private function ConvertRecordToObject(Record $record, URIHandlerParameter $object)
		{
			$urihandler = $record->urihandler->String;
			$name = $record->name->String;
			$pattern = $record->pattern->String;
			
			$urihandler = URIHandlerAccess::Instance()->GetByID($urihandler);
			
			$object->URIHandler = $urihandler;
			$object->Name = $name;
			$object->Pattern = $pattern;
			
			WebsiteDomainObjectAccess::Instance()->ConvertRecordToObject($record, $object);
			
			return $object;
		}
		
		  //
		 // VARIABLES
		//
		
		private static /*(URIHandlerParameterAccess)*/ $instance;
		
		  //
		 // FUNCTIONS
		//
		
		public static function Instance()
		{
			if(is_null(self::$instance))
			{
				self::$instance = new URIHandlerParameterAccess;
			}
			
			return self::$instance;
		}
	}
?>