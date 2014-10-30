<?php
	namespace YageCMS\Core\DomainAccess;
	
	use \YageCMS\Core\Domain\DomainObject;
	use \YageCMS\Core\Domain\WebsiteDomainObject;
	use \YageCMS\Core\DatabaseInterface\Access;
	use \YageCMS\Core\DatabaseInterface\Record;
	use \YageCMS\Core\DomainAccess\UserAccess;
	use \YageCMS\Core\Domain\User;
	
	class WebsiteDomainObjectAccess
	{
		
		public function ConvertRecordToObject(Record $record, WebsiteDomainObject $object)
		{
			$website = $record->website->String;
			
			$website = WebsiteAccess::Instance()->GetByID($website);
			
			$object->Website = $website;
			
			DomainObjectAccess::Instance()->ConvertRecordToObject($record, $object);
			
			return $object;
		}
		
		  //
		 // VARIABLES
		//
		
		private static /*(WebsiteDomainObjectAccess)*/ $instance;
		
		  //
		 // FUNCTIONS
		//
		
		public static function Instance()
		{
			if(is_null(self::$instance))
			{
				self::$instance = new WebsiteDomainObjectAccess;
			}
			
			return self::$instance;
		}
	}
?>