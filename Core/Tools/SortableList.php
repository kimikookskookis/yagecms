<?php
	namespace YageCMS\Core\Tools;
	
	use \YageCMS\Core\Exception\EventPositioningModeInvalidException;
	
	class SortableList
	{
		  //
		 // ATTRIBUTES
		//	
		
		/**
		 * @var array
		 */
		private $items;
		
		  //
		 // CONSTRUCTOR
		//
		
		public function __construct()
		{
			$this->items = array();
		}
		
		  //
		 // METHODS
		//
		
		public function AddItem(SortableListItem $item, $position = "last")
		{
			switch(strtolower($position))
			{
				case "first":
					array_unshift($this->items, $item);
					break;
				
				case "last":
					$this->items[] = $item;
					break;
				
				default:
					
					$position = explode(":",$position);
					$mode = strtolower($position[0]);
					$search = $position[1];
					
					if($mode <> "before" && $mode <> "after")
					{
						$logcode = LogManager::_("Event Handler Position Mode '".$mode."' is not valid", LogItem::TYPE_ERROR);
						throw new EventPositioningModeInvalidException($logcode);
					}
					
					if($search == (string) ((int)$search))
					{
						$search = (int) $search;
					}
					
					$position = -1;
					
					foreach($this->items as $itemPosition => $itemValue)
					{
						if((!is_int($search) && $itemValue->GetSortableListKey() == $search) || (is_int($search) && $itemPosition == $search))
						{
							$position = $itemPosition;
							
							if($mode == "before") $position--;
							else $position++;
						}
					}
					
					if($position > -1)
					{
						$this->items[$position] = $item;
					}
					else
					{
						$this->items[] = $item;
					}
					
					break;
			}
			
			/*
			 * Now reorder the handlers within this event to add some spacing
			 * between the positions
			 */
			
			$position = 10;
			
			$reordered = array();
			
			foreach($this->items as $item)
			{
				$reordered[$position] = $item;
				$position += 10;
			}
			
			ksort($reordered);
			
			$this->items = $reordered;
			
			return true;
		}
	}
?>