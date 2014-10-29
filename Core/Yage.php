<?php
	namespace YageCMS\Core;
	
	use \YageCMS\Core\Tools\EventManager;
	use \YageCMS\Core\DomainAccess\WebsiteAccess;
	use \YageCMS\Core\Domain\Website;
	
	class Yage
	{
		public static function Main()
		{
			DatabaseInterface\ConnectionManager::ImportConnectionsFromConfiguration();
			
			$website = WebsiteAccess::Instance()->GetByHostname($_SERVER["HTTP_HOST"]);
			
			if(!is_null($website))
			{
				Website::SetCurrentWebsite($website);
			}
			
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
			
			var_dump(Website::GetCurrentWebsite());
		}
	}
?>