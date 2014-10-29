<?php
	namespace YageCMS\Core;
	
	use YageCMS\Core\Tools\EventManager;
	
	class Yage
	{
		public static function Main()
		{
			/*
			 * Use this event to call functions before anything else has been executed
			 */
			EventManager::Instance()->TriggerEvent("YageCMS.PreRendering");
			
			/*
			 * This event shouldn't be altered!
			 * It calls the URL-Handler
			 */
			EventManager::Instance()->TriggerEvent("YageCMS.Rendering");
			
			/*
			 * Use this event to call functions when everything else has been executed
			 */
			EventManager::Instance()->TriggerEvent("YageCMS.PostRendering");
			
			//DatabaseInterface\ConnectionManager::Instance()->ImportConnectionsFromConfiguration();
			/*
			$con = new DatabaseInterface\Drivers\MySQL("default","localhost","webshop","root","");
			$con = DatabaseInterface\ConnectionManager::Instance()->GetConnection("default");
			$con->Connect();
			$con->Disconnect();
			var_dump($con);
			 */
			 var_dump($_SERVER);
			 var_dump(Tools\ConfigurationManager::Instance());
			 var_dump(Tools\LogManager::Instance());
			 echo "<hr/>";
			 
			DatabaseInterface\ConnectionManager::ImportConnectionsFromConfiguration();
			 var_dump(DatabaseInterface\ConnectionManager::Instance());
		}
	}
?>