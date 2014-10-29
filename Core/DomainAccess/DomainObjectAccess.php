<?php
	namespace YageCMS\Core\DomainAccess;
	
	use \YageCMS\Core\Domain\DomainObject;
	use \YageCMS\Core\DatabaseInterface\Access;
	use \YageCMS\Core\DatabaseInterface\Record;
	use \YageCMS\Core\DomainAccess\UserAccess;
	use \YageCMS\Core\Domain\User;
	
	class DomainObjectAccess
	{
		  //
		 // ATTRIBUTES
		//
		
		private /*(array<string, array<string, array<DomainObject>>>)*/ $cache;
		
		  //
		 // CONSTRUCTOR
		//
		
		private function __construct()
		{
			$this->cache = array();
		}
		
		  //
		 // METHODS
		//
		
		public function AddToCache($cache, $key, DomainObject $object)
		{
			if(!($object instanceof DomainObject))
			{
				throw new InvalidDomainObjectException();
			}
			
			$type = get_class($object);
			
			if(!array_key_exists($type, $this->cache))
			{
				$this->cache[$type] = array();
			}
			
			if(!array_key_exists($cache, $this->cache[$type]))
			{
				$this->cache[$type][$cache] = array();
			}
			
			$this->cache[$type][$cache][$key] = $object;
		}
		
		public function GetFromCache($cache, $key)
		{
			$cache = explode(".",$cache);
			$type = $cache[0];
			$cache = $cache[1];
			
			if(!array_key_exists($type, $this->cache))
				return null;
			
			if(!array_key_exists($cache, $this->cache[$type]))
				return null;
			
			if(!array_key_exists($key, $this->cache[$type][$cache]))
				return null;
			
			return $this->cache[$type][$cache][$key];
		}
		
		public function GenerateGUID()
		{
			//			XXXXXXXX-XXXX-XXXX-XXXX-XXXXXXXXXXXX
			$pattern = "%04X%04X-%04X-%04X-%04X-%04X%04X%04X";
			
			$rand1 = mt_rand(0,65535);
			$rand2 = mt_rand(0,65535);
			$rand3 = mt_rand(0,65535);
			$rand4 = mt_rand(16384,20479);
			$rand5 = mt_rand(32768,49151);
			$rand6 = mt_rand(0,65535);
			$rand7 = mt_rand(0,65535);
			$rand8 = mt_rand(0,65535);
			
			return sprintf($pattern, $rand1, $rand2, $rand3, $rand4, $rand5, $rand6, $rand7, $rand8);
		}
		
		public function ConvertRecordToObject(Record $record, DomainObject $object)
		{
			$id = $record->id->String;
			$created = $record->created->Timestamp;
			$createdby = (!$record->createdby->IsNull ? $record->createdby->String : null);
			$modified = $record->modified->Timestamp;
			$modifiedby = (!$record->modifiedby->IsNull ? $record->modifiedby->String : null);
			$deleted = (!$record->deleted->IsNull ? $record->deleted->Timestamp : null);
			$deletedby = (!$record->deletedby->IsNull ? $record->deletedby->String : null);
			
			$object->ID = $id;
			
			$object->Created = $created;
			$object->Modified = $modified;
			$object->Deleted = $deleted;
			
			if(!is_null($createdby))
			{
				$createdby = UserAccess::Instance()->GetByID($createdby);
			}
			
			if(!is_null($modifiedby))
			{
				$modifiedby = UserAccess::Instance()->GetByID($modifiedby);
			}
			
			if(!is_null($deletedby))
			{
				$deletedby = UserAccess::Instance()->GetByID($deletedby);
			}
			
			$object->CreatedBy = $createdby;
			$object->ModifiedBy = $modifiedby;
			$object->DeletedBy = $deletedby;
			
			return $object;
		}
		
		  //
		 // VARIABLES
		//
		
		private static /*(DomainObjectAccess)*/ $instance;
		
		  //
		 // FUNCTIONS
		//
		
		public static function Instance()
		{
			if(is_null(self::$instance))
			{
				self::$instance = new DomainObjectAccess;
			}
			
			return self::$instance;
		}
	}
?>