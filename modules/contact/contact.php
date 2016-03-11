<?php
class CONTACT extends MODULE
	{
	   function Display()
	       {
		      $this->Add_Template_File('contact','modules/contact/html/contact.html');
			  $parts[''] = array();
			  return $this->Populate_Template_Parts('contact', $parts);
		   }
	}
?>
