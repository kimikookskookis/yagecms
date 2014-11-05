<?php
	namespace YageCMS\Core\Tools;
	
	/**
	 * @author Dominik Jahn
	 * @version 1.0
	 * @since 1.0
	 */
	class ArrayTools
	{
		public static function Subset($array,$start,$items)
		{
			
		}
		
		/**
		 * This method removes a value from an array
		 *
		 * @author Dominik Jahn &lt;dominik1991jahn@gmail.com&gt;
		 * @version 1.0
		 * @since 1.0
		 *
		 * @param array $array The array containing the value
		 * @param object $value The value to be removed
		 * @param boolean/null $removeAll Set to true to remove all items matching $value, not just the first match
		 * @param boolean/null $nocheck Set to true to avoid parsing PHPDoc (not recommended, but used by the PHPDoc-parser itself to avoid a never-ending recursion loop)
		 * @return array $result The clean array
		 */
		public static function RemoveValue(&$array, $value, $removeAll = false, $nocheck = false)
		{
			if($nocheck)
			{
				FunctionCheck::CheckMethodParameters(__METHOD__, func_get_args());
			}
				
			if(!$removeAll)
			{
				$key = array_search($value, $array, true);
		
				if(!$key)
					return false;
		
				$keys = array($key);
			}
			else
			{
				$keys = array_keys($array, $value, true);
		
				if(!count($keys))
					return false;
			}
				
			foreach($keys as $key)
			{
				unset($array[$key]);
			}
				
			return true;
		}
		
	}
