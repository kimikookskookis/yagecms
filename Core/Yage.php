<?php
	namespace YageCMS\Core;
	
	use \YageCMS\Core\Tools\EventManager;
	use \YageCMS\Core\Domain\User;
	
	class Yage
	{
		public static function Main()
		{
			/*
			 * Use this event to call functions before anything else has been executed
			 */
			EventManager::Instance()->TriggerEvent("YageCMS.Core.PreRendering");
			
			/*
			 * This event shouldn't be altered!
			 * It calls the URL-Handler
			 */
			EventManager::Instance()->TriggerEvent("YageCMS.Core.Rendering");
			
			/*
			 * Use this event to call functions when everything else has been executed
			 */
			EventManager::Instance()->TriggerEvent("YageCMS.Core.PostRendering");
		}
	}
?>