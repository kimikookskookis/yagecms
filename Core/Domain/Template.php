<?php
	namespace YageCMS\Core\Domain;
	
	use \YageCMS\Core\Domain\Website;
	use \YageCMS\Core\DomainAccess\TemplateAccess;
	use \YageCMS\Core\Exception\NoTemplateFoundInExpectedLocationException;
	use \YageCMS\Core\Tools\LogManager;
	
	class Template extends WebsiteDomainObject
	{
		  //
		 // ATTRIBUTES
		//
		
		private /*(string)*/ $name;
		private /*(string)*/ $title;
		private /*(string)*/ $type;
		
		private /*(string)*/ $code;
		private /*(string)*/ $location;
		
		  //
		 // METHODS
		//
		
		public function VarDump($html = true)
		{
			$dump = parent::VarDump($html);
			
			if($html)
			{
				$code = null;
				
				try
				{
					$code = htmlentities($this->Code);
				}
				catch(NoTemplateFoundInExpectedLocationException $e)
				{
					$code .= "<span style='color:#d00'>Template not found in its expected location</span>";
				}
				
				$dump .= "<p><strong>Name:</strong> ".$this->Name;
				$dump .= "<br/><strong>Title:</strong> ".$this->Title;
				$dump .= "<br/><strong>Type:</strong> ".$this->Type;
				$dump .= "<br/><strong>Location:</strong> ".$this->Location;
				$dump .= "<br/><strong>Code:</strong> ".$code."</p>";
			}
			else
			{
				
			}
			
			return $dump;
		}
		
		  //
		 // GETTERS/SETTERS
		//
		
		# Name
		
		private function GetName()
		{
			return $this->name;
		}
		
		private function SetName($value)
		{
			$this->name = $value;
		}
		
		# Title
		
		private function GetTitle()
		{
			return $this->title;
		}
		
		private function SetTitle($value)
		{
			$this->title = $value;
		}
		
		# Type
		
		private function GetType()
		{
			return $this->type;
		}
		
		private function SetType($value)
		{
			$this->type = $value;
		}
		
		# Code
		
		private function GetCode()
		{
			if(is_null($this->code))
			{
				$path = $this->Location;
				
				if(!file_exists($path))
				{
					$logcode = LogManager::_("No Template found for Template '".$this->Name."' in '".$path."'");
					throw new NoTemplateFoundInExpectedLocationException($logcode);
				}
				
				$code = file_get_contents($path);
				$this->code = $code;
			}
			
			return $this->code;
		}
		
		private function SetCode($value)
		{
			$this->code = $value;
		}
		
		# Location
		
		private function GetLocation()
		{
			if(is_null($this->location))
			{
				$this->location = self::DetermineLocation($this);
			}
			
			return $this->location;
		}
		
		private function SetLocation($value)
		{
			$this->location = $value;
		}
		
		  //
		 // CONSTANTS
		//
		
		const TYPE_DESIGN = "DESIGN";
		const TYPE_VIEW = "VIEW";
		const TYPE_SUBTEMPLATE = "SUBTEMPLATE";
		
		  //
		 // FUNCTIONS
		//
		
		public static function LoadTemplate($name, $type = null)
		{
			$template = null;
			
			try
			{
				$template = TemplateAccess::GetByName($name);
			}
			catch(\Exception $e)
			{
				$template = new Template();
				$template->Name = $name;
				$template->Type = $type;
			}
			
			return $template;
		}
		
		private static function DetermineLocation(Template $template)
		{
			$name = $template->Name;
			$hostname = Website::GetCurrentWebsite()->Hostname;
			
			$name = explode(".", $name);
			$path = null;
			
			// YageCMS.Core.Module.%Module%.%View%.%Name%
			// -> Core/Modules/%Module%/Templates/%View%/[Subtemplates/]%Name%.html
			// -> Configuration/%hostname%/Templates/Modules/%Module%/%View%/[Subtemplates/]%Name%.html
			if($name[0] == "YageCMS" && $name[1] == "Core" && $name[2] == "Module")
			{
				if(!$template->IsPersistent)
				{
					$path .= "Core/Modules/";
				}
				else
				{
					$path .= "Configuration/".$hostname."/Templates/Modules/";
				}
					// Mod-Name
				$path .= $name[3]."/";
				
				if(!$template->IsPersistent)
				{
					$path .= "Templates/";
				}
				
					// View-Name
				$path .= $name[4]."/";
				
				if($template->Type == "SUBTEMPLATE")
				{
					$path .= "Subtemplates/";
				}
				
				$path .= $name[5].".html";
			}
			// Module.%Module%.%View%.%Name%
			// -> Configuration/Modules/%Module%/Templates/%View%/[Subtemplates/]%Name%.html
			// -> Configuration/%hostname%/Templates/Modules/%Module%/%View%/[Subtemplates/]%Name%.html
			else if($name[0] == "Module")
			{
				if(!$template->IsPersistent)
				{
					$path .= "Configuration/Modules/";
				}
				else
				{
					$path .= "Configuration/".$hostname."/Templates/Modules/";
				}
				
					// Mod-Name
				$path .= $name[1]."/";
				
				if(!$template->IsPersistent)
				{
					$path .= "Templates/";
				}
				
					// View-Name
				$path .= $name[2]."/";
				
				if($template->Type == "SUBTEMPLATE")
				{
					$path .= "Subtemplates/";
				}
				
				$path .= $name[3].".html";
			}
			else
			{
				$path .= "Configuration/".$hostname."/Templates/";
				
				if($template->Type == "SUBTEMPLATE")
				{
					$path .= "Subtemplates/";
				}
				
				$path .= $name[0].".html";
			}
			
			return $path;
		}
	}
?>