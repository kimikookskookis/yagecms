<?php
	namespace YageCMS\Core\DatabaseInterface;
	
	use \YageCMS\Core\Tools\LogManager;
	use \YageCMS\Core\Exception\DataSetFieldNotDefinedException;
	
	class Record
	{
		  //
		 // ATTRIBUTES
		//
		
		private /*(object)*/ $data;
		
		  //
		 // CONSTRUCTOR
		//
		
		public function __construct($data)
		{
			$this->data = $data;
		}
		
		  //
		 // PROPERTIES
		//
		
		public function __get($field)
		{
			return $this->GetValue($field);
		}
		
		  //
		 // GETTERS / SETTERS
		//
		
		private function GetValue($field)
		{
			if(!property_exists($this->data, $field))
			{
				$logcode = LogManager::_("Record field '".$field."' not defined");
				throw new DataSetFieldNotDefinedException($logcode);
			}
			
			$value = $this->data->$field;
			
			return new Field($this->data->$field);
		}
	}
?>