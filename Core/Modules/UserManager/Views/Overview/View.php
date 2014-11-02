<?php
	namespace YageCMS\Modules\UserManager\Views\Overview;
	
	use \YageCMS\Core\Tools\Module\ModuleView;
	use \YageCMS\Core\Domain\Template;
	
	class View extends ModuleView
	{
		public function DoDefault()
		{
			$template = Template::LoadTemplate("YageCMS.Core.Module.UserManager.Overview.Overview","VIEW");
			print $template->VarDump(true);
			return "Hi";
		}
	}
?>