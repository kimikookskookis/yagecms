<?php
	namespace YageCMS\Core\Domain;
	
	use \YageCMS\Core\Tools\ConfigurationManager;
	use \YageCMS\Core\Domain\URIHandlerParameter;
	use \YageCMS\Core\DomainAccess\URIHandlerParameterAccess;
	use \YageCMS\Core\Tools\RequestHeader;
	
	class URIHandler extends WebsiteDomainObject
	{
		  //
		 // ATTRIBUTES
		//
		
		private /*(string)*/ $name;
		private /*(string)*/ $pattern;
		private /*(string)*/ $method;
		private /*(string)*/ $handler;
		private /*(string)*/ $position;
		
		private /*(array<URIHandlerParameter>)*/ $parameters;
		private /*(string)*/ $realpattern;
		
		  //
		 // CONSTRUCTOR
		//
		
		public function __construct($method = "GET", $pattern = ".*", $handler = "", $name = null, $position = "last")
		{
			$this->name = $name;
			$this->method = $method;
			$this->pattern = $pattern;
			$this->handler = $handler;
		}
		
		  //
		 // METHODS
		//
		
		public function AddParameter(URIHandlerParameter $value)
		{
			// Call this property to make sure that all database parameters are loaded first
			$this->Parameters;
			
			if(is_null($value->URIHandler))
			{
				$value->URIHandler = $this;
			}
			
			if($value->URIHandler->ID <> $this->ID)
			{
				throw new Exception("QQ");
			}
			
			$this->parameters[] = $value;
		}
		
		public function Matches($uri, $method = "GET")
		{
			if($method <> $this->method)
			{
				return false;
			}
			
			if(is_null($this->realpattern))
			{
				$this->CreateRealPattern();
			}
			
			if(!preg_match("{^".$this->realpattern."$}i", $uri))
			{
				return false;
			}
			
			return true;
		}
		
		public function CreateRealPattern()
		{
			$realpattern = $this->pattern;
			
			$realpattern = str_replace(array("?","-"), array("\?","\-"), $realpattern);
			
			// Use $this->'P'arameters, not 'p'arameters, because we need database parameters first
			foreach($this->Parameters as $parameter)
			{
				$realpattern = str_replace("%".$parameter->Name."%", $parameter->Pattern, $realpattern);
			}
			
			$this->realpattern = $realpattern;
		}
		
		public function CallHandler()
		{
			$handler = $this->handler;
			$handler = explode("->",$handler);
			
			$class = $handler[0];
			$method = $handler[1];
			
			$class = str_replace(".", "\\", $class);
			
			$parametermatches = array();
			preg_match("{^".$this->realpattern."$}i", RequestHeader::Instance()->RequestURI, $parametermatches);
			
			$parameters = array();
			
			if(count($parametermatches))
			{
				for($p = 1; $p < count($parametermatches); $p++)
				{
					$value = $parametermatches[$p];
					$name = $this->parameters[($p-1)]->Name;
					
					$parameters[$name] = $value;
				}
			}
			
			$handlerMethod = new \ReflectionMethod($class, $method);
			
			$result = $handlerMethod->invokeArgs(null, $parameters);
			
			return $result;
		}
		
		  //
		 // GETTERS/SETTERS
		//
		
		# Name
		
		private function GetName()
		{
			return $this->name;
		}
		
		private function SetName($value)
		{
			$this->name = $value;
		}
		
		# Pattern
		
		private function GetPattern()
		{
			return $this->pattern;
		}
		
		private function SetPattern($value)
		{
			$this->pattern = $value;
		}
		
		# Method
		
		private function GetMethod()
		{
			return $this->method;
		}
		
		private function SetMethod($value)
		{
			$this->method = $value;
		}
		
		# Handler
		
		private function GetHandler()
		{
			return $this->handler;
		}
		
		private function SetHandler($value)
		{
			$this->handler = $value;
		}
		
		# Position
		
		private function GetPosition()
		{
			return $this->position;
		}
		
		private function SetPosition($value)
		{
			$this->position = $value;
		}
		
		# Attributes
		
		private function GetParameters()
		{
			if($this->IsPersistent && is_null($this->parameters))
			{
				$this->parameters = array();
				
				try
				{
					$this->parameters = URIHandlerParameterAccess::Instance()->GetByURIHandler($this);
				}
				catch(\Exception $e)
				{
					// ignore
				}
			}
			
			return $this->parameters;
		}
		
		private function SetParameters($value)
		{
			throw new Exception("!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!");
		}
		
	}
?>