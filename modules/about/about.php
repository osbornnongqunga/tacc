<?php
class ABOUT extends MODULE
	{
	   function Display()
	       {
		      if(isset($_GET['dogma']))
			     {
				    return $this->Dogma();
				 }
			   if(isset($_GET['projects']))
			     {
				    return $this->Projects();
				 }
		      $this->Add_Template_File('about','modules/about/html/about.html');
			  $parts[''] = array();
			  return $this->Populate_Template_Parts('about', $parts);
		   }
	   function Dogma()
	       {
		      $this->Add_Template_File('dogma','modules/about/html/dogma.html');
			  $parts[''] = array();
			  return $this->Populate_Template_Parts('dogma', $parts);
		   }
	   function Projects()
	       {
		      $this->Add_Template_File('projects','modules/about/html/projects.html');
			  $parts[''] = array();
			  return $this->Populate_Template_Parts('projects', $parts);
		   }   
	}
?>
