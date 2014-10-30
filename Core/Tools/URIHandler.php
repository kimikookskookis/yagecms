<?php
	namespace YageCMS\Core\Tools;
	
	class URIHandler
	{
		  //
		 // ATTRIBUTES
		//
		
		private /*(string)*/ $name;
		private /*(string)*/ $method;
		private /*(string)*/ $pattern;
		private /*(string)*/ $handler;
		private /*(array<URIParameter>)*/ $parameters;
		
		private /*(string)*/ $realpattern;
		
		  //
		 // CONSTRUCTOR
		//
		
		public function __construct($method, $pattern, $handler, $name = null)
		{
			$this->name = $name;
			$this->method = $method;
			$this->pattern = $pattern;
			$this->handler = $handler;
			$this->parameters = array();
		}
		
		  //
		 // METHODDS
		//
		
		public function AddParameter(URIParameter $value)
		{
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
			
			foreach($this->parameters as $parameter)
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
	}
?>