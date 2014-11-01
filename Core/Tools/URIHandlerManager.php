<?php
	namespace YageCMS\Core\Tools;
	
	use \YageCMS\Core\Tools\ConfigurationManager;
	use \YageCMS\Core\Domain\URIHandlerParameter;
	use \YageCMS\Core\Domain\URIHandler;
	use \YageCMS\Core\DomainAccess\URIHandlerAccess;
	
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
			
			$this->ImportCoreURIHandlers();
			$this->ImportGlobalURIHandlers();
			$this->ImportLocalURIHandlers();
		}
		
		  //
		 // METHOD
		//
		
		public function RegisterURIHandler(URIHandler $handler)
		{
			$position = $handler->Position;
			
			if(empty($position))
			{
				$position = "last";
			}
			
			if(strtolower($position) == "first")
			{
				array_unshift($this->handlers, $handler);
			}
			else if(strtolower($position) == "last")
			{
				$this->handlers[] = $handler;
			}
			else
			{
				$position = explode(":",$position);
				$mode = $position[0];
				$searchhandler = $position[1];
				
				if($mode <> "before" && $mode <> "after")
				{
					$logcode = LogManager::_("URI Handler Position Mode '".$mode."' is not valid", LogItem::TYPE_ERROR);
					throw new EventPositioningModeInvalidException($logcode);
				}
				
				$position = -1;
				
				foreach($this->handlers as $handlerposition => $reghandler)
				{
					if($reghandler->Name == $searchhandler)
					{
						$position = $handlerposition;
						
						if($mode == "before")
							$position--;
						else
							$position++;
					}
				}
				
				if($position > 0)
				{
					$this->handlers[$position] = $handler;
				}
				else
				{
					$this->handlers[] = $handler;
				}
			}
			
			/*
			 * Now reorder the handlers within this event to add some spacing
			 * between the positions
			 */
			
			$position = 10;
			
			$reordered = array();
			
			foreach($this->handlers as $handler)
			{
				$reordered[$position] = $handler;
				$position += 10;
			}
			
			ksort($reordered);
			
			$this->handlers = $reordered;
		}
		
		private function ImportCoreURIHandlers()
		{
			$path = ConfigurationManager::Instance()->GetParameter("FileMapping.CoreURIHandlers");
			
			$xmlHandlers = simplexml_load_file($path);
				
			foreach($xmlHandlers->children() as $xmlHandler)
			{
				$xmlAttributes = $xmlHandler->attributes();
				
				$name = (isset($xmlAttributes["name"]) ? (string) $xmlAttributes["name"] : null);
				$method = (isset($xmlAttributes["method"]) ? (string) $xmlAttributes["method"] : "GET");
				$position = (isset($xmlAttributes["position"]) ? (string) $xmlAttributes["position"] : "last");
				
				if($position == "first") $position = true;
				else if($position == "last") $position = false;
				
				$pattern = (string) $xmlAttributes["pattern"];
				$handler = (string) $xmlAttributes["handler"];
				
				$handler = new URIHandler($method, $pattern, $handler, $name, $position);
				
				$xmlParameters = $xmlHandler->children();
				
				if(count($xmlParameters))
				{
					foreach($xmlParameters as $xmlParameter)
					{
						$xmlAttributes = $xmlParameter->attributes();
						
						$pname = (string) $xmlAttributes["name"];
						$ppattern = (string) $xmlAttributes["pattern"];
						
						$parameter = new URIHandlerParameter($pname, $ppattern);
						$handler->AddParameter($parameter);
					}
				}
				
				$this->RegisterURIHandler($handler);
			}
		}

		private function ImportGlobalURIHandlers()
		{
			$path = ConfigurationManager::Instance()->GetParameter("FileMapping.GlobalURIHandlers");
			
			$xmlHandlers = simplexml_load_file($path);
				
			foreach($xmlHandlers->children() as $xmlHandler)
			{
				$xmlAttributes = $xmlHandler->attributes();
				
				$name = (isset($xmlAttributes["name"]) ? (string) $xmlAttributes["name"] : null);
				$method = (isset($xmlAttributes["method"]) ? (string) $xmlAttributes["method"] : "GET");
				$position = (isset($xmlAttributes["position"]) ? (string) $xmlAttributes["position"] : "last");
				
				if($position == "first") $position = true;
				else if($position == "last") $position = false;
				
				$pattern = (string) $xmlAttributes["pattern"];
				$handler = (string) $xmlAttributes["handler"];
				
				$handler = new URIHandler($method, $pattern, $handler, $name, $position);
				
				$xmlParameters = $xmlHandler->children();
				
				if(count($xmlParameters))
				{
					foreach($xmlParameters as $xmlParameter)
					{
						$xmlAttributes = $xmlParameter->attributes();
						
						$pname = (string) $xmlAttributes["name"];
						$ppattern = (string) $xmlAttributes["pattern"];
						
						$parameter = new URIHandlerParameter($pname, $ppattern);
						$handler->AddParameter($parameter);
					}
				}
				
				$this->RegisterURIHandler($handler, $position);
			}
		}

		private function ImportLocalURIHandlers()
		{
			$urihandlers = array();
			
			try
			{
				$urihandlers = URIHandlerAccess::Instance()->GetAll();
			}
			catch(NoURIHandlersFoundException $e)
			{
				//ignore
			}
			
			if(count($urihandlers))
			{
				foreach($urihandlers as $urihandler)
				{
					$this->RegisterURIHandler($urihandler);
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