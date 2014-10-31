<?php
	namespace YageCMS\Core\Tools;
	
	class EventHandler
	{
		  //
		 // ATTRIBUTES
		//
		
		private /*(string)*/ $handler;
		private /*(array<object>)*/ $parameters;
		private /*(string)*/ $name;
		
		  //
		 // CONSTRUCTOR
		//
		
		public function __construct($handler, $parameters = array(), $name = null)
		{
			if(is_null($parameters))
			{
				$parameters = array();
			}
			else if(!is_array($parameters))
			{
				$parameters = array($parameters);
			}
			
			$this->handler = $handler;
			$this->parameters = $parameters;
			$this->name = $name;
		}
		
		  //
		 // METHODS
		//
		
		public function TriggerHandler()
		{
			$handler = explode("->",$this->handler);
			$class = $handler[0];
			$function = $handler[1];
			
			$class = str_replace(".","\\",$class);
			
			if(!method_exists($class, $function))
			{
				$logcode = LogManager::_("Event Handler '".$this->handler."' is not implemented", LogItem::TYPE_ERROR);
				throw new YageEventHandlerNotImplementedException($logcode);
			}
			
			$function = new \ReflectionMethod($class, $function);
			$result = $function->invokeArgs(null, $this->parameters);
			
			return $result;
		}
		
		  //
		 // PROPERTIES
		//
		
		public function __get($field)
		{
			switch($field)
			{
				case "Handler": return $this->GetHandler();
				default: throw new \InvalidArgumentException("Readable property '".$field."' not defined in ".get_called_class());
			}
		}
		
		public function __set($field, $value)
		{
			throw new \InvalidArgumentException(get_called_class()." has no writable properties");
		}
		
		  //
		 // GETTERS/SETTERS
		//
		
		private function GetHandler()
		{
			return $this->handler;
		}
	}
?>