<?php
	namespace YageCMS\Core\Tools;
	
	use \YageCMS\Core\Exception\ClassNotFoundException;
	use \YageCMS\Core\Exception\ClassFileNotFoundException;
	
	class Classloader
	{
		  //
		 // ATTRIBUTES
		//
		
		private /*(array<string>)*/ $loaded;
		
		  //
		 // CONSTRUCTOR
		//
		
		private function __construct()
		{
			$this->loaded = array("YageCMS\\Yage","YageCMS\\Tools\\Classloader");
		}
		
		  //
		 // METHODS
		//
		
		public function LoadClass($class)
		{
			if(class_exists($class) || interface_exists($class))
			{
				LogManager::Instance()->_("Class or Interface '".$class."' already available");
				return true;
			}
			
			if(substr($class,0,8) <> "YageCMS\\")
			{
				$logcode = LogManager::Instance()->_("Class '".$class."' not found (and it's not a YageCMS-Class either)", LogItem::TYPE_ERROR);
				throw new ClassNotFoundException($logcode);
			}
			
			$path = substr($class,8);
			
			/*
			 * Find out whether it's a Core-Class or from an Extension
			 */
			
			$isCore = false;
			
			if(substr($path,0,5) == "Core\\")
			{
				$path = substr($path,5);
				$isCore = true;
			}
			
			$path = explode("\\",$path);
			$path = implode("/",$path).".php";
			
			if($isCore)
			{
				$path = "Core/".$path;
			}
			else
			{
				$path = "Configuration/Modules/".$path;
			}
			
			if(!file_exists($path))
			{
				$logcode = LogManager::Instance()->_("Class '".$class."' not found: File not found in its expected location (".$path.")", LogItem::TYPE_ERROR);
				throw new ClassFileNotFoundException($logcode);
			}
			
			require_once $path;
			
			$this->loaded[] = $class;
			LogManager::Instance()->_("Class '".$class."' loaded", LogItem::TYPE_INFO);
			
			return true;
		}
		
		  //
		 // VARIABLES
		//
		
		private static /*(Classloader)*/ $instance;
		
		  //
		 // FUNCTIONS
		//
		
		public static function Instance()
		{
			if(is_null(self::$instance))
			{
				self::$instance = new Classloader;
			}
			
			return self::$instance;
		}
	}
?>