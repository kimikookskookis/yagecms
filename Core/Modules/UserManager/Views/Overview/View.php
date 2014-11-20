<?php
	namespace YageCMS\Modules\UserManager\Views\Overview;
	
	use \YageCMS\Core\Tools\Module\ModuleView,
	    \YageCMS\Core\Domain\Template,
	    \YageCMS\Core\Tools\ConfigurationManager,
	    \YageCMS\Core\DomainAccess\SetupAccess;
	
	class View extends ModuleView
	{
		public function DoDefault()
		{
			/*
			 * Steps to take:
			 * 
			 * 1. Determine if there is a setup for this module in the database
			 * 2. If so, load it
			 * 3. Else, load the default setup
			 * 4. View assigns itself to the setup as YageCMS.Core.ModuleView
			 * 5. Parse template
			 */
			/*$template = Template::LoadTemplate("YageCMS.Core.Module.UserManager.Overview.Overview","VIEW");
			print $template->VarDump(true);
			return "Hi";*/
			
			$moduleViewSetup = ConfigurationManager::Instance()->GetParameter("YageCMS.Core.CustomSetup.Overview","module-usermanager");
			
			if(!$moduleViewSetup)
			{
				$moduleViewSetup = ConfigurationManager::Instance()->GetParameter("YageCMS.Core.DefaultSetup");
			}
			
			$setup = SetupAccess::Instance()->GetByID($moduleViewSetup);
			
			$setup->AddToSection("YageCMS.Core.ModuleView", $this);
			
			$output = $this->LoadSetup();
			
			return $output;
		}
	}
?>