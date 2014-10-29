<?php
	namespace YageCMS\Core\DatabaseInterface;
	
	abstract class Connection
	{
		  //
		 // ATTRIBUTES
		//
		
		private /*(string)*/ $connectionstring;
		private /*(string)*/ $username;
		private /*(string)*/ $password;
		
		private /*(PDO)*/ $pdo;
		private /*(boolean)*/ $status;
		
		  //
		 // CONSTRUCTOR
		//
		
		/**
		 * @args any
		 */
		public function __construct()
		{
			$this->status = self::STATUS_NEW;
			
			$args = func_get_args();
			
			$name = $args[0];
			unset($args[0]);
			
			$parameters = array_values($args);
			
			$connectionstring = $this->BuildConnectionString($parameters);
			$this->connectionstring = $connectionstring;
			
			ConnectionManager::Instance()->AddConnection($this, $name);
			
			$this->status = self::STATUS_PREPARED;
		}
		
		  //
		 // METHODS
		//
		
		protected abstract function BuildConnectionString($parameters);
		
		public function Connect()
		{
			$pdo = null;
			
			try
			{
				$pdo = new \PDO($this->connectionstring, $this->username, $this->password);
			} catch(\PDOException $e)
			{
				$this->status = self::STATUS_ERROR;
				throw $e;
			}
			
			$this->pdo = $pdo;
			$this->status = self::STATUS_CONNECTED;
		}
		
		public function Disconnect()
		{
			$this->pdo = null;
			$this->status = self::STATUS_DISCONNECTED;
		}
		
		  //
		 // PROPERTIES
		//
		
		public function __get($field)
		{
			
			switch($field)
			{
				#case "ConnectionString": return $this->GetConnectionString();
				case "Status": return $this->GetStatus();
				case "PDO": return $this->GetPDO();
				default: throw new \InvalidArgumentException("Readable property '".get_called_class()."->".$field."' doesn't exist");
			}
		}
		
		public function __set($field, $value)
		{
			switch($field)
			{
				case "Username": $this->SetUsername($value); break;
				case "Password": $this->SetPassword($value); break;
				default: throw new \InvalidArgumentException("Writable property '".get_called_class()."->".$field."' doesn't exist");
			}
		}
		
		  //
		 // GETTERS/SETTERS
		//
		
		private function GetConnectionString()
		{
			return $this->connectionstring;
		}
		
		private function SetUsername($value)
		{
			$this->username = $value;
		}
		
		private function SetPassword($value)
		{
			$this->password = $value;
		}
		
		private function GetStatus()
		{
			return $this->status;
		}
		
		private function GetPDO()
		{
			return $this->pdo;
		}
		
		  //
		 // CONSTANTS
		//
		
		const STATUS_NEW = 1;
		const STATUS_PREPARED = 2;
		const STATUS_CONNECTED = 4;
		const STATUS_DISCONNECTED = 8;
		const STATUS_ERROR = 16;
	}
?>