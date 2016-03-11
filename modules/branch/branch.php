<?php
class BRANCH extends MODULE
	{
	   function Display()
	       {
		      $this->Add_Template_File('branch','modules/branch/html/branch.html');
			  $parts[''] = array();
			  return $this->Populate_Template_Parts('branch', $parts);
		   }
	}
?>

