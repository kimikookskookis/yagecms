<?php
	namespace YageCMS\Core\Tools;
	
	class Log
	{
		  //
		 // ATTRIBUTES
		//
		
		private /*(string)*/ $name;
		private /*(array<LogItem>)*/ $items;
		
		  //
		 // CONSTRUCTOR
		//
		
		public function __construct($name)
		{
			$this->name = $name;
			$this->items = array();
		}
		
		  //
		 // METHODS
		//
		
		
		public function LogInfo($message)
		{
			return $this->_($message, LogItem::TYPE_INFO);
		}
		
		public function LogWarning($message)
		{
			return $this->_($message, LogItem::TYPE_WARNING);
		}
		
		public function LogError($message)
		{
			return $this->_($message, LogItem::TYPE_ERROR);
		}
		
		public function LogFatal($message)
		{
			return $this->_($message, LogItem::TYPE_FATAL);
		}
		
		public function _($message, $type = LogItem::TYPE_INFO)
		{
			$item = new LogItem($type, $message);
			$this->items[$item->Code] = $item;
			
			if(ConfigurationManager::Instance()->GetParameter("Log.".$this->name.".AutoWrite","local") == "YES")
			{
				$path = "Configuration/Logs/".$this->name."-".date("Ymd-His").".txt";
				
				$logoutput = $item->GenerateLogOutput();
				
				$fileHandler = fopen($path,"a");
				
				fwrite($fileHandler, $logoutput);
				
				fclose($fileHandler);
			}
			
			return $this->name.":".$item->Code;
		}
		
		public function GetLogItemByCode($code)
		{
			return $this->items[$code];
		}
	}
	
	class LogItem
	{
		  //
		 // ATTRIBUTES
		//
		
		private /*(int)*/ $type;
		private /*(string)*/ $message;
		private /*(float)*/ $timestamp;
		private /*(string)*/ $code;
		
		  //
		 // CONSTRUCTOR
		//
		
		public function __construct($type, $message)
		{
			$this->type = $type;
			$this->message = $message;
			$this->timestamp = microtime(true);
			
			$this->code = strtoupper(substr(md5($this->timestamp.$message.$type),0,6));
			
			#var_dump($this->code.': '.$message);
		}
		
		  //
		 // METHODS
		//
		
		public function GenerateLogOutput()
		{
			$output = "[";
			
			switch($this->type)
			{
				case self::TYPE_INFO: $output .= "Info"; break;
				case self::TYPE_WARNING: $output .= "Warning"; break;
				case self::TYPE_ERROR: $output .= "Error"; break;
				case self::TYPE_FATAL: $output .= "Fatal Error"; break;
			}
			
			$output .= " #".$this->code."]\n\n\tMessage: ".$this->message;
			
			if($this->type >= self::TYPE_ERROR)
			{
				$output .= "\n\tStrack trace:\n".print_r(debug_backtrace(),true);
			}
			
			$output .= "\n\n".str_repeat("=", 88)."\n\n";
			
			return $output;
		}
		
		  //
		 // PROPERTIES
		//
		
		public function __get($field)
		{
			switch($field)
			{
				case "Code": return $this->GetCode();
				case "Message": return $this->GetMessage();
			}
		}
		
		  //
		 // GETTERS/SETTERS
		//
		
		private function GetCode()
		{
			return $this->code;
		}
		
		private function GetMessage()
		{
			return $this->message;
		}
		
		  //
		 // CONSTANTS
		//
		
		const TYPE_INFO = 1;
		const TYPE_WARNING = 2;
		const TYPE_ERROR = 4;
		const TYPE_FATAL = 8;
	}
?>