<?php
class MENU extends MODULE
	{
	   function Display()
	       {
		      $this->Add_Template_File('menu','modules/menu/html/menu.html');
			  $parts[''] = array();
			  return $this->Populate_Template_Parts('menu', $parts);
		   }
	}
?>
