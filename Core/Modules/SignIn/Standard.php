<?php
	namespace YageCMS\Modules\SignIn;
	
	use \YageCMS\Core\Tools\Module\ModuleView;
	
	class Standard extends ModuleView
	{
		public function GET_DoDefault()
		{
			return "Sign In";
		}
	}
?>