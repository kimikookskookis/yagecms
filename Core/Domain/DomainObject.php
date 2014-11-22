<?php
	namespace YageCMS\Core\Domain;
	
	use \YageCMS\Core\Tools\LogManager,
	    \YageCMS\Core\Exception\SetterNotDeclaredException,
	    \YageCMS\Core\Exception\GetterNotDeclaredException,
	    \YageCMS\Core\DatabaseInterface\ConnectionManager,
	    \YageCMS\Core\Tools\StringTools,
	    \YageCMS\Core\DomainAccess\DomainObjectAccess,
	    \YageCMS\Core\Tools\FunctionCheck,
	    \YageCMS\Core\Exception\ValueCannotBeNullException,
	    \DateTime;
	
	/**
	 * This is the base class for all Domain Classes
	 * 
	 * @author Dominik Jahn &lt;dominik1991jahn@gmail.com&gt;
	 * @version 1.0
	 * @since 1.0
	 */
	abstract class DomainObject
	{
		  //
		 // ATTRIBUTES
		//
		
		/**
		 * The ID of the Domain
		 * @var int
		 */
		private $id;
		
		/**
		 * A timestamp of when the object has been saved to the database
		 * @var DateTime
		 */
		private $created;
		
		/**
		 * A reference to the user which created the object
		 * @var User
		 */
		private $createdby;
		
		/**
		 * A timestamp of when the object has last been modified
		 * @var DateTime
		 */
		private $modified;
		
		/**
		 * A reference to the user which last modified the object
		 * @var User
		 */
		private $modifiedby;
		
		/**
		 * A timestamp of when the object has been deleted
		 * @var DateTime
		 */
		private $deleted;
		
		/**
		 * A reference to the user which deleted the object
		 * @var User/null
		 */
		private $deletedby;
		
		/**
		 * Marks the object as persistent
		 * @var boolean
		 */
		private $stored;
		
		/**
		 * Features all the fields which have changed since the object
		 * has been read from the database
		 * @var array&lt;string&gt;
		 */
		private $changedfields;
		
		  //
		 // CONSTRUCTOR
		//

		/**
		 * @author Dominik Jahn &lt;dominik1991jahn@gmail.com&gt;
		 * @version 1.0
		 * @since 1.0
		 */
		public function __construct()
		{
			$this->created = new DateTime();
			$this->createdby = User::GetCurrentUser();
			$this->modified = new DateTime();
			$this->modifiedby = User::GetCurrentUser();
			$this->deleted = new DateTime("9999-12-31 23:59:59");
			$this->deletedby = null;
			
			$this->stored = false;
			$this->changedfields = array();
		}
		
		  //
		 // METHODS
		//
		
		/**
		 * Creates a new dataset in the database.
		 * If the dataset is already saved, a copy will be created
		 * 
		 * @author Dominik Jahn &lt;dominik1991jahn@gmail.com&gt;
		 * @version 1.0
		 * @since 1.0
		 * 
		 * @return integer
		 */
		public function Create()
		{
			$this->ID = null;
			$this->Created = new DateTime();
			$this->CreatedBy = User::GetCurrentUser();
			$this->Modified = new DateTime();
			$this->ModifiedBy = User::GetCurrentUser();
			
			$type = strtolower($this->GetType());
			$values = $this->GetChangedValues();
			
			$id = DomainObjectAccess::Create($type, $values);
			$this->ID = $id;
			
			return true;
		}
		
		/**
		 * Modifies an existing record.
		 * If the record doesn't exist yet, an error will be created
		 * 
		 * @author Dominik Jahn &lt;dominik1991jahn@gmail.com&gt;
		 * @version 1.0
		 * @since 1.0
		 * 
		 * @return boolean
		 */
		public function Modify()
		{
			$this->modified = new DateTime();
			$this->modifiedby = User::GetCurrentUser();
			
			$connection = DatabaseConnection::GetConnection("default");
			return DatabaseAccess::Modify($this);
		}
		
		/**
		 * Deletes an existing record.
		 * If the record doesn't exist yet, an error will be created
		 * 
		 * @author Dominik Jahn &lt;dominik1991jahn@gmail.com&gt;
		 * @version 1.0
		 * @since 1.0
		 * 
		 * @return boolean
		 */
		public function Delete()
		{
			$this->deleted = new DateTime();
			$this->deletedby = User::GetCurrentUser();
			
			$connection = DatabaseConnection::GetConnection("default");
			return DatabaseAccess::Delete($this);
		}
		
		/**
		 * Returns the type of the object (without namespaces)
		 * 
		 * @author Dominik Jahn &lt;dominik1991jahn@gmail.com&gt;
		 * @version 1.0
		 * @since 1.0
		 * 
		 * @return string The name of the Domain Class
		 */
		private function GetType()
		{
			$type = get_called_class();
			$type = explode("\\",$type);
			$type = $type[(count($type)-1)];
			
			return $type;
		}
		
		/**
		 * Creates output of this object (maybe more accessible than <code>var_dump</code>)
		 *
		 * @author Dominik Jahn &lt;dominik1991jahn@gmail.com&gt;
		 * @version 1.0
		 * @since 1.0
		 *
		 * @var boolean $html Whether to return HTML or text
		 * 
		 * @return string the var dump
		 */
		public function VarDump($html = true)
		{
			$dump = null;
			
			$id = (!is_null($this->ID) ? $this->ID : "New Object");
			
			if($html)
			{
				$dump .= "<h1>Dump of Object &lt;".get_class($this)."#".$id."&gt;</h1>";
				
				$dump .= "<p><strong>Created:</strong> ".$this->Created;
				if(!is_null($this->CreatedBy)) $dump .= " <strong>by</strong> ".$this->CreatedBy->Loginname;
				
				$dump .= "<br/><strong>Last modified:</strong> ".$this->Modified;
				if(!is_null($this->ModifiedBy)) $dump .= " <strong>by</strong> ".$this->ModifiedBy->Loginname;
				
				if($this->Deleted)
				{
					$dump .= "<br/><strong>Deleted:</strong> ".$this->Deleted;
					if(!is_null($this->DeletedBy)) $dump .= " <strong>by</strong> ".$this->DeletedBy->Loginname;
				}
				
				if(count($this->changedfields))
				{
					$dump .= "<br/><strong>Changed fields:</strong> ".implode(", ",$this->changedfields);
				}
				
				$dump .= "<br/><strong>State:</strong> ";
				
				if($this->ID && $this->stored == true) $dump .= "Persistent";
				else if($this->ID && !$this->stored) $dump .= "Modified";
				else $dump .= "New";
				
				$dump .= "</p>";
			}
			else
			{
				
			}
			
			return $dump;
		}
		
		/**
		 * Returns the object as a string (by default its ID)
		 *
		 * @author Dominik Jahn &lt;dominik1991jahn@gmail.com&gt;
		 * @version 1.0
		 * @since 1.0
		 * 
		 * @return int The ID
		 */
		public function __tostring()
		{
			return (string) $this->ID;
		}
		
		  //
		 // GETTERS/SETTERS
		//
		
		# ID
		
		/**
		 * Returns the ID of the current object
		 *
		 * @author Dominik Jahn &lt;dominik1991jahn@gmail.com&gt;
		 * @version 1.0
		 * @since 1.0
		 * 
		 * @return integer/null
		 */
		private function GetID()
		{
			return $this->id;
		}
		
		/**
		 * Sets the ID for the current object
		 *
		 * @author Dominik Jahn &lt;dominik1991jahn@gmail.com&gt;
		 * @version 1.0
		 * @since 1.0
		 * 
		 * @param integer $value
		 */
		private function SetID($value)
		{
			$this->id = $value;
		}
		
		# Created
		
		/**
		 * Returns the timestamp of when the object was created
		 *
		 * @author Dominik Jahn &lt;dominik1991jahn@gmail.com&gt;
		 * @version 1.0
		 * @since 1.0
		 * 
		 * @return DateTime
		 */
		private function GetCreated()
		{
			return $this->created;
		}
		
		/**
		 * Sets the timestamp of when the object was created
		 *
		 * @author Dominik Jahn &lt;dominik1991jahn@gmail.com&gt;
		 * @version 1.0
		 * @since 1.0
		 * 
		 * @param DateTime $value
		 */
		private function SetCreated(\DateTime $value)
		{
			$this->created = $value;
		}
		
		# CreatedBy
		
		/**
		 * @return \YageCMS\Core\Domain\User/null
		 */
		private function GetCreatedBy()
		{
			return $this->createdby;
		}
		
		/**
		 * Sets the user which created this object
		 *
		 * @author Dominik Jahn &lt;dominik1991jahn@gmail.com&gt;
		 * @version 1.0
		 * @since 1.0
		 * 
		 * @param User/null $value
		 */
		private function SetCreatedBy(User $value = null)
		{
			$this->createdby = $value;
		}
		
		# Modified
		
		/**
		 * Returns the timestamp of when the object was modified
		 *
		 * @author Dominik Jahn &lt;dominik1991jahn@gmail.com&gt;
		 * @version 1.0
		 * @since 1.0
		 * 
		 * @return DateTime
		 */
		private function GetModified()
		{
			return $this->modified;
		}
		
		/**
		 * Sets the date of when the object was modified
		 *
		 * @author Dominik Jahn &lt;dominik1991jahn@gmail.com&gt;
		 * @version 1.0
		 * @since 1.0
		 * 
		 * @param DateTime $value
		 */
		private function SetModified(\DateTime $value)
		{
			$this->modified = $value;
		}
		
		# ModifiedBy
		
		/**
		 * @return \YageCMS\Core\Domain\User/null
		 */
		private function GetModifiedBy()
		{
			return $this->modifiedby;
		}
		
		/**
		 * Sets the users which modified this object
		 *
		 * @author Dominik Jahn &lt;dominik1991jahn@gmail.com&gt;
		 * @version 1.0
		 * @since 1.0
		 * 
		 * @param User/null $value
		 */
		private function SetModifiedBy(User $value = null)
		{
			$this->modifiedby = $value;
		}
		
		# Deleted
		
		/**
		 * Returns the timestamp of when the object was deleted
		 *
		 * @author Dominik Jahn &lt;dominik1991jahn@gmail.com&gt;
		 * @version 1.0
		 * @since 1.0
		 * 
		 * @return DateTime
		 */
		private function GetDeleted()
		{
			return $this->deleted;
		}
		
		/**
		 * Sets the date when this object was deleted or null if it's not deleted
		 *
		 * @author Dominik Jahn &lt;dominik1991jahn@gmail.com&gt;
		 * @version 1.0
		 * @since 1.0
		 * 
		 * @param DateTime $value
		 */
		private function SetDeleted(\DateTime $value)
		{
			$this->deleted = $value;
		}
		
		/**
		 * @return boolean
		 */
		private function GetIsDeleted()
		{
			$isDeleted = ($this->deleted->format("Y-m-d H:i:s") == "9999-12-31 23:59:59" ? true : false);
			
			return $isDeleted;
		}
		
		# DeletedBy
		
		/**
		 * @return \YageCMS\Core\Domain\User/null
		 */
		private function GetDeletedBy()
		{
			return $this->deletedby;
		}
		
		/**
		 * Sets the user which deleted this object
		 *
		 * @author Dominik Jahn &lt;dominik1991jahn@gmail.com&gt;
		 * @version 1.0
		 * @since 1.0
		 * 
		 * @param User/null $value
		 */
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
		
		# Changed Values
		
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
		 // PROPERTIES
		//
		
		/**
		 * The getter directs properties ($this->Property) to a getter ($this->GetPropery())
		 *
		 * @author Dominik Jahn &lt;dominik1991jahn@gmail.com&gt;
		 * @version 1.0
		 * @since 1.0
		 * 
		 * @param string $field
		 * @throws GetterNotDeclaredException
		 * @return mixed The value returned by the getter
		 */
		final public function __get($field)
		{
			switch($field)
			{
				case "IsPersistent":
					
					return $this->stored;
					
					break;
					
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
					$refMethod = new \ReflectionMethod($class, $method);
					$refMethod->setAccessible(true);
					
					// And execute it
					$value = $refMethod->invoke($this);
					$refMethod->setAccessible(false);
					
					// Check the return value
					FunctionCheck::CheckMethodReturnValue($class."::".$method, $value);
					
					return $value;
			}
		}
		
		/**
		 * Redirects properties ($this->Property) to a setter ($this->SetProperty())
		 *
		 * @author Dominik Jahn &lt;dominik1991jahn@gmail.com&gt;
		 * @version 1.0
		 * @since 1.0
		 * 
		 * @param string $field
		 * @param mixed $value
		 * @throws SetterNotDeclaredException
		 */
		final public function __set($field, $value)
		{
			switch($field)
			{
				case "IsPersistent":
					
					if($value == true)
					{
						$this->stored = true;
						$this->changedfields = array();
					}
					else
					{
						$this->stored = false;
					}
					
					break;
					
				default:
					$method = "Set".$field;
					$class = get_called_class();
					
					// Check if the current value (if any) equals the new value
					// If it doesn't, the field is to be declared as changed
					$currentValue = null;
					
					try
					{
						$currentValue = $this->__get($field);
					}
					catch(ValueCannotBeNullException $e)
					{
						// ignore
					}
					
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
					
					// Check the parameters
					FunctionCheck::CheckMethodParameters($class."::".$method, array($value));
					
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