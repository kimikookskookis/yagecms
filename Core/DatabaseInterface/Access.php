<?php
	namespace YageCMS\Core\DatabaseInterface;
	
	class Access
	{
		  //
		 // METHODS
		//
		
		private function PrepareQuery($sqlQuery, $parameters = array(), $connection = "default")
		{
			$connection = ConnectionManager::Instance()->GetConnection($connection);
			$statement = null;
			
			try
			{
				$connection->Connect();
				
				$statement = $connection->PDO->prepare($sqlQuery);
				
				if(count($parameters))
				{
					foreach($parameters as $name => $value)
					{
						$this->BindParameter($name, $value, $statement);
					}
				}
			}
			catch(\Exception $e)
			{
				throw $e;
			}
			
			try
			{
				$statement->execute();
			}
			catch(\Exception $e)
			{
				throw $e;
			}
			
			return $statement;
		}
		
		private function BindParameter($name, $value, $statement)
		{
			$type = gettype($value);
		
			switch($type)
			{
				case "boolean":
					$value = ($value ? "YES": "NO");
					$datatype = \PDO::PARAM_STR;
					break;
			
				case "NULL":
					$datatype = \PDO::PARAM_NULL;
					break;
			
				case "integer":
					$datatype = \PDO::PARAM_INT;
					break;
			
				case "STRING":
					$datatype = \PDO::PARAM_STR;
					break;
			
				case "object":
						
					if($value instanceof DomainObject)
					{
						$value = $value->ID;
						$datatype = \PDO::PARAM_INT;
					}
					else if($value instanceof Unit)
					{
						$value = $value->Value;
						$datatype = \PDO::PARAM_INT;
					}
					else
					{
						$value = (string) $value;
						$datatype = \PDO::PARAM_STR;
					}
						
					break;
			
				default:
					$datatype = \PDO::PARAM_STR;
						
					$value = ((string) $value);
						
					break;
			}
			
			try
			{
				$statement->bindValue(":".$name, $value, $datatype);
			} catch(\Exception $e)
			{
				throw $e;
			}
			
			return true;
		}
		
		public function Read($sqlQuery, $parameters = array(), $connection = "default")
		{
			$statement = null;
			
			try
			{
				$statement = $this->PrepareQuery($sqlQuery, $parameters, $connection);
			}
			catch(\Exception $e)
			{
				throw $e;
			}
			
			$resultSet = new ResultSet();
			
			try
			{
				$dataSets = $statement->fetchAll(\PDO::FETCH_OBJ);
			}
			catch(\Exception $e)
			{
				throw $e;
			}
			
			$resultSet->Populate($dataSets);
			
			return $resultSet;
		}
		
		public function ReadSingle($sqlQuery, $parameters = array(), $connection = "default")
		{
			try
			{
				$resultSet = $this->Read($sqlQuery, $parameters, $connection);
				
				if(!$resultSet->HasRecords)
				{
					return null;
				}
				
				$resultSet->MoveToFirstRecord();
				$record = $resultSet->CurrentRecord;
				
				
				return $record;
			}
			catch(\Exception $e)
			{
				throw $e;
			}
		}
		
		public function Execute($sqlQuery, $parameters = array(), $connection = "default")
		{
			$statement = null;
			
			try
			{
				$statement = $this->PrepareQuery($sqlQuery, $parameters, $connection);
			}
			catch(\Exception $e)
			{
				throw $e;
			}
			
			return ($statement ? true : false);
		}
		
		  //
		 // VARIABLES
		//
		
		private static /*(Access)*/ $instance;
		
		  //
		 // FUNCTIONS
		//
		
		public static function Instance()
		{
			if(is_null(self::$instance))
			{
				self::$instance = new Access;
			}
			
			return self::$instance;
		}
	}
?>