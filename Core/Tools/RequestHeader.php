<?php
	namespace YageCMS\Core\Tools;
	
	class RequestHeader
	{
		  //
		 // ATTRIBUTES
		//
		
		private /*(<array<string, string>)*/ $fields;
		
		  //
		 // CONSTRUCTOR
		//
		
		private function __construct()
		{
			$this->fields = array();
			
			$requestURL = substr($_SERVER["REDIRECT_URL"],1);
			$docRoot = str_replace("\\","/",$_SERVER["DOCUMENT_ROOT"]);
			$curDir = str_replace("\\", "/", getcwd());
			$curDir = substr(str_replace($docRoot,null,$curDir),1);
			$requestURL = str_replace($curDir, null, $requestURL);
			
			$altHeaders = getallheaders();
			
			$this->fields = array(
				"RequestMethod" => $_SERVER["REQUEST_METHOD"],
				"RequestURI" => $requestURL,
				"UserAgent" => (isset($_SERVER["HTTP_USER_AGENT"]) ? $_SERVER["HTTP_USER_AGENT"] : null),
				"Authentication" => (isset($altHeaders["Authorization"]) ? $altHeaders["Authorization"] : null),
				"Accept" => (isset($_SERVER["HTTP_ACCEPT"]) ? $_SERVER["HTTP_ACCEPT"] : null),
				"AcceptLanguage" => (isset($_SERVER["HTTP_ACCEPT_LANGUAGE"]) ? $_SERVER["HTTP_ACCEPT_LANGUAGE"] : null),
				"AcceptCharset" => (isset($_SERVER["HTTP_ACCEPT_CHARSET"]) ? $_SERVER["HTTP_ACCEPT_CHARSET"] : null),
				"Date" => (isset($_SERVER["HTTP_DATE"]) ? $_SERVER["HTTP_DATE"] : null),
				"Cookies" => $_COOKIE
			);
		}

		  //
		 // METHODS
		//
		
		public function GetPreferedAcceptLanguage($available = array())
		{
			$languages = explode(",",$this->AcceptLanguage);
			
			foreach($languages as $language)
			{
				$language = explode(";",$language);
				$language = trim($language[0]);
				
				if(in_array($language, $available))
				{
					return $language;
				}
			}
			
			return null;
		}
		
		public function GetPreferedAcceptType($available = array())
		{
			$types = explode(",",$this->Accept);
			
			foreach($types as $type)
			{
				$type = explode(";",$type);
				$type = $this->UnifyMimeType(trim($type[0]));
				
				if(in_array($type,$available))
				{
					return $type;
				}
			}
			
			return null;
		}
		
		private function UnifyMimeType($type)
		{
			switch($type)
			{
				case "text/xml": return "application/xml";
				case "application/xhtml+xml": return "text/html";
				case "application/x-javascript": return "application/javascript";
				
				case "application/ogg":
				case "application/pdf":
				case "application/zip":
				case "application/gzip":
					return "application/octet-stream";
				default: return $type;
			}
		}
		
		  //
		 // PROPERTIES
		//
		
		public function __get($field)
		{
			if(!array_key_exists($field, $this->fields))
			{
				throw new HeaderFieldNotDefined($field);
			}
			
			return $this->fields[$field];
		}
		
		  //
		 // VARIABLES
		//
		
		private static /*(RequestHeader)*/ $instance;
		
		  //
		 // FUNCTIONS
		//
		
		public static function Instance()
		{
			if(is_null(self::$instance))
			{
				self::$instance = new RequestHeader;
			}
			
			return self::$instance;
		}
		
		public static function _($message, $type = LogItem::TYPE_INFO)
		{
			return self::$instance->GetDefaultLog()->_($message, $type);
		}
		
	}
?>