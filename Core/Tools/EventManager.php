<?php
	namespace YageCMS\Core\Tools;
	
	use YageCMS\Core\Exception\EventPositioningModeInvalidException;
	
	class EventManager
	{
		  //
		 // ATTRIBUTES
		//
		
		private /*(array<string, array<int, EventHandler>>)*/ $events;
		private /*(array<string>)*/ $imported;
		
		  //
		 // CONSTRUCTOR
		//
		
		private function __construct()
		{
			$this->events = array();
			$this->imported = array();
		}
		
		  //
		 // METHODS
		//
		
		public function RegisterEventHandler($event, EventHandler $handler, $position = true)
		{
			if(!array_key_exists($event, $this->events))
			{
				$this->events[$event] = array();
			}
			
			if(is_bool($position))
			{
				if($position === true)
				{
					$this->events[$event][] = $handler;
				}
				else
				{
					array_unshift($this->events[$event], $handler);
				}
			}
			else
			{
				$position = explode(":",$position);
				
				$mode = $position[0];
				$searchhandler = $position[1];
				
				if($mode <> "before" && $mode <> "after")
				{
					$logcode = LogManager::_("Event Handler Position Mode '".$mode."' is not valid", LogItem::TYPE_ERROR);
					throw new EventPositioningModeInvalidException($logcode);
				}
				
				$position = -1;
				
				foreach($this->events[$event] as $handlerposition => $reghandler)
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
					$this->events[$event][$position] = $handler;
				}
				else
				{
					$this->events[$event][] = $handler;
				}
			}
			
			/*
			 * Now reorder the handlers within this event to add some spacing
			 * between the positions
			 */
			
			$position = 10;
			
			$reordered = array();
			
			foreach($this->events[$event] as $handler)
			{
				$reordered[$position] = $handler;
				$position += 10;
			}
			
			ksort($reordered);
			
			$this->events[$event] = $reordered;
			
			return true;
		}

		public function TriggerEvent($event)
		{
			if(!array_key_exists($event, $this->events))
			{
				LogManager::_("Event '".$event."' has no registered handlers", LogItem::TYPE_WARNING);
				return true;
			}
			
			$handlers = $this->events[$event];
			
			foreach($handlers as $handler)
			{
				$handler->TriggerHandler();
			}
			
			return true;
		}
		
		private function ImportEventHandlers()
		{
			$this->ImportCoreEventHandlers();
			#$this->ImportGlobalEventHandlers();
			#$this->ImportLocalEventHandlers();
		}
		
		private function ImportCoreEventHandlers()
		{
			$path = getcwd()."/Core/Configuration/EventHandlers.xml";
			
			if(!in_array($path, $this->imported))
			{
				$this->ImportEventHandlerFile($path);
				$this->imported[] = $path;
			}
		}
		
		private function ImportEventHandlerFile($path)
		{
			$xmlHandlers = simplexml_load_file($path);
			
			foreach($xmlHandlers->children() as $xmlHandler)
			{
				$name = (isset($xmlHandler["name"]) ? (string) $xmlHandler["name"] : null);
				$event = (string) $xmlHandler["event"];
				$handler = (string) $xmlHandler["handler"];
				$position = (isset($xmlHandler["position"]) ? (string) $xmlHandler["position"] : "last");
				
				if($position == "first") $position = true;
				else if($position == "last") $position = false;
				
				$eventHandler = new EventHandler($handler, null, $name);
				$this->RegisterEventHandler($event, $eventHandler, $position);
			}
		}
		
		  //
		 // VARIABLES
		//
		
		private static /*(EventManager)*/ $instance;
		
		  //
		 // FUNCTIONS
		//
		
		public static function Instance()
		{
			if(is_null(self::$instance))
			{
				self::$instance = new EventManager;
				self::$instance->ImportEventHandlers();
			}
			
			return self::$instance;
		}
	}
?>