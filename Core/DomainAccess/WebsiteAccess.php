<?php
	namespace YageCMS\Core\DomainAccess;
	
	use YageCMS\Core\Domain\Website;
	use YageCMS\Core\Domain\DomainObject;
	use YageCMS\Core\DatabaseInterface\Access;
	use YageCMS\Core\DatabaseInterface\Record;
	
	class WebsiteAccess
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
			$fromCache = DomainObjectAccess::Instance()->GetFromCache("YageCMS\\Core\\Domain\\Website.ByID",$value);
			
			if(!is_null($fromCache))
			{
				return $fromCache;
			}
			
			$sqlQuery = "SELECT * FROM website WHERE id = :value";
			$result = Access::Instance()->ReadSingle($sqlQuery, array("value" => $value));
			
			if(!$result)
			{
				$logcode = LogManager::_("Website with ID '".$value."' not found");
				throw new WebsiteNotFoundException($logcode);
			}
			
			$object = new Website;
			
			DomainObjectAccess::Instance()->AddToCache("ByID", $value, $object);
			
			$this->ConvertRecordToObject($result, $object);
			
			return $object;
		}
		
		public function GetByHostname($value)
		{
			$fromCache = DomainObjectAccess::Instance()->GetFromCache("Website.ByHostname",$value);
			
			if(!is_null($fromCache))
			{
				return $fromCache;
			}
			
			$sqlQuery = "SELECT * FROM website WHERE hostname = :value";
			$result = Access::Instance()->ReadSingle($sqlQuery, array("value" => $value));
			
			if(!$result)
			{
				throw new WebsiteNotFoundException($id);
			}
			
			$object = new Website;
			
			DomainObjectAccess::Instance()->AddToCache("ByHostname", $value, $object);
			
			$this->ConvertRecordToObject($result, $object);
			
			return $object;
		}
		
		private function ConvertRecordToObject(Record $record, Website $object)
		{
			$hostname = $record->hostname->String;
			
			$object->Hostname = $hostname;
			
			DomainObjectAccess::Instance()->ConvertRecordToObject($record, $object);
			
			return $object;
		}
		
		  //
		 // VARIABLES
		//
		
		private static /*(WebsiteAccess)*/ $instance;
		
		  //
		 // FUNCTIONS
		//
		
		public static function Instance()
		{
			if(is_null(self::$instance))
			{
				self::$instance = new WebsiteAccess;
			}
			
			return self::$instance;
		}
	}
?>