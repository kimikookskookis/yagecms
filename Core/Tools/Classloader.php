<?php
	namespace YageCMS\Core\Tools;
	
	use \YageCMS\Core\Exception\ClassNotFoundException;
	use \YageCMS\Core\Exception\ClassFileNotFoundException;
	use \YageCMS\Core\Domain\Website;
	
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
			
			$paths = array();
			
			if(substr($path,0,8) <> "Modules\\")
			{
				$path = substr($path,5);
				$path = "Core/".$path;
				
				$path .= ".php";
				
				$paths[] = $path;
			}
			else
			{
				$path = substr($path,8);
				
				// Core Modules
				$paths[] = "Core/Modules/".$path.".php";
				// Global Modules
				$paths[] = "Configuration/Modules/".$path.".php";
				
				// Local Modules
				$website = Website::GetCurrentWebsite();
			
				if(!is_null($website))
				{
					$paths[] = "Configuration/".$website->Hostname."/Modules/".$path.".php";
				}
			}
			
			$loaded = false;
			
			foreach($paths as $path)
			{
				$path = explode("\\",$path);
				$path = implode("/",$path);
				
				if(file_exists($path))
				{
					require_once $path;
					$loaded = true;
					break;
				}
			}
			
			if(!$loaded)
			{
				$logcode = LogManager::Instance()->_("Class '".$class."' not found: File not found in its expected location (".implode(", ",$paths).")", LogItem::TYPE_ERROR);
				throw new ClassFileNotFoundException($logcode);
			}
			
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