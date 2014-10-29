<?php
	namespace YageCMS\Core\Domain;
	
	abstract class DomainObject
	{
		  //
		 // ATTRIBUTES
		//
		
		private /*(int)*/ $id;
		private /*(int)*/ $created;
		private /*(int)*/ $modified;
		private /*(int)*/ $deleted;
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
			$this->created = time();
			$this->modified = time();
			
			$connection = DatabaseConnection::GetConnection("default");
			return DatabaseAccess::Create($this);
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
		
		  //
		 // GETTERS/SETTERS
		//
		
		private function GetID()
		{
			return $this->id;
		}
		
		private function GetCreated()
		{
			return $this->created;
		}
		
		private function GetModified()
		{
			return $this->modified;
		}
		
		private function GetDeleted()
		{
			return $this->deleted;
		}
		
		private function GetIsDeleted()
		{
			return ($this->deleted ? true : false);
		}
		
		private function GetIsStored()
		{
			return $this->stored;
		}
		
		private function GetChangedFields()
		{
			return $this->changedfields;
		}
		
		  //
		 // PROPERTIES
		//
		
		final public function __get($field)
		{
			$method = "Get".$field;
			$class = get_called_class();
			
			if(!method_exists($this, $method))
			{
				throw new GetterNotDeclaredException($class."->".$method);
			}
			
			// Find the method
			$method = new ReflectionMethod($class, $method);
			
			// And execute it
			return $method->invoke($this);
		}
		
		final public function __set($field, $value)
		{
			$method = "Set".$field;
			$class = get_called_class();
			
			// Check if the current value (if any) equals the new value
			// If it doesn't, the field is to be declared as changed
			$currentValue = $this->__get($field);
			
			if($currentValue <> $value)
			{
				$this->changedfields[] = $field;
			}
			
			if(!method_exists($this, $method))
			{
				throw new SetterNotDeclaredException($class."->".$method);
			}
			
			// Find the method
			$method = new ReflectionMethod($class, $method);
			
			// And execute it
			$method->invokeArgs($this, array($value));
		}
		
	}
?>