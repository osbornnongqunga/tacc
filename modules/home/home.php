<?php
class HOME extends MODULE
	{
	   function Display()
	       {
		      $this->Add_Template_File('home','modules/home/html/home.html');
			  $parts[''] = array();
			  return $this->Populate_Template_Parts('home', $parts);
		   }
	}
?>
