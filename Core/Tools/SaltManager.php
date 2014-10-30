<?php
	namespace YageCMS\Core\Tools;
	
	class SaltManager
	{
		  //
		 // ATTRIBUTES
		//
		
		private /*(<array<Salt>)*/ $salts;
		
		  //
		 // CONSTRUCTOR
		//
		
		private function __construct()
		{
			$this->salts = array();
			
			$this->ImportSalts();
		}

		  //
		 // METHODS
		//
		
		public function GetSalt($time)
		{
			foreach($this->salts as $salt)
			{
				if($salt->ValidFrom <= $time && $time <= $salt->ValidTo)
				{
					return $salt->Salt;
				}
				
				return null;
			}
		}
		
		private function ImportSalts()
		{
			$xmlPath = ConfigurationManager::Instance()->GetParameter("FileMapping.PasswordSalts");
			
			$xmlSalts = simplexml_load_file($xmlPath);
			
			foreach($xmlSalts as $xmlSalt)
			{
				$salt = (string) $xmlSalt;
				
				$attributes = $xmlSalt->attributes();
				$from = (string) $attributes["validfrom"];
				$to = (string) $attributes["validto"];
				
				$from = strtotime($from);
				
				if(!strlen($to)) 
					$to = time();
				
				$salt = new Salt($salt, $from, $to);
				
				$this->salts[] = $salt;
			}
		}
		
		  //
		 // VARIABLES
		//
		
		private static /*(SaltManager)*/ $instance;
		
		  //
		 // FUNCTIONS
		//
		
		public static function Instance()
		{
			if(is_null(self::$instance))
			{
				self::$instance = new SaltManager;
			}
			
			return self::$instance;
		}
		
	}
	
	class Salt
	{
		  //
		 // PROPERTIES
		//
		
		private /*(int)*/ $validfrom;
		private /*(int)*/ $validto;
		private /*(string)*/ $salt;
		
		  //
		 // CONSTRUCTOR
		//
		
		public function __construct($salt, $from, $to)
		{
			$this->salt = $salt;
			$this->validfrom = $from;
			$this->validto = $to;
		}
		
		  //
		 // PROPERTIES
		//
		
		public function __get($field)
		{
			switch($field)
			{
				case "ValidFrom": return $this->GetValidFrom();
				case "ValidTo": return $this->GetValidTo();
				case "Salt": return $this->GetSalt();
			}
		}
		
		  //
		 // GETTERS / SETTERS
		//
		
		private function GetValidFrom()
		{
			return $this->validfrom;
		}
		
		private function GetValidTo()
		{
			return $this->validto;
		}
		
		private function GetSalt()
		{
			return $this->salt;
		}
		
	}
?>