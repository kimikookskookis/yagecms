<?php
	namespace YageCMS\Core\Tools;
	
	class ConfigurationManager
	{
		  //
		 // ATTRIBUTES
		//
		
		private /*(array<string, array<string, string>)*/ $parameters;
		
		  //
		 // CONSTRUCTOR
		//
		
		private function __construct()
		{
			$this->parameters = array();
			
			$this->LoadGlobalConfiguration();
			$this->LoadLocalConfiguration();
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
		
		private function LoadGlobalConfiguration()
		{
			$this->parameters["local"] = array();
			
			$this->ImportConfigurationFile(getcwd()."/Core/Configuration/GlobalConfiguration.xml", "local");
			
			#LogManager::_("Global Configuration imported");
		}
		
		private function LoadLocalConfiguration()
		{
			$path = $this->GetParameter("FileMapping.LocalConfiguration");
			$this->ImportConfigurationFile($path, "local");
			
			#LogManager::_("Local Configuration imported");
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
			}
			
			return self::$instance;
		}
	}
?>