<?php
	namespace YageCMS\Core\Tools;
	
	use \YageCMS\Core\Domain\Website;
	use \YageCMS\Core\Domain\User;
	use \YageCMS\Core\DomainAccess\ConfigurationParameterAccess;
	
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
			#$this->parameters["global"] = array();
			
			$path = getcwd()."/Configuration/GlobalConfiguration.xml";
			
			if(!in_array($path, $this->imported) && file_exists($path))
			{
				$this->ImportConfigurationFile($path, "local");
				$this->imported[] = $path;
			}
			
			#LogManager::_("Global Configuration imported");
		}
		
		private function LoadLocalConfiguration()
		{
			#$this->parameters["local"] = array();
			
			$website = Website::GetCurrentWebsite();
			
			if(is_null($website))
				return;
			
			$path = getcwd()."/Configuration/".$website->Hostname."/LocalConfiguration.xml";
			
			if(!in_array($path, $this->imported) && file_exists($path))
			{
				$this->ImportConfigurationFile($path, "local");
				$this->imported[] = $path;
			}
			
			#LogManager::_("Local Configuration imported");
		}
		
		private function LoadUserConfiguration()
		{
			$path = "Database:User";
			
			if(!in_array($path, $this->imported) && !is_null(User::GetCurrentUser()))
			{
				$parameters = ConfigurationParameterAccess::Instance()->GetByScopeValue("USER", User::GetCurrentUser()->ID);
				var_dump($parameters);
			}
		}
		
		private function LoadModuleConfiguration($module)
		{
			$namespace = $module;
			
			$this->parameters[$namespace] = array();
			
			$path = "Configuration/Modules/".$module."/Configuration/Configuration.xml";
			
			$this->ImportConfigurationFile($path, $namespace);
			
			LogManager::_("Configuration for Module '".$module."' imported");
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