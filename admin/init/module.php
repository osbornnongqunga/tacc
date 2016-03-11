<?php
//**********************************************************************************\\
//			Filename: "module.php"
//		 Description: Site startup functions for the special programmes Project.
//
//	   Last Modified: 10/05/2006
//
//	 All code for the special programmes project is written by Bruce Galpin.
//
// 		For support or site info e-mail: bruce.galpin@e-khaya.ecprov.gov.za
//
//**********************************************************************************\\

class MODULE
	{	
		var $template_files = array();
		
		#### PAGE PART TEMPLATE CONTROLS FOR MODULES

		function Add_Template_File( $template_name, $html_file )
			{
				// Load the Template into Module Array.
				$tfile = fopen( $html_file, 'r' );				
				if (!$tfile) return 'Could not load Page Template !';				
				
				$html_data = fread( $tfile, filesize($html_file) );
				if (!$html_data) return 'Could not read html file!';
				
				$this -> template_files[$template_name] = $html_data;
				
				fclose( $tfile );	
				
				return true;
			}
		
		#################################################################################################################
			
		// Loads the specified Page Template from array and replaces the {page_part} variables with their coresponding values.
		function Populate_Template_Parts( $template_name, $template_parts )
			{
				$html_data = $this -> template_files[$template_name];
				
				// Replace Page Parts with Values
				while( list($var, $value) = each($template_parts) )
					{
						$html_data = str_replace( '{'.$var.'}', $value, $html_data );						
					}
					
				return $html_data;						
			}		
	}

?>