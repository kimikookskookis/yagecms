<?php
	namespace YageCMS\Core\DomainAccess;
	
	use \YageCMS\Core\Domain\Template,
	    \YageCMS\Core\Domain\DomainObject,
	    \YageCMS\Core\DatabaseInterface\Access,
	    \YageCMS\Core\DatabaseInterface\Record,
	    \YageCMS\Core\Exception\UserNotFoundException,
	    \YageCMS\Core\Tools\LogManager;
	
	class TemplateAccess
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
			$fromCache = DomainObjectAccess::Instance()->GetFromCache("YageCMS\\Core\\Domain\\Template.ByID",$value);
			
			if(!is_null($fromCache))
			{
				return $fromCache;
			}
			
			$sqlQuery = "SELECT * FROM template WHERE id = :value";
			$result = Access::Instance()->ReadSingle($sqlQuery, array("value" => $value));
			
			if(!$result)
			{
				$logcode = LogManager::_("Template with ID '".$value."' not found");
				throw new TemplateNotFoundException($logcode);
			}
			
			$object = new Template;
			DomainObjectAccess::Instance()->AddToCache("ByID", $value, $object);
			
			$this->ConvertRecordToObject($result, $object);
			
			return $object;
		}
		
		private function ConvertRecordToObject(Record $record, Template $object)
		{
			$name = $record->name->String;
			$type = $record->type->String;
			
			$object->Name = $name;
			$object->Type = $type;
			
			WebsiteDomainObjectAccess::Instance()->ConvertRecordToObject($record, $object);
			
			return $object;
		}
		
		  //
		 // VARIABLES
		//
		
		private static /*(TemplateAccess)*/ $instance;
		
		  //
		 // FUNCTIONS
		//
		
		public static function Instance()
		{
			if(is_null(self::$instance))
			{
				self::$instance = new TemplateAccess;
			}
			
			return self::$instance;
		}
	}
?>