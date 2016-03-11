<?php
class GALLARY extends MODULE
{
	function Display()
		{
			return $this-> List_Gallaries();
		}
	function List_Gallaries()
		{
			if(isset($_GET['add']))
				{
					$list .= $this->Add_Gallary();
				}
			if(isset($_GET['edit']))
				{
					$list .= $this->Edit_Gallary();
				}
			if(isset($_GET['view']))
				{
				 	$list .= $this->View_Gallary();
				}
					
		    $list .= '<h2>Gallaries</h2>';
		    $list .= '<a href="index.php?menu=gallary&add"><img src="images/plus.png"></a>';
			
		 	$this->db = ADODB_Connect();
		    $query = $this->db->Prepare('select * from tbl_gallaries order by date_time DESC');
		    $result = $this->db->Execute($query);
			
			if ( $result->RecordCount() > 0 )
			 {
			  	$list .= '<ol class="list">';
				  while (!$result->EOF)
					  {
						$data = $result->fields;
						$list .= '<li>'.$data['name'].'';
						$list .= ' | <a href="index.php?menu=gallary&edit='.$data['id'].'">Edit</a>';
						$list .= ' | <a href="index.php?menu=gallary&view='.$data['id'].'">View</a>';
						$list .= '</li>';
						$result->MoveNext();
					  }
				$list .= '</ol>';
			 }
			 else{
				   $list .= error('No photo gallaries created yet.');
				 }
					
			return $list;
		}
	function Add_Gallary()
		{
			$status = '';
			 
			 if(isset($_GET['save']))
				{
				   $status = $this->Save_Gallary();
				}
			
			$this -> Add_Template_File('form','modules/gallary/html/create_gallary.html');
			$parts['gallary'] = $_POST['gallary'];
			$parts['description'] = $_POST['description'];
			$parts['status'] = $status;
			return $this -> Populate_Template_Parts( 'form',$parts ); 
		}
	function Save_Gallary()
		{
			 if (trim($_POST['gallary']) == '') return error("Gallary Name is a required field");
			 if (!eregi('^[a-zA-Z0-9]', $_POST['gallary'])) return error("please enter a valid gallary name"); 
			 
			 $this->db = ADODB_Connect();
			 
			 $sql = "select * from tbl_gallaries where id = -1";
			 $rs = $this->db->Execute($sql);
			 
			 $record = array();
			  
			 $record["name"] = addslashes($_POST['gallary']);
			 $record["uid"] = addslashes($_SESSION['USER']['uid']);
			 $record["description"] = addslashes($_POST['description']);
			 $record["date_time"] = addslashes(DATETIME);
			 
			 $insertSQL = $this->db->GetInsertSQL($rs, $record);
		
			  if($this->db->Execute($insertSQL))
				 {
				   $this->Clear_Gallary();
				   return success('Gallary added successfully.');  
				 }
				 else{
					 return error('Could not successfully add gallary.');
				 }
		}
	function Clear_Gallary()
		{
		   $_POST['gallary'] = '';
		   $_POST['description'] = '';
		}
	function Edit_Gallary()
		{
		 	$status = '';
			 
			 if(isset($_GET['save']))
				{
				   $status = $this->Update_Gallary();
				}
				
			$this->db = ADODB_Connect();
			$query = $this->db->Prepare('select * from tbl_gallaries where id = ? limit 1');
			$result = $this->db->Execute($query,$_GET['edit']);
				  
			  if ( $result->RecordCount() > 0 )
				  {
					$data = $result->fields;
					
					$_POST['gallary'] = $data['name'];
					$_POST['description'] = $data['description'];
					
					$this -> Add_Template_File('form','modules/gallary/html/edit_gallary.html');
					$parts['id'] = $data['id'];
					$parts['gallary'] = $_POST['gallary'];
					$parts['description'] = $_POST['description'];
					$parts['status'] = $status;
					return $this -> Populate_Template_Parts( 'form',$parts );
				  }
				  else {
				   	return error('Gallary does not exist.');  
				  }
		}
	function Update_Gallary()
		{
		 	if (trim($_POST['gallary']) == '') return error("Gallary Name is a required field");
			if (!eregi('^[a-zA-Z0-9]', $_POST['gallary'])) return error("please enter a valid gallary name");
			
			$this->db = ADODB_Connect();
			$query = $this->db->Prepare('select * from tbl_gallaries where id = ? limit 1');
			$result = $this->db->Execute($query,$_GET['edit']);
			
			$record = array();
			$record["name"] = $_POST['gallary'];
			$record["description"] = $_POST['description'];
			
			$updateSQL = $this->db->GetUpdateSQL($result, $record);
					  
			if($this->db->Execute($updateSQL))
				{
					return success('Gallary added successfully updated.');  
				}
				else{
					return error('Could not successfully update gallary.');	
				}
		}
	function View_Gallary()
		{
		    $this->db = ADODB_Connect();
			
		    if(isset($_GET['add_pic']))
				{
				 	$list .= $this->Add_Pic_Form();
				}
			if(isset($_GET['delete']))
			    {
				    $sql = $this->db->Prepare('select id,file_name from tbl_gallary_pics where id = ? limit 1');
		            $rs = $this->db->Execute($sql,$_GET['delete']);
					
					if ( $rs->RecordCount() > 0 )
				 		{
							$query = $this->db->Prepare('delete from tbl_gallary_pics where id = ?');
							$result = $this->db->Execute($query,$_GET['delete']);
							if($result)
							   {
									$target = "../media/pics/";
									$target = $target . basename($rs->fields['file_name']) ;
									unlink($target);
									$list .= success('Picture '.$rs->fields['file_name'].' deleted successfully');
							   }
						}
			    }
				
			$list .= '<h2>Gallary : '.Gallary_Name($_GET['view']).'</h2>';
		    $list .= '<a href="index.php?menu=gallary&view='.$_GET['view'].'&add_pic"><img src="images/plus.png"></a>';
			$list .= '<div style="clear:both;">';
			
			
		    $query = $this->db->Prepare('select * from tbl_gallary_pics where gallary_id = ? order by date_filed DESC');
		    $result = $this->db->Execute($query,$_GET['view']);
			
			 if ( $result->RecordCount() > 0 )
				 {
					  while (!$result->EOF)
						  {
							$data = $result->fields;
							
							$list .= '<div class="col-3">';
							$list .= '<div class="single-member">';
							$list .= '<img src="../media/pics/'.$data['file_name'].'" width="100%" height="100%">';
							$list .= '<div class="member-info">';
							$list .= '<h5>'.$data['file_name'].'</h5>';
							$list .= '<a href="index.php?menu=gallary&view='.$_GET['view'].'&delete='.$data['id'].'">X</a>';
							$list .= '</div>';
							$list .= '</div>';
							$list .= '</div>';
							
						    $result->MoveNext();
						  }
				 }
				 else{
					   $list .= error('No pictures loaded yet on this gallery.');
					 }
			
			return $list;
		}
	function Add_Pic_Form()
		{
		 	$status = '';
			
			if(isset($_GET['save']))
				{
					$status = $this-> Save_Pictures();
				}
			
			$this -> Add_Template_File('form','modules/gallary/html/upload.html');
			$parts['id'] = $_GET['view'];
			$parts['status'] = $status;
			return $this -> Populate_Template_Parts( 'form',$parts );
		}
	function Save_Pictures()
		{
		 	$this->db = ADODB_Connect();
			
			$number_of_file_fields = 0;
			$number_of_uploaded_files = 0;
			$number_of_moved_files = 0;
			$uploaded_files = array();
			$upload_directory = '../media/pics/'; 
			
			for ($i = 0; $i < count($_FILES['images']['name']); $i++) 
				{
					$number_of_file_fields++;
					if ($_FILES['images']['name'][$i] != '') 
					   { 
						 $file_type = explode('/',$_FILES['images']['type'][$i]);
						 $file_type = $file_type[0];
						 if($file_type!='image') return error('Only images are allowed to be uploaded in this module.').$file_type;
						 
						 $random_digit=rand(0000,9999);
						 $number_of_uploaded_files++;
						 $uploaded_files[] = $random_digit.$_FILES['images']['name'][$i];
						  if (move_uploaded_file($_FILES['images']['tmp_name'][$i], $upload_directory.$random_digit.$_FILES['images']['name'][$i])) 
							 {
								$sql = "select * from tbl_gallary_pics where id = -1";
								$rs = $this->db->Execute($sql);
								
								$record = array();
								$record["gallary_id"] = addslashes($_GET['view']);
								$record["file_size"] = addslashes($_FILES['images']['size'][$i]);
								$record["file_type"] = addslashes($_FILES['images']['type'][$i]);
								$record["file_name"] = $random_digit.addslashes($_FILES['images']['name'][$i]);
								$record["date_filed"] = addslashes(DATETIME);
								
								$insertSQL = $this->db->GetInsertSQL($rs, $record);
								
								if($this->db->Execute($insertSQL))
								
								$number_of_moved_files++;
							 }
			
					   }
			
				 }
			$list .= 'Number of Picture fields created '.$number_of_file_fields.'<br/> ';
			$list .= 'Number of Pictures submitted '.$number_of_uploaded_files.'<br/>';
			$list .= 'Number of successfully moved Pictures '.$number_of_moved_files.'<br/>';
			$list .= 'Pictures Names are <br/>'.implode(',', $uploaded_files);
			return success($list);
		}
}
?>