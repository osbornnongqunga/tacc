<?php
class APOSTLE extends MODULE
	{
	   function Display()
	       {
		      if(isset($_GET['executive']))
			     {
				   return $this->Executive();
				 }
			   if(isset($_GET['district']))
			     {
				   return $this->District();
				 }
			   if(isset($_GET['regional']))
			     {
				   return $this->Regional();
				 }
			   if(isset($_GET['departed']))
			     {
				   return $this->Departed();
				 }
		      $this->Add_Template_File('apostles','modules/apostles/html/chief.html');
			  $parts[''] = array();
			  return $this->Populate_Template_Parts('apostles', $parts);
		   }
		function Executive()
		   {
		      $this->Add_Template_File('executive','modules/apostles/html/executive.html');
			  $parts[''] = array();
			  return $this->Populate_Template_Parts('executive', $parts);
		   }
		function District()
		   {
		      $this->Add_Template_File('district','modules/apostles/html/district.html');
			  $parts[''] = array();
			  return $this->Populate_Template_Parts('district', $parts);
		   }
		function Regional()
		   {
		      $this->Add_Template_File('regional','modules/apostles/html/regional.html');
			  $parts[''] = array();
			  return $this->Populate_Template_Parts('regional', $parts);
		   }
		function Departed()
		   {
		      $this->Add_Template_File('departed','modules/apostles/html/departed.html');
			  $parts[''] = array();
			  return $this->Populate_Template_Parts('departed', $parts);
		   }
	}
?>
