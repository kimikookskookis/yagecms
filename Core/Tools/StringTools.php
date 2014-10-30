<?php
	namespace YageCMS\Core\Tools;
	
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
	}
?>