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
					
					$output = str_replace($wrapper, self::ParseConditionBlock($block), $output);
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
		
		private function ParseConditionBlock($block)
		{
			// Wrap the XML with a root node
			$block = "<IfConditionBlock xmlns:Template=\"http://www.yagecms.com/some/namespace/Template\">".$block."</IfConditionBlock>";
				
			// Wrap <Condition>...</Condition>, <Then>...</Then>, <ElseIf>...</ElseIf> and <Else>...</Else> with CDATA
			$block = preg_replace("/<(Condition|Then|ElseIf|Else)>((?:(?:(?!<\/?\\1).)*|(?R))*)<\/\\1>/Smisu","<$1><![CDATA[$2]]></$1>",$block);
				
			$xmlIfBlock = simplexml_load_string($block);
				
			$xmlBlocks = $xmlIfBlock->children();
				
			$blocks = array();
				
			for($index = 0; $index < count($xmlBlocks); $index++)
			{
				$xmlBlock = $xmlBlocks[$index];
				$type = $xmlBlock->getName();
				if($type == "Condition") $type = "If";
				
				if($type == "If" || $type == "ElseIf")
				{
					$index++;
					$xmlBody = $xmlBlocks[$index];
					
					$blocks[$type] = array("Condition" => (string)$xmlBlock, "Body" => (string) $xmlBody);
				}
				else if($type == "Else")
				{
					$blocks["Else"] = array("Condition" => true, "Body" => (string) $xmlBlock);
				}
			}
			
			foreach($blocks as $block)
			{
				$condition = $block["Condition"];
				$body = $block["Body"];
				
				if(self::CheckCondition($condition))
				{
					return $body;
				}
			}
		}
		
		private function CheckCondition($condition)
		{
			//echo "<h1>Checking condition: ".htmlentities($condition)."</h1>";
			//echo "\n\n";
			$condition = $this->ParseTags($condition);
			
			//echo "<br/>Final Condition: '".htmlentities($condition)."'";
			echo "<br/>".htmlentities("return (".$condition.");")."<br/>";
			$result = eval("return (".$condition.");");
			return $result;
		}
		
		private function ParseTags($body)
		{
			$tags = array();
			$results = preg_match_all("/<(.*)\/>/Smisu",$body,$tags,PREG_SET_ORDER);
			
			if($results)
			{
				foreach($tags as $tag)
				{
					$output = $this->ParseTag($tag[0]);
					
					$body = str_replace($tag[0], $output, $body);
				}
			}
			
			return $body;
		}
		
		private function ParseTag($tag)
		{
			//echo "Parsing '".htmlentities($tag)."'<br/>\n\n";
			
			$info = array();
			$tag = preg_match("/<([a-zA-Z]+)(:([a-zA-Z]+)?)(.*)\/>/Smisu",$tag,$info);
			
			switch($info[1])
			{
				case "Template":
					
					$function = $info[3];
					
					switch($function)
					{
						case "SectionNotEmpty":
							
							preg_match("/name=(\"|')([a-zA-Z0-9\._]+)(\\1)/ui",$info[4],$subinfo);
							$name = $subinfo[2];
							
							if(!array_key_exists($name, $this->plugins))
								return 0;
							
							if(!$this->plugins[$name]->HasItems())
								return 0;
							
							return true;
							
							break;
						
						case "SectionItemsNumber":
							
							preg_match("/name=(\"|')([a-zA-Z0-9\._]+)(\\1)/ui",$info[4],$subinfo);
							$name = $subinfo[2];
							
							if(!array_key_exists($name, $this->plugins))
								return 0;
								
							return $this->plgins[$name]->NumberOfItems();
							
							break;
					}
					
					break;
				
				case "Setting":
					
					break;
			}
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