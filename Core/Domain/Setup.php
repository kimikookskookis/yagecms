<?php
	namespace YageCMS\Core\Domain;
	
	use \YageCMS\Core\Tools\LogManager,
	    \YageCMS\Core\Tools\Module\View,
	    \YageCMS\Core\Tools\Module\ModuleView,
	    \YageCMS\Core\Tools\SortableList;
	
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
		
		/**
		 * @var SortableList
		 */
		private $plugins;
		
		  //
		 // CONSTRUCTOR
		//
		
		public function __construct()
		{
			parent::__construct();
			
			$this->plugins = array();
		}
		
		  //
		 // METHODS
		//
		
		/**
		 * @return string
		 */
		public function CreateOutput()
		{
			$output = $this->template->Code;
			
			/*
			 * Check all conditional blocks
			 */
			$ifblocks = array();
			$results = preg_match_all("/<Template:If>(.+)<\/Template:If>/Smisu",$output,$ifblocks,PREG_SET_ORDER);
			
			if($results)
			{
				foreach($ifblocks as $ifblock)
				{
					$wrapper = $ifblock[0];
					$block = $ifblock[1];
					
					// Wrap the XML with a root node
					$block = "<IfConditionBlock xmlns:Template=\"http://www.yagecms.com/some/namespace/Template\">".$block."</IfConditionBlock>";
					
					// Wrap <Condition>...</Condition>, <Then>...</Then>, <ElseIf>...</ElseIf> and <Else>...</Else> with CDATA
					$block = preg_replace("/<(Condition|Then|ElseIf|Else)>(.*?)<\/\\1>/Smisu","<$1><![CDATA[$2]]></$1>",$block);
					
					$xmlIfBlock = simplexml_load_string($block);
					var_dump($xmlIfBlock);
				}
			}
			
			foreach($this->template->Sections as $section)
			{
				$sectionoutput = null;
				
				if(array_key_exists($section, $this->plugins))
				{
					$plugins = $this->plugins[$section];
					
					$sectionoutput = print_r($plugins,1);
				}
				
				
				$output = str_replace("<!--SECTION:".$section."-->",$sectionoutput,$output);
			}
			
			return $output;
		}
		
		public function AddToSection($section, View $view, $position = "last")
		{
			$this->template->Analyze();
			
			if(!array_key_exists($section, $this->plugins))
			{
				$this->plugins[$section] = new SortableList;
			}
			
			$this->plugins[$section]->AddItem($view, $position);
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