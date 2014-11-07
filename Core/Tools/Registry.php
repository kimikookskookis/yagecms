<?php
	namespace YageCMS\Core\Tools;
	
	class Registry
	{
		  //
		 // CONSTRUCTOR
		//
		
		private function __construct()
		{
			
		}
		
		  //
		 // METHODS
		//
		
		public function CreateRegistryGroup($name, $module, $label = null, $description = null, Registry $parent = null)
		{
			
		}
		
		public function CreateRegistryItem($name, $module, $label = null, $description = null, Registry $group = null)
		{
			
		}
		
		  //
		 // VARIABLES
		//
		
		private static /*(Registry)*/ $instance;
		
		  //
		 // FUNCTIONS
		//
		
		public static function Instance()
		{
			if(is_null(self::$instance))
			{
				self::$instance = new Registry;
			}
				
			return self::$instance;
		}
	}
?>