<?php
	namespace YageCMS\Core\Tools;
	
	class LogManager
	{
		  //
		 // ATTRIBUTES
		//
		
		private /*(array<string, Log>)*/ $logs;
		
		  //
		 // CONSTRUCTOR
		//
		
		private function __construct()
		{
			$this->logs = array();
			
			$this->RegisterLog(new Log("default"), "default");
		}
		
		  //
		 // METHODS
		//
		
		public function RegisterLog(Log $log, $name)
		{
			$this->logs[$name] = $log;
		}
		
		public function GetLog($name)
		{
			if(!array_key_exists($name, $this->logs))
			{
				self::_("Log '".$name."' not registered", LogItem::TYPE_WARNING);
				return null;
			}
			
			return $this->logs[$name];
		}
		
		public function GetDefaultLog()
		{
			return $this->GetLog("default");
		}
		
		  //
		 // VARIABLES
		//
		
		private static /*(LogManager)*/ $instance;
		
		  //
		 // FUNCTIONS
		//
		
		public static function Instance()
		{
			if(is_null(self::$instance))
			{
				self::$instance = new LogManager;
			}
			
			return self::$instance;
		}
		
		public static function _($message, $type = LogItem::TYPE_INFO)
		{
			return self::$instance->GetDefaultLog()->_($message, $type);
		}
	}
?>