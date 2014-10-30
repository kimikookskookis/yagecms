<?php
	namespace YageCMS\Core\Domain;
	
	use \YageCMS\Core\Tools\LogManager;
	use \YageCMS\Core\Exception\SetterNotDeclaredException;
	use \YageCMS\Core\Exception\GetterNotDeclaredException;
	use \YageCMS\Core\DatabaseInterface\ConnectionManager;
	use \YageCMS\Core\Tools\StringTools;
	use \YageCMS\Core\DomainAccess\DomainObjectAccess;
	
	abstract class DomainObject
	{
		  //
		 // ATTRIBUTES
		//
		
		private /*(int)*/ $id;
		private /*(int)*/ $created;
		private /*(User)*/ $createdby;
		private /*(int)*/ $modified;
		private /*(User)*/ $modifiedby;
		private /*(int)*/ $deleted;
		private /*(User)*/ $deletedby;
		private /*(boolean)*/ $stored;
		private /*(array<string>)*/ $changedfields;
		
		  //
		 // CONSTRUCTOR
		//
		
		public function __construct()
		{
			$this->stored = false;
			$this->changedfields = array();
		}
		
		  //
		 // METHODS
		//
		
		public function Create()
		{
			$this->ID = StringTools::GenerateGUID();
			$this->Created = time();
			$this->CreatedBy = User::GetCurrentUser();
			$this->Modified = time();
			$this->ModifiedBy = User::GetCurrentUser();
			
			$type = strtolower($this->GetType());
			$values = $this->GetChangedValues();
			
			return DomainObjectAccess::Create($type, $values);
		}
		
		public function Modify()
		{
			$this->modified = time();
			
			$connection = DatabaseConnection::GetConnection("default");
			return DatabaseAccess::Modify($this);
		}
		
		public function Delete()
		{
			$this->deleted = time();
			
			$connection = DatabaseConnection::GetConnection("default");
			return DatabaseAccess::Delete($this);
		}
		
		private function GetType()
		{
			$type = get_called_class();
			$type = explode("\\",$type);
			$type = $type[(count($type)-1)];
			
			return $type;
		}
		
		private function GetChangedValues()
		{
			$values = array();
			
			foreach($this->changedfields as $field)
			{
				$value = $this->$field;
				$field = strtolower($field);
				
				$values[$field] = $value;
			}
			
			return $values;
		}
		
		  //
		 // GETTERS/SETTERS
		//
		
		# ID
		
		private function GetID()
		{
			return $this->id;
		}
		
		private function SetID($value)
		{
			$this->id = $value;
		}
		
		# Created
		
		private function GetCreated()
		{
			return date("Y-m-d H:i:s",$this->created);
		}
		
		private function SetCreated($value)
		{
			if(!is_int($value))
				$value = strtotime($value);
			
			$this->created = $value;
		}
		
		# CreatedBy
		
		private function GetCreatedBy()
		{
			return $this->createdby;
		}
		
		private function SetCreatedBy(User $value = null)
		{
			$this->createdby = $value;
		}
		
		# Modified
		
		private function GetModified()
		{
			return date("Y-m-d H:i:s",$this->modified);
		}
		
		private function SetModified($value)
		{
			if(!is_int($value))
				$value = strtotime($value);
			
			$this->modified = $value;
		}
		
		# ModifiedBy
		
		private function GetModifiedBy()
		{
			return $this->modifiedby;
		}
		
		private function SetModifiedBy(User $value = null)
		{
			$this->modifiedby = $value;
		}
		
		# Deleted
		
		private function GetDeleted()
		{
			return (!is_null($this->deleted) ? date("Y-m-d H:i:s",$this->deleted) : null);
		}
		
		private function SetDeleted($value)
		{
			if(!is_int($value) && !is_null($value))
				$value = strtotime($value);
			
			$this->deleted = $value;
		}
		
		private function GetIsDeleted()
		{
			return ($this->deleted ? true : false);
		}
		
		# DeletedBy
		
		private function GetDeletedBy()
		{
			return $this->deletedby;
		}
		
		private function SetDeletedBy(User $value = null)
		{
			$this->deletedby = $value;
		}
		
		# Stored
		
		private function GetIsStored()
		{
			return $this->stored;
		}
		
		# Changed Fields
		private function GetChangedFields()
		{
			return $this->changedfields;
		}
		
		  //
		 // PROPERTIES
		//
		
		final public function __get($field)
		{
			switch($field)
			{
				#case "ID": return $this->GetID();
				default:
					$method = "Get".$field;
					$class = get_called_class();
					
					if(!method_exists($this, $method))
					{
						$logcode = LogManager::_("No Getter defined in '".$class."->".$method."'");
						throw new GetterNotDeclaredException($logcode);
					}
					
					// Find the method
					$method = new \ReflectionMethod($class, $method);
					$method->setAccessible(true);
					
					// And execute it
					$value = $method->invoke($this);
					$method->setAccessible(false);
					
					return $value;
			}
		}
		
		final public function __set($field, $value)
		{
			switch($field)
			{
				case "IsPersistent":
					
					$this->stored = true;
					$this->changedfields = array();
					
					break;
					
				default:
					$method = "Set".$field;
					$class = get_called_class();
					
					// Check if the current value (if any) equals the new value
					// If it doesn't, the field is to be declared as changed
					$currentValue = $this->__get($field);
					
					if($currentValue <> $value)
					{
						$this->changedfields[] = $field;
						$this->stored = false;
					}
					
					if(!method_exists($this, $method))
					{
						$logcode = LogManager::_("No Setter defined in '".$class."->".$method."'");
						throw new SetterNotDeclaredException($logcode);
					}
					
					// Find the method
					$method = new \ReflectionMethod($class, $method);
					$method->setAccessible(true);
					
					// And execute it
					$method->invokeArgs($this, array($value));
					$method->setAccessible(false);
					break;
			}
		}
		
	}
?>