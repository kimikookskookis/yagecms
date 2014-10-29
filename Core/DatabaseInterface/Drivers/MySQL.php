<?php
	namespace YageCMS\Core\DatabaseInterface\Drivers;
	
	class MySQL extends \YageCMS\Core\DatabaseInterface\Connection
	{
		protected function BuildConnectionString($parameters)
		{
			list($server, $database, $username, $password) = $parameters;
			
			$string = "mysql:host=".$server.";dbname=".$database;
			
			$this->Username = $username;
			$this->Password = $password;
			
			return $string;
		}
		
		/*public function __get($field)
		{
			return parent::__get($field);
		}
		
		public function __set($field, $value)
		{
			parent::__set($field, $value);
		}*/
		
		public static function ImportFromConfiguration($parameters)
		{
			$name = $parameters["Name"];
			$server = $parameters["Server"];
			$database = $parameters["Database"];
			$username = $parameters["Username"];
			$password = $parameters["Password"];
			
			$connection = new MySQL($name, $server, $database, $username, $password);
		}
	}
?>