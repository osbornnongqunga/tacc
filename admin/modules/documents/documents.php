<?php
class DOCUMENTS extends MODULE
{
	function Display()
		{
			return $this->List_Categories();
		}
	function List_Categories()
		{
		 	$this->db = ADODB_Connect();
			
			if(isset($_GET['add']))
				{
					$list .= $this->Add_Category();
				}
			if(isset($_GET['edit']))
				{
					$list .= $this->Edit_Category();
				}
			if(isset($_GET['view']))
				{
				 	$list .= $this->View_Category();
				}
			
			$list .= '<h2>Document Categories</h2>';
		    $list .= '<a href="index.php?menu=documents&add"><img src="images/plus.png"></a>';
			
		    $query = $this->db->Prepare('select * from tbl_doc_categories order by category ASC');
		    $result = $this->db->Execute($query);
			
			if ( $result->RecordCount() > 0 )
			 {
			  	$list .= '<ol class="list">';
				  while (!$result->EOF)
					  {
						$data = $result->fields;
						$list .= '<li>'.$data['category'].'';
						$list .= ' | <a href="index.php?menu=documents&edit='.$data['id'].'">Edit</a>';
						$list .= ' | <a href="index.php?menu=documents&view='.$data['id'].'">View</a>';
						$list .= '</li>';
						$result->MoveNext();
					  }
				$list .= '</ol>';
			 }
			 else{
				   $list .= error('No documents categories created yet.');
				 }
					
			return $list;
		}
	function Add_Category()
	    {
		 	$status = '';
			 
			 if(isset($_GET['save']))
				{
				   $status = $this->Save_Category();
				}
			
			$this -> Add_Template_File('form','modules/documents/html/create_category.html');
			$parts['category'] = $_POST['category'];
			$parts['description'] = $_POST['description'];
			$parts['status'] = $status;
			return $this -> Populate_Template_Parts( 'form',$parts ); 
		}
	function Save_Category()
		{
		 	 if (trim($_POST['category']) == '') return error("Category Name is a required field");
			 if (!eregi('^[a-zA-Z0-9]', $_POST['category'])) return error("please enter a valid category name"); 
			 
			 $this->db = ADODB_Connect();
			 
			 $sql = "select * from tbl_doc_categories where id = -1";
			 $rs = $this->db->Execute($sql);
			 
			 $record = array();
			  
			 $record["category"] = addslashes($_POST['category']);
			 $record["description"] = addslashes($_POST['description']);
			 
			 $insertSQL = $this->db->GetInsertSQL($rs, $record);
		
			  if($this->db->Execute($insertSQL))
				 {
				   $this->Clear_Category();
				   return success('Category added successfully.');  
				 }
				 else{
					 return error('Could not successfully add category.');
				 }
		}
	function Clear_Category()
		{
			$_POST['category'] = '';
			$_POST['description'] = '';
		}
	function Edit_Category()
		{
		 	$status = '';
			 
			 if(isset($_GET['save']))
				{
				   $status = $this->Update_Category();
				}
				
			$this->db = ADODB_Connect();
			$query = $this->db->Prepare('select * from tbl_doc_categories where id = ? limit 1');
			$result = $this->db->Execute($query,$_GET['edit']);
				  
			  if ( $result->RecordCount() > 0 )
				  {
					$data = $result->fields;
					
					$_POST['category'] = $data['category'];
					$_POST['description'] = $data['description'];
					
					$this -> Add_Template_File('form','modules/documents/html/edit_category.html');
					$parts['id'] = $data['id'];
					$parts['category'] = $_POST['category'];
					$parts['description'] = $_POST['description'];
					$parts['status'] = $status;
					return $this -> Populate_Template_Parts( 'form',$parts );
				  }
				  else {
				   	return error('Category does not exist.');  
				  }
		}
	function Update_Category()
		{
			if (trim($_POST['category']) == '') return error("Category Name is a required field");
			if (!eregi('^[a-zA-Z0-9]', $_POST['category'])) return error("please enter a valid category name");
			
			$this->db = ADODB_Connect();
			$query = $this->db->Prepare('select * from tbl_doc_categories where id = ? limit 1');
			$result = $this->db->Execute($query,$_GET['edit']);
			
			$record = array();
			$record["category"] = $_POST['category'];
			$record["description"] = $_POST['description'];
			
			$updateSQL = $this->db->GetUpdateSQL($result, $record);
					  
			if($this->db->Execute($updateSQL))
				{
					return success('Category added successfully updated.');  
				}
				else{
					return error('Could not successfully update category.');	
				}
		}
	function View_Category()
		{
			$this->db = ADODB_Connect();
			
			 if(isset($_GET['add_doc']))
				{
				 	$list .= $this->Add_Doc_Form();
				}
			if(isset($_GET['delete']))
			    {
				    $sql = $this->db->Prepare('select id,file_name from tbl_docs where id = ? limit 1');
		            $rs = $this->db->Execute($sql,$_GET['delete']);
					
					if ( $rs->RecordCount() > 0 )
				 		{
							$query = $this->db->Prepare('delete from tbl_docs where id = ?');
							$result = $this->db->Execute($query,$_GET['delete']);
							if($result)
							   {
									$target = "../media/docs/";
									$target = $target . basename($rs->fields['file_name']) ;
									unlink($target);
									$list .= success('Document '.$rs->fields['file_name'].' deleted successfully');
							   }
						}
			    }
			
			$list .= '<h2>Category : '.Document_Category_Name($_GET['view']).'</h2>';
		    $list .= '<a href="index.php?menu=documents&view='.$_GET['view'].'&add_doc"><img src="images/plus.png"></a>';
			$list .= '<div style="clear:both;">';
			
			$query = $this->db->Prepare('select * from tbl_docs where category_id = ? order by date_filed desc');
		    $result = $this->db->Execute($query,$_GET['view']);
		  
		    if ( $result->RecordCount() > 0 )
				{
					 $no = 0;
					 
					 $list .= '<table class="hovertable">';
					 $list .= '<tr>';
					 $list .= '<th>No</th>';
					 $list .= '<th>Type</th>';
					 $list .= '<th>Name</th>';
					 $list .= '<th>Date</th>';
					 $list .= '<th>Remove</th>';
					 $list .= '</tr>';
					 
					while (!$result->EOF)
					   {
						   $data = $result->fields;
						   
							  $list .= '<tr>';
							  $list .= '<td>'.$no = ($no+1).'</td>';
							  $list .= '<td>'.stripslashes(Document_Icon($data['file_type'],$data['file_name'])).'</td>';
							  $list .= '<td><a href="http://'.$_SERVER['HTTP_HOST'].'/media/docs/'.$data['file_name'].'" target="_blank">'.stripslashes($data['file_name']).'</a></td>';
							  $list .= '<td>'.stripslashes($data['date_filed']).'</td>';
							  $list .= '<td><a href="index.php?menu=documents&view='.$_GET['view'].'&delete='.$data['id'].'">X</a></td>';
							  $list .= '</tr>';
			
						   $result->MoveNext();	
						}
					$list .= '</table>';
					
				}
				else{
					$list .= error('No documents loaded yet.');
				}
			
			return $list;
		}
	function Add_Doc_Form()
		{
		 	$status = '';
			
			if(isset($_GET['save']))
				{
					$status = $this-> Save_Document();
				}
			
			$this -> Add_Template_File('form','modules/documents/html/upload.html');
			$parts['id'] = $_GET['view'];
			$parts['description'] = $_POST['description'];
			$parts['status'] = $status;
			return $this -> Populate_Template_Parts( 'form',$parts );
		}
	function Save_Document()
		{
			$this->db = ADODB_Connect();
			$upload_directory = '../media/docs/';
			if ($_FILES['document']['name']!= '') 
				{
				   $random_digit=rand(0000,9999);
			if(move_uploaded_file($_FILES['document']['tmp_name'],$upload_directory.$random_digit.$_FILES['document']['name'])) 
					{
						$sql = "select * from tbl_docs where id = -1";
						$rs = $this->db->Execute($sql);
						
						$record = array();
						$record["category_id"] = addslashes($_GET['view']);
						$record["file_size"] = addslashes($_FILES['document']['size']);
						$record["file_type"] = addslashes($_FILES['document']['type']);
						$record["file_name"] = $random_digit.addslashes($_FILES['document']['name']);
						$record["date_filed"] = addslashes(DATETIME);
						$record["description"] = $_POST['description'];
						
						$insertSQL = $this->db->GetInsertSQL($rs, $record);
						
						if($this->db->Execute($insertSQL))
							{
							   return success('Document saved successfully.');
							}
							else {
								return error('Could not save document.');
							}
					}
					else {
					  return error('Could not move file to server.');
					}
				}
				else {
				 	return error('No document selected.');
				}
		}
}
?>
