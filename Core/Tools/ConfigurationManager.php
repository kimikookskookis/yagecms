<?php
	namespace YageCMS\Core\Tools;
	
	use \YageCMS\Core\Domain\Website,
	    \YageCMS\Core\Domain\User,
	    \YageCMS\Core\DomainAccess\ConfigurationParameterAccess,
	    \YageCMS\Core\DomainAccess\ModuleAccess,
	    \YageCMS\Core\Exception\NoConfigurationParametersFoundByScopevalueException;
	
	class ConfigurationManager
	{
		  //
		 // ATTRIBUTES
		//
		
		private /*(array<string, array<string, string>)*/ $parameters;
		private /*(array<string>)*/ $imported;
		
		  //
		 // CONSTRUCTOR
		//
		
		private function __construct()
		{
			$this->parameters = array();
			$this->imported = array();
		}
		
		  //
		 // METHODS
		//
		
		public function GetParameter($name, $namespace = "local")
		{
			if(!array_key_exists($namespace, $this->parameters))
				return null;
			
			if(!array_key_exists($name, $this->parameters[$namespace]))
				return null;
			
			return $this->parameters[$namespace][$name];
		}
		
		public function GetParameters($name, $namespace = "local")
		{
			$parameters = array();
			
			if(!array_key_exists($namespace, $this->parameters))
			{
				LogManager::_("Configuration namespace '".$namespace."' not found", LogItem::TYPE_WARNING);
			}
			
			$length = strlen($name);
			
			foreach($this->parameters[$namespace] as $parameter => $value)
			{
				if(substr($parameter,0,$length) == $name)
				{
					$index = substr($parameter,$length+1);
					
					if(strpos($index,"."))
					{
						$index = explode(".",$index,2);
						$index = $index[0];
						$path = $name.".".$index;
						$parameters[$index] = $this->GetParameters($path, $namespace);
					}
					else
					{
						$parameters[$index] = $value;
					}
				}
			}
			
			return $parameters;
		}
		
		public function LoadConfiguration()
		{
			self::$instance->LoadCoreConfiguration();
			self::$instance->LoadGlobalConfiguration();
			self::$instance->LoadLocalConfiguration();
			self::$instance->LoadUserGroupConfiguration();
			self::$instance->LoadUserConfiguration();
		}
		
		private function LoadCoreConfiguration()
		{
			$path = getcwd()."/Core/Configuration/CoreConfiguration.xml";
			
			if(!in_array($path, $this->imported))
			{
				$this->parameters["local"] = array();
				$this->ImportConfigurationFile($path, "local");
				$this->imported[] = $path;
			}
			
			#LogManager::_("Global Configuration imported");
		}
		
		private function LoadGlobalConfiguration()
		{
			$path = getcwd()."/Configuration/GlobalConfiguration.xml";
			
			if(!in_array($path, $this->imported) && file_exists($path))
			{
				$this->ImportConfigurationFile($path, "local");
				$this->imported[] = $path;
			}
		}
		
		private function LoadLocalConfiguration()
		{
			$path = "Database:Local";
			
			if(!in_array($path, $this->imported) && !is_null(Website::GetCurrentWebsite()))
			{
				$parameters = null;
				
				try
				{
					$parameters = ConfigurationParameterAccess::Instance()->GetByScope("LOCAL");
				}
				catch(NoConfigurationParametersFoundByScopevalueException $e)
				{
					//ignore
				}
				/*catch(\Exception $e)
				{
					var_dump($e);
				}*/
				
				if(count($parameters))
				{
					$namespace = "local";
					
					foreach($parameters as $parameter)
					{
						$path = $parameter->name;
						$value = $parameter->value;
						
						$value = preg_replace_callback("#\{\\$([a-zA-Z0-9_]+):([a-zA-Z0-9\._]+)\}#", array($this,"ReplaceParameterReferences"), $value);
						
						$this->parameters[$namespace][$path] = $value;
					}
				}
			}
		}
		
		private function LoadUserGroupConfiguration()
		{
			$path = "Database:UserGroup";
			
			if(!in_array($path, $this->imported) && !is_null(User::GetCurrentUser()))
			{
				$usergroups = User::GetCurrentUser()->UserGroups;
				
				foreach($usergroups as $usergroup)
				{
					$parameters = null;
					
					try
					{
						$parameters = ConfigurationParameterAccess::Instance()->GetByScopeValue("USERGROUP", $usergroup);
					}
					catch(NoConfigurationParametersFoundByScopevalueException $e)
					{
						//ignore
					}
					
					if(count($parameters))
					{
						$namespace = "user";
							
						foreach($parameters as $parameter)
						{
							$path = $parameter->name;
							$value = $parameter->value;
					
							$value = preg_replace_callback("#\{\\$([a-zA-Z0-9_]+):([a-zA-Z0-9\._]+)\}#", array($this,"ReplaceParameterReferences"), $value);
					
							$this->parameters[$namespace][$path] = $value;
						}
					}
				}
			}
		}
		
		private function LoadUserConfiguration()
		{
			$path = "Database:User";
			
			if(!in_array($path, $this->imported) && !is_null(User::GetCurrentUser()))
			{
				$parameters = null;
				
				try
				{
					$parameters = ConfigurationParameterAccess::Instance()->GetByScopeValue("USER", User::GetCurrentUser());
				}
				catch(NoConfigurationParametersFoundByScopevalueException $e)
				{
					//ignore
				}
				
				if(count($parameters))
				{
					$namespace = "user";
					
					foreach($parameters as $parameter)
					{
						$path = $parameter->name;
						$value = $parameter->value;
						
						$value = preg_replace_callback("#\{\\$([a-zA-Z0-9_]+):([a-zA-Z0-9\._]+)\}#", array($this,"ReplaceParameterReferences"), $value);
						
						$this->parameters[$namespace][$path] = $value;
					}
				}
			}
		}
		
		public function LoadGlobalModuleConfiguration($module)
		{
			$namespace = "Module:".$module;
			
			$module = ModuleAccess::Instance()->GetByName($module);
			$hostname = Website::GetCurrentWebsite()->Hostname;
			
			$this->parameters[$namespace] = array();
			
			$paths = array();
			
			if($module->Location == "CORE")
			{
				$paths[] = "Core/Modules/".$module->Name."/Configuration/Configuration.xml";
			}
			
			$paths[] = "Configuration/Modules/".$module->Name."/Configuration/Configuration.xml";
			$paths[] = "Configuration/".$hostname."/Modules/".$module->Name."/Configuration/Configuration.xml";
			
			foreach($paths as $path)
			{
				if(!file_exists($path))
					continue;
				
				$this->ImportConfigurationFile($path, $namespace);
			}
			
			LogManager::_("Configuration for Module '".$module."' imported");
		}
		
		public function LoadCustomModuleConfiguration($module)
		{
			$path = "Database:Module-".$module;
				
			if(!in_array($path, $this->imported) && !is_null(User::GetCurrentUser()))
			{
				$parameters = null;
		
				try
				{
					$parameters = ConfigurationParameterAccess::Instance()->GetByScopeValue("MODULE", $module);
				}
				catch(NoConfigurationParametersFoundByScopevalueException $e)
				{
					//ignore
				}
		
				if(count($parameters))
				{
					$namespace = "module-".$module;
						
					foreach($parameters as $parameter)
					{
						$path = $parameter->name;
						$value = $parameter->value;
		
						$value = preg_replace_callback("#\{\\$([a-zA-Z0-9_]+):([a-zA-Z0-9\._]+)\}#", array($this,"ReplaceParameterReferences"), $value);
		
						$this->parameters[$namespace][$path] = $value;
					}
				}
			}
		}
		
		private function ImportConfigurationFile($path, $namespace)
		{
			$xmlConfig = simplexml_load_file($path);
			
			foreach($xmlConfig->children() as $node)
			{
				$this->ImportFromNode($node, null, $namespace);
			}
		}
		
		private function ImportFromNode(\SimpleXMLElement $node, $path, $namespace)
		{
			$nodename = $node->getName();
			
			$childnodes = $node->children();
			
			if(!is_null($path))
			{
				$path .= ".".$nodename;
			}
			else
			{
				$path = $nodename;
			}
			
			if(!count($childnodes))
			{
				$value = (string) $node;
				$value = preg_replace_callback("#\{\\$([a-zA-Z0-9_]+):([a-zA-Z0-9\._]+)\}#", array($this,"ReplaceParameterReferences"), $value);
				$this->parameters[$namespace][$path] = $value;
			}
			else
			{
				foreach($childnodes as $childnode)
				{
					$this->ImportFromNode($childnode, $path, $namespace);
				}
			}
		}
		
		public function ReplaceParameterReferences($match)
		{
			$namespace = $match[1];
			$parameter = $match[2];
			
			if(!array_key_exists($namespace, $this->parameters))
			{
				LogManager::_("Configuration namespace '".$namespace."' not found for reference replacement", LogItem::TYPE_WARNING);
			}
			
			if(!array_key_exists($parameter, $this->parameters[$namespace]))
			{
				LogManager::_("Configuration parameter '".$namespace.":".$parameter."' not found for reference replacement", LogItem::TYPE_WARNING);
			}
			
			return $this->parameters[$namespace][$parameter];
		}
		
		  //
		 // VARIABLES
		//
		
		private static /*(ConfigurationManager)*/ $instance;
		
		  //
		 // FUNCTIONS
		//
		
		public static function Instance()
		{
			if(is_null(self::$instance))
			{
				self::$instance = new ConfigurationManager;
				self::$instance->LoadConfiguration();
			}
			
			return self::$instance;
		}
	}
?>