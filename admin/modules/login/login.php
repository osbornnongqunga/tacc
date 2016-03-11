<?php
class LOGIN extends MODULE
{
	function Run_Login()
	{ 
		
		$status = '';
			
		if (isset($_GET['login']))
			{
			   $this->db = ADODB_Connect();
		
				$query = $this->db->Prepare('select * from tbl_user where username like ? limit 1');
				$result = $this->db->Execute($query, $_POST['username']);
				
				  if(!strlen($_POST['username']) == 0)
					{
					   //if entered username has special characters
					   if (!eregi('^[a-zA-Z0-9]', $_POST['username'])) $status .= error("Please enter a valid username");
					   
						if ((!$result) || ($result->RecordCount() == 0))
						{
						   //if entered username does not match
						   $status .= error('Invalid username entered');
						
						}
						else
							{
								//BRING UP THE CURRENT RECORDSET
								$data = $result->fields;
								
								if ( md5($_POST['password']) === $result->fields['password'])
									{
										//on successful login create a session for user to carry across
										$_SESSION['USER'] = $result->fields;
										//On successful login redirect to the index.php
										header("Location:index.php");
									}
								else
									{
										//if password entered does not match
										$status .= error('Invalid password!');
									} 
									   
							}		
					 }
					else{
						  //if the username field is left empty
						  $status .= error('Username is a required field.'); 
						}
					
			}
				
			 $this->Add_Template_File('login','modules/login/html/login.html');
			 $parts['username'] = $_POST['username'];
			 $parts['password'] = $_POST['password'];
			 $parts['status'] = $status;
			 return $this->Populate_Template_Parts('login', $parts);
	}
		
		
			  
}
?>