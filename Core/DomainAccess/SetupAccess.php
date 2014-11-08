<?php
	namespace YageCMS\Core\DomainAccess;
	
	use \YageCMS\Core\Domain\Setup,
	    \YageCMS\Core\Domain\Template,
	    \YageCMS\Core\Domain\DomainObject,
	    \YageCMS\Core\DatabaseInterface\Access,
	    \YageCMS\Core\DatabaseInterface\Record,
	    \YageCMS\Core\Exception\UserNotFoundException,
	    \YageCMS\Core\Tools\LogManager;
	
	class SetupAccess
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
			$fromCache = DomainObjectAccess::Instance()->GetFromCache("YageCMS\\Core\\Domain\\Setup.ByID",$value);
			
			if(!is_null($fromCache))
			{
				return $fromCache;
			}
			
			$sqlQuery = "SELECT * FROM setup WHERE id = :value";
			$result = Access::Instance()->ReadSingle($sqlQuery, array("value" => $value));
			
			if(!$result)
			{
				$logcode = LogManager::_("Setup with ID '".$value."' not found");
				throw new SetupNotFoundException($logcode);
			}
			
			$object = new Setup;
			DomainObjectAccess::Instance()->AddToCache("ByID", $value, $object);
			
			$this->ConvertRecordToObject($result, $object);
			
			return $object;
		}
		
		private function ConvertRecordToObject(Record $record, Setup $object)
		{
			$module = (!$record->module->IsNull ? $record->module->String : null);
			$name = $record->name->String;
			$template = $record->template->String;
			
			$template = TemplateAccess::Instance()->GetByID($template);
			
			$object->Module = $module;
			$object->Name = $name;
			$object->Template = $template;
			
			WebsiteDomainObjectAccess::Instance()->ConvertRecordToObject($record, $object);
			
			return $object;
		}
		
		  //
		 // VARIABLES
		//
		
		private static /*(SetupAccess)*/ $instance;
		
		  //
		 // FUNCTIONS
		//
		
		public static function Instance()
		{
			if(is_null(self::$instance))
			{
				self::$instance = new SetupAccess;
			}
			
			return self::$instance;
		}
	}
?>