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
		
		public function Create($type, $values)
		{
			$sqlQuery = "INSERT INTO ".$type." (".implode(", ",array_keys($values)).") VALUES (:".implode(", :",array_keys($values)).")";
			$result = Access::Instance()->Insert($sqlQuery, $values);
			
			return $result;
		}
		
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
			return \YageCMS\Core\Tools\StringTools::GenerateGUID();
		}
		
		public function ConvertRecordToObject(Record $record, DomainObject $object)
		{
			$id = $record->id->Integer;
			$created = $record->created->DateTime;
			$createdby = (!$record->createdby->IsNull ? $record->createdby->String : null);
			$modified = $record->modified->DateTime;
			$modifiedby = (!$record->modifiedby->IsNull ? $record->modifiedby->String : null);
			$deleted = $record->deleted->DateTime;
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
			
			$object->IsPersistent = true;
			
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