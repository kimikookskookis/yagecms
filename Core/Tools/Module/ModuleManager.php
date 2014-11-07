<?php
	namespace YageCMS\Core\Tools;
	
	class ModuleManager
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
		
		/**
		 * Installs a module from an Install.xml file
		 * 
		 * @param string $path Path to the XML file
		 */
		public function InstallModuleFromXML($path)
		{
			$xmlModule = simplexml_load_file($path);
			
			$name = $description = $version = $requirements = $settings = null;
			$settinggroups = $permissions = $permissiongroups = $views = null;
			$plugins = $setups = $strings = $templates = $file = null;
			
			foreach($xmlModule->children() as $xmlNode)
			{
				switch(strtolower($xmlNode->getName()))
				{
					case "name": $name = (string) $xmlNode; break;
					case "description": $description = (string) $xmlNode; break;
					case "version": $version = (string) $xmlNode; break;
					
					case "requirements":
						
						$requirements = array();
						
						foreach($xmlNode->children() as $xmlRequirement)
						{
							$module = $xmlRequirement->getName();
							$version = (string) $xmlRequirement;
							
							$requirements[$module] = $version;
						}
						
						break;
					
					case "settings":
						
						self::ImportSettingsFromInstallXML($xmlNode, $settings, $settinggroups);
						
						break;
					
					case "permissions":
						
						self::ImportPermissionsFromInstallXML($xmlNode, $permissions, $permissiongroups);
						
						break;
						
					case "views":
						
						$views = array();
						
						foreach($xmlNode->children() as $xmlView)
						{
							$views[] = (string) $xmlView["name"];
						}
						
						break;
						
					case "plugins":
						
						$views = array();
						
						foreach($xmlNode->children() as $xmlView)
						{
							$views[] = (string) $xmlView["name"];
						}
						
						break;
					
					case "setups":
						
						throw new \Exception("Not implemented yet");
						
						break;
						
					case "languagestrings":
						
						$strings = array();
						
						foreach($xmlNode->children() as $xmlStringSet)
						{
							$stringset = array();
							
							foreach($xmlStringSet->children() as $xmlString)
							{
								$language = $xmlString->getName();
								$translation = (string) $xmlString;
								
								$stringset[$language] = $translation;
							}
							
							$strings[] = $stringset;
						}
						
						break;
					
					case "files":
						
						$files = array();
						
						foreach($xmlNode->children() as $xmlFile)
						{
							$name = (string) $xmlFile["name"];
							$content = base64_decode((string) $xmlFile);
							
							$files[$name] = $content;
						}
						
						break;
				}
			}
		}
		
		/**
		 * Converts a module to an Install.xml file
		 * 
		 * @param string $module Name of the module
		 */
		public function CreateModuleXML($module)
		{
			
		}
		
		  //
		 // VARIABLES
		//
		
		private static /*(ModuleManager)*/ $instance;
		
		  //
		 // FUNCTIONS
		//
		
		public static function Instance()
		{
			if(is_null(self::$instance))
			{
				self::$instance = new ModuleManager;
			}
				
			return self::$instance;
		}
	}
?>