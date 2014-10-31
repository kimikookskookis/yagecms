<?php
	namespace YageCMS\Core\Tools;
	
	use \YageCMS\Core\Tools\ConfigurationManager;
	use \YageCMS\Core\Tools\URIParameter;
	use \YageCMS\Core\Tools\URIHandler;
	
	class URIHandlerManager
	{
		  //
		 // ATTRIBUTES
		//
		
		private /*(array<int, URIHandler)*/ $handlers;
		
		  //
		 // CONSTRUCTOR
		//
		
		private function __construct()
		{
			$this->handlers = array();
			
			$this->ImportURIHandlers();
		}
		
		  //
		 // METHOD
		//
		
		private function ImportURIHandlers()
		{
			$paths = ConfigurationManager::Instance()->GetParameters("FileMapping.URIHandlers");
			
			foreach($paths as $path)
			{
				$xmlHandlers = simplexml_load_file($path);
				
				foreach($xmlHandlers->children() as $xmlHandler)
				{
					$xmlAttributes = $xmlHandler->attributes();
					
					$name = (isset($xmlAttributes["name"]) ? (string) $xmlAttributes["name"] : null);
					$method = (isset($xmlAttributes["method"]) ? (string) $xmlAttributes["method"] : "GET");
					
					$pattern = (string) $xmlAttributes["pattern"];
					$handler = (string) $xmlAttributes["handler"];
					
					$handler = new URIHandler($method, $pattern, $handler, $name);
					
					$xmlParameters = $xmlHandler->children();
					
					if(count($xmlParameters))
					{
						foreach($xmlParameters as $xmlParameter)
						{
							$xmlAttributes = $xmlParameter->attributes();
							
							$pname = (string) $xmlAttributes["name"];
							$ppattern = (string) $xmlAttributes["pattern"];
							
							$parameter = new URIParameter($pname, $ppattern);
							$handler->AddParameter($parameter);
						}
					}
					
					$this->handlers[] = $handler;
				}
			}
		}

		public function MatchURI($uri, $method = "GET")
		{
			foreach($this->handlers as $handler)
			{
				if($handler->Matches($uri, $method))
				{
					return $handler;
				}
			}
			
			return null;
		}
		
		  //
		 // VARIABLES
		//
		
		private static /*(URIHandlerManager)*/ $instance;
		
		  //
		 // FUNCTIONS
		//
		
		public static function Instance()
		{
			if(is_null(self::$instance))
			{
				self::$instance = new URIHandlerManager;
			}
			
			return self::$instance;
		}
		
		public static function ParseURI()
		{
			$currentURI = RequestHeader::Instance()->RequestURI;
			$currentMethod = RequestHeader::Instance()->RequestMethod;
			
			$handler = self::Instance()->MatchURI($currentURI, $currentMethod);
			$result = $handler->CallHandler();
			
			print $result;
		}
	}
?>