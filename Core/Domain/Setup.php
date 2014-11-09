<?php
	namespace YageCMS\Core\Domain;
	
	use \YageCMS\Core\Tools\LogManager;
	
	class Setup extends WebsiteDomainObject
	{
		  //
		 // ATTRIBUTES
		//
		
		/**
		 * @var string
		 */
		private $module;
		
		/**
		 * @var string
		 */
		private $name;
		
		/**
		 * @var \YageCMS\Core\Domain\Template;
		 */
		private $template;
		
		  //
		 // METHODS
		//
		
		/**
		 * @return string
		 */
		public function CreateOutput()
		{
			#echo $this->template->VarDump(true);
			$this->template->Analyze();
			return "Setup::CreateOutput";
		}
		
		  //
		 // GETTERS/SETTERS
		//
		
		/*
		 * Module
		 */
		
		/**
		 * @return string/null
		 */
		private function GetModule()
		{
			return $this->module;
		}
		
		/**
		 * @param string/null $value
		 */
		private function SetModule($value)
		{
			$this->module = $value;
		}
		
		/*
		 * Name
		 */
		
		/**
		 * @return string
		 */
		private function GetName()
		{
			return $this->name;
		}
		
		/**
		 * @param string $value
		 */
		private function SetName($value)
		{
			$this->name = $value;
		}
		
		/*
		 * Template
		 */
		
		/**
		 * @return \YageCMS\Core\Domain\Template
		 */
		private function GetTemplate()
		{
			return $this->template;
		}
		
		/**
		 * @param \YageCMS\Core\Domain\Template
		 */
		private function SetTemplate(\YageCMS\Core\Domain\Template $value)
		{
			$this->template = $value;
		}
	}
?>