<?php
	namespace YageCMS\Core\DomainAccess;
	
	use YageCMS\Core\Domain\UserGroup,
	    YageCMS\Core\Domain\User,
	    YageCMS\Core\Domain\Website,
	    YageCMS\Core\Domain\DomainObject,
	    YageCMS\Core\DatabaseInterface\Access,
	    YageCMS\Core\DatabaseInterface\Record,
	    YageCMS\Core\Exception\UserNotFoundException,
	    YageCMS\Core\Tools\LogManager;
	
	class UserGroupAccess
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
			$fromCache = DomainObjectAccess::Instance()->GetFromCache("YageCMS\\Core\\Domain\\UserGroup.ByID",$value);
			
			if(!is_null($fromCache))
			{
				return $fromCache;
			}
			
			$sqlQuery = "SELECT * FROM usergroup WHERE id = :value";
			$result = Access::Instance()->ReadSingle($sqlQuery, array("value" => $value));
			
			if(!$result)
			{
				$logcode = LogManager::_("User-Group with ID '".$value."' not found");
				throw new UserGroupNotFoundException($logcode);
			}
			
			$object = new UserGroup;
			DomainObjectAccess::Instance()->AddToCache("ByID", $value, $object);
			
			$this->ConvertRecordToObject($result, $object);
			
			return $object;
		}
		
		public function GetByUser(User $value)
		{
			$sqlQuery = "	SELECT usergroup.*
							FROM usergroup, usergroupitem
							WHERE	usergroupitem.user = :value
									AND usergroupitem.usergroup = usergroup.id
									AND usergroupitem.website = :website
									AND usergroupitem.deleted IS NULL
									AND usergroup.deleted IS NULL
							ORDER BY usergroupitem.priority ASC";
			
			$parameters = array("value" => $value, "website" => Website::GetCurrentWebsite());
				
			$result = Access::Instance()->Read($sqlQuery, $parameters);
				
			if(!$result || !$result->HasRecords)
			{
				$logcode = LogManager::_("No User Groups found for user");
				throw new NoUserGroupsFoundException($logcode);
			}
				
			$objects = array();
				
			while($result->MoveToNextRecord() == true)
			{
				$record = $result->CurrentRecord;
				$id = $record->id->String;
				
				$fromCache = DomainObjectAccess::Instance()->GetFromCache("YageCMS\\Core\\Domain\\UserGroup.ByID",$value);
					
				if(!is_null($fromCache))
				{
					$object = $fromCache;
				}
				else
				{
					$object = new UserGroup;
					DomainObjectAccess::Instance()->AddToCache("ByID", $id, $object);
		
					$this->ConvertRecordToObject($record, $object);
				}
				
				$objects[] = $object;
			}
				
			return $objects;
		}
		
		public function AddUserToUserGroup(UserGroup $usergroup, User $user, $priority = 0)
		{
			if(!$priority)
			{
				$priority = 10;
				
				$sqlPriority = "SELECT MAX(priority) AS priority FROM usergroupitem WHERE usergroup = :usergroup AND user = :user AND deleted IS NULL";
				$parameters = array("usergroup" => $usergroup, "user" => $user);
				
				$result = Access::Instance()->ReadSingle($sqlPriority, $parameters);
				
				if($result)
				{
					$priority = ($result->priority->Integer) + 10;
				}
			}
			
			$sqlQuery = "INSERT INTO usergroupitem (id, website, usergroup, user, priority, created, createdby, modified, modifiedby, deleted, deletedby)
									 VALUES (:id, :website, :usergroup, :user, :priority, :created, :createdby, :modified, :modifiedby,:deleted, :deletedby)";
			
			$created = date("Y-m-d H:i:s");
			$createdby = User::GetCurrentUser();
			
			$parameters = array(
				"id" => DomainObjectAccess::GenerateGUID(),
				"website" => Website::GetCurrentWebsite(),
				"usergroup" => $usergroup,
				"user" => $user,
				"priority" => $priority,
				"created" => $created,
				"createdby" => $createdby,
				"modified" => $created,
				"modifiedby" => $createdby,
				"deleted" => null,
				"deletedby" => null
			);
			
			return Access::Instance()->Execute($sqlQuery, $parameters);
		}
		
		private function ConvertRecordToObject(Record $record, UserGroup $object)
		{
			$name = $record->name->String;
			
			$object->Name = $name;
			
			WebsiteDomainObjectAccess::Instance()->ConvertRecordToObject($record, $object);
			
			return $object;
		}
		
		  //
		 // VARIABLES
		//
		
		private static /*(UserGroupAccess)*/ $instance;
		
		  //
		 // FUNCTIONS
		//
		
		public static function Instance()
		{
			if(is_null(self::$instance))
			{
				self::$instance = new UserGroupAccess;
			}
			
			return self::$instance;
		}
	}
?>