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
			$template = Template::LoadTemplate("YageCMS.Core.Module.UserManager.Overview.Overview","VIEW");
			print $template->VarDump(true);
			return "Hi";*/
			
			$defaultSetup = ConfigurationManager::Instance()->GetParameter("YageCMS.Core.DefaultSetup");
			$setup = SetupAccess::Instance()->GetByID($defaultSetup);
			
			return $setup->CreateOutput();
		}
	}
?>