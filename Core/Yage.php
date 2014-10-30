<?php
	namespace YageCMS\Core;
	
	use \YageCMS\Core\Tools\EventManager;
	use \YageCMS\Core\DomainAccess\WebsiteAccess;
	use \YageCMS\Core\Domain\Website;
	use \YageCMS\Core\Domain\User;
	use \YageCMS\Core\Tools\ConfigurationManager;
	
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
			
			// Import Website specific parameters
			ConfigurationManager::Instance()->LoadConfiguration();
			
			$user = User::SignIn();
			
			// Import User specific parameters
			ConfigurationManager::Instance()->LoadConfiguration();
			
			/* This should be in an XML File */
			\YageCMS\Core\Tools\EventManager::Instance()->RegisterEventHandler("YageCMS.Rendering",new \YageCMS\Core\Tools\EventHandler("YageCMS.Core.Tools.URIHandlerManager->ParseURI"));
			/* End XML */
			
			
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
			
			#var_dump(Website::GetCurrentWebsite());
			
			echo "<h1>".DomainAccess\DomainObjectAccess::Instance()->GenerateGUID()."</h1>";
		}
	}
?>