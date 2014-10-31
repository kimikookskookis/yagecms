<?php
	namespace YageCMS\Core\DatabaseInterface;
	
	use \YageCMS\Core\Tools\ConfigurationManager;
	
	class ConnectionManager
	{
		  //
		 // ATTRIBUTES
		//
		
		private /*(array<string, Connection>)*/ $connections;
		
		  //
		 // CONSTRUCTOR
		//
		
		private function __construct()
		{
			$this->connections = array();
		}
		
		  //
		 // METHODS
		//
		
		public function AddConnection(Connection $connection, $name)
		{
			$this->connections[$name] = $connection;
		}
		
		public function GetConnection($name)
		{
			if(!array_key_exists($name, $this->connections))
			{
				return null;
			}
			
			return $this->connections[$name];
		}
		
		private function ImportConnectionsFromConfiguration()
		{
			$connections = ConfigurationManager::Instance()->GetParameters("DataSource.Connections","local");
			
			foreach($connections as $name => $values)
			{
				$driver = $values["Driver"];
				$values["Name"] = $name;
				
				$importer = new \ReflectionMethod($driver, "ImportFromConfiguration");
				$importer->invokeArgs(null, array($values));
				# The array($values) is neccessary to provide the array as it is, and not be split into variables
			}
		}
		
		  //
		 // VARIABLES
		//
		
		private static /*(ConnectionManager)*/ $instance;
		
		  //
		 // FUNCTIONS
		//
		
		public static function Instance()
		{
			if(is_null(self::$instance))
			{
				self::$instance = new ConnectionManager;
				self::$instance->ImportConnectionsFromConfiguration();
			}
			
			return self::$instance;
		}
	}
?>