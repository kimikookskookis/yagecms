<?php
	namespace YageCMS\Core\DatabaseInterface;
	
	class ResultSet
	{
		  //
		 // ATTRIBUTES
		//
		
		private /*(array<Record>)*/ $records;
		private /*(int)*/ $numberofrecords;
		private /*(int)*/ $currentposition;
		
		  //
		 // CONSTRUCTOR
		//
		
		public function __construct()
		{
			$this->records = array();
			$this->numberofrecords = 0;
			$this->currentposition = -1;
		}
		
		  //
		 // METHODS
		//
		
		public function Populate(/*(array)*/ $objects)
		{
			$records = $this->records;
			
			foreach($objects as $object)
			{
				$records[] = new Record($object);
				$this->numberofrecords++;
			}
			
			$this->records = array_values($records);
		}
		
		public function MoveToPosition($position)
		{
			$this->currentposition = $position;
			
			if(!array_key_exists($this->currentposition, $this->records))
			{
				return false;
			}
			
			return true;
		}
		
		public function MoveToFirstRecord()
		{
			$position = 0;
			
			return $this->MoveToPosition($position);
		}
		
		public function MoveToNextRecord()
		{
			$position = $this->currentposition + 1;
			
			return $this->MoveToPosition($position);
		}
		
		  //
		 // PROPERTIES
		//
		
		public function __get($field)
		{
			switch($field)
			{
				case "NumberOfRecords": return $this->GetNumberOfRecords();
				case "CurrentRecord": return $this->GetCurrentRecord();
				case "HasRecords": return $this->GetHasRecords();
			}
		}
		
		  //
		 // GETTERS / SETTERS
		//
		
		private function GetNumberOfRecords()
		{
			return $this->numberofrecords;
		}
		
		private function GetCurrentRecord()
		{
			return $this->records[$this->currentposition];
		}
		
		private function GetHasRecords()
		{
			return ($this->numberofrecords>0?true:false);
		}
		
	}
?>