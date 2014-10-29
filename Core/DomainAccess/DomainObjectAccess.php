<?php
	namespace YageCMS\Core\DomainAccess;
	
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
		
		protected function GetNewID($type)
		{
			$sqlLastID = "SELECT lastid FROM tableinfo WHERE tablename = :tablename";
			$lastID = 
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