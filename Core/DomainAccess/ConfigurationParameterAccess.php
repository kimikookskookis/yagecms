<?php
	namespace YageCMS\Core\DomainAccess;
	
	use \YageCMS\Core\Domain\ConfigurationParameter;
	use \YageCMS\Core\Domain\DomainObject;
	use \YageCMS\Core\DatabaseInterface\Access;
	use \YageCMS\Core\DatabaseInterface\Record;
	use \YageCMS\Core\Exception\NoConfigurationParametersFoundByScopevalueException;
	use \YageCMS\Core\Tools\LogManager;
	use \YageCMS\Core\Domain\Website;
	
	class ConfigurationParameterAccess
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
			$fromCache = DomainObjectAccess::Instance()->GetFromCache("YageCMS\\Core\\Domain\\ConfigurationParameter.ByID",$value);
			
			if(!is_null($fromCache))
			{
				return $fromCache;
			}
			
			$sqlQuery = "SELECT * FROM configurationparameter WHERE id = :value";
			$result = Access::Instance()->ReadSingle($sqlQuery, array("value" => $value));
			
			if(!$result)
			{
				$logcode = LogManager::_("Configuration Parameter with ID '".$value."' not found");
				throw new UserNotFoundException($logcode);
			}
			
			$object = new ConfigurationParameter;
			DomainObjectAccess::Instance()->AddToCache("ByID", $value, $object);
			
			$this->ConvertRecordToObject($result, $object);
			
			return $object;
		}
		
		public function GetByScope($value)
		{
			$sqlQuery = "SELECT * FROM configurationparameter WHERE scope = :value AND website = :website AND deleted IS NULL";
			$parameters = array("value" => $value, "website" => Website::GetCurrentWebsite());
			
			$result = Access::Instance()->Read($sqlQuery, $parameters);
			
			if(!$result || !$result->HasRecords)
			{
				$logcode = LogManager::_("Configuration Parameter with Scope '".$value."' not found");
				throw new NoConfigurationParametersFoundByScopevalueException($logcode);
			}
			
			$objects = array();
			
			while($result->MoveToNextRecord() == true)
			{
				$record = $result->CurrentRecord;
				
				$object = new ConfigurationParameter;
				
				$id = $record->id->String;
				DomainObjectAccess::Instance()->AddToCache("ByID", $id, $object);
				
				$this->ConvertRecordToObject($record, $object);
				$objects[] = $object;
			}
			
			return $objects;
		}
		
		public function GetByScopeValue($scope, $scopevalue)
		{
			$sqlQuery = "SELECT * FROM configurationparameter WHERE scope = :scopevalue AND scopevalue = :scopevalue AND website = :website AND deleted IS NULL";
			$result = Access::Instance()->Read($sqlQuery, array("scope" => $scope, "scopevalue" => $scopevalue, "website" => Website::GetCurrentWebsite()));
			
			if(!$result || !$result->HasRecords)
			{
				$logcode = LogManager::_("Configuration Parameter with Scopevalue '".$scope.":".$scopevalue."' not found");
				throw new NoConfigurationParametersFoundByScopevalueException($logcode);
			}
			
			$objects = array();
			
			while($result->MoveToNextRecord() == true)
			{
				$record = $result->CurrentRecord;
				
				$object = new ConfigurationParameter;
				
				$id = $record->id->String;
				DomainObjectAccess::Instance()->AddToCache("ByID", $id, $object);
				
				$this->ConvertRecordToObject($record, $object);
				
				$objects[] = $object;
			}
			
			return $objects;
		}
		
		private function ConvertRecordToObject(Record $record, ConfigurationParameter $object)
		{
			$scope = $record->scope->String;
			$scopevalue = (!$record->scopevalue->IsNull ? $record->scopevalue->String : null);
			$name = $record->name->String;
			$value = $record->value->String;
			
			$object->Scope = $scope;
			$object->ScopeValue = $scopevalue;
			$object->Name = $name;
			$object->Value = $value;
			
			WebsiteDomainObjectAccess::Instance()->ConvertRecordToObject($record, $object);
			
			return $object;
		}
		
		  //
		 // VARIABLES
		//
		
		private static /*(ConfigurationParameterAccess)*/ $instance;
		
		  //
		 // FUNCTIONS
		//
		
		public static function Instance()
		{
			if(is_null(self::$instance))
			{
				self::$instance = new ConfigurationParameterAccess;
			}
			
			return self::$instance;
		}
	}
?>