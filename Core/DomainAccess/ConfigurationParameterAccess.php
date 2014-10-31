<?php
	namespace YageCMS\Core\DomainAccess;
	
	use \YageCMS\Core\Domain\ConfigurationParameter;
	use \YageCMS\Core\Domain\DomainObject;
	use \YageCMS\Core\DatabaseInterface\Access;
	use \YageCMS\Core\DatabaseInterface\Record;
	use \YageCMS\Core\Exception\UserNotFoundException;
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
			$fromCache = DomainObjectAccess::Instance()->GetFromCache("YageCMS\\Core\\Domain\\ConfigurationParameter.ByScope",$value);
			
			if(!is_null($fromCache))
			{
				return $fromCache;
			}
			
			$sqlQuery = "SELECT * FROM configurationparameter WHERE scope = :value AND website = :website AND deleted IS NULL";
			$result = Access::Instance()->ReadSingle($sqlQuery, array("value" => $value, "website" => Website::GetCurrentWebsite()));
			
			if(!$result)
			{
				$logcode = LogManager::_("Configuration Parameter with Scope '".$value."' not found");
				throw new UserNotFoundException($logcode);
			}
			
			$object = new ConfigurationParameter;
			DomainObjectAccess::Instance()->AddToCache("ByScope", $value, $object);
			
			$this->ConvertRecordToObject($result, $object);
			
			return $object;
		}
		
		public function GetByScopeValue($scope, $scopevalue)
		{
			$sqlQuery = "SELECT * FROM configurationparameter WHERE scope = :scope AND scopevalue = :scopevalue AND website = :website AND deleted IS NULL";
			$result = Access::Instance()->ReadSingle($sqlQuery, array("scope" => $scope, "scopevalue" => $scopevalue, "website" => Website::GetCurrentWebsite()));
			
			if(!$result || !$result->HasRecords)
			{
				$logcode = LogManager::_("Configuration Parameter with Scope Value '".$value."' not found");
				throw new NoConfigurationParametersFoundException($logcode);
			}
			
			$value = $scope.":".$scopevalue;
			
			$fromCache = DomainObjectAccess::Instance()->GetFromCache("YageCMS\\Core\\Domain\\ConfigurationParameter.ByScopeValue",$value);
			
			if(!is_null($fromCache))
			{
				return $fromCache;
			}
			
			$object = new ConfigurationParameter;
			DomainObjectAccess::Instance()->AddToCache("ByScopeValue", $value, $object);
			
			$this->ConvertRecordToObject($result, $object);
			
			return $object;
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