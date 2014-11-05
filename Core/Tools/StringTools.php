<?php
	namespace YageCMS\Core\Tools;
	
	/**
	 * @author Dominik Jahn
	 * @version 1.0
	 * @since 1.0
	 */
	class StringTools
	{
		public static function GenerateGUID()
		{
			//			XXXXXXXX-XXXX-XXXX-XXXX-XXXXXXXXXXXX
			$pattern = "%04X%04X-%04X-%04X-%04X-%04X%04X%04X";
			
			$rand1 = mt_rand(0,65535);
			$rand2 = mt_rand(0,65535);
			$rand3 = mt_rand(0,65535);
			$rand4 = mt_rand(16384,20479);
			$rand5 = mt_rand(32768,49151);
			$rand6 = mt_rand(0,65535);
			$rand7 = mt_rand(0,65535);
			$rand8 = mt_rand(0,65535);
			
			return sprintf($pattern, $rand1, $rand2, $rand3, $rand4, $rand5, $rand6, $rand7, $rand8);
		}
		
		public static function CamelCase($string)
		{
			$camelcase = strtoupper(substr($string,0,1));
			
			$nextCap = false;
			
			for($pos=1;$pos<strlen($string); $pos++)
			{
				$char = substr($string,$pos,1);
				
				if($char == "_" || $char == " " || $char == "-")
				{
					$nextCap = true;
				}
				else {
					$char = ($nextCap ? strtoupper($char) : $char);
					
					$camelcase .= $char;
					
					$nextCap = false;
				}
			}
			
			return $camelcase;
		}
		
		/**
		 * This method doesn't only trim the start and end of the line,
		 * it also trims every single line between
		 *
		 * @author Dominik Jahn &lt;dominik1991jahn@gmail.com&gt;
		 * @version 1.0
		 *
		 * @param string $string The string to be trimmed
		 * @param boolean/null $nocheck Set to true to avoid parsing PHPDoc (not recommended, but used by the PHPDoc-parser itself to avoid a never-ending recursion loop)
		 * @return string $result The trimmed string
		 */
		public static function FullTrim($string, $nocheck = false)
		{
			if(!$nocheck)
			{
				FunctionCheck::CheckMethodParameters(__METHOD__, func_get_args());
			}
				
			$string = explode("\n",trim($string));
				
			$result = null;
				
			foreach($string as $line)
			{
				$result .= trim($line)."\n";
			}
				
			return $result;
		}
	}
?>