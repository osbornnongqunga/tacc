<?php
class PODCASTS extends MODULE
{
	function Display()
		{
			return $this->List_Channels();
		}
	function List_Channels()
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
			
			$list .= '<h2>Podcast</h2>';
		    $list .= '<a href="index.php?menu=podcasts&add"><img src="images/plus.png"></a>';
			
			$query = $this->db->Prepare('select * from tbl_podcasts_categories order by category ASC');
		    $result = $this->db->Execute($query);
			
			if ( $result->RecordCount() > 0 )
			 {
			  	$list .= '<ol class="list">';
				  while (!$result->EOF)
					  {
						$data = $result->fields;
						$list .= '<li>'.$data['category'].'';
						$list .= ' | <a href="index.php?menu=podcasts&edit='.$data['id'].'">Edit</a>';
						$list .= ' | <a href="index.php?menu=podcasts&view='.$data['id'].'">View</a>';
						$list .= '</li>';
						$result->MoveNext();
					  }
				$list .= '</ol>';
			 }
			 else{
				   $list .= error('No podcasts categories created yet.');
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
			
			$this -> Add_Template_File('form','modules/podcasts/html/create_category.html');
			$parts['category'] = $_POST['category'];
			$parts['status'] = $status;
			return $this -> Populate_Template_Parts( 'form',$parts );
		}
	function Save_Category()
		{
		 	 if (trim($_POST['category']) == '') return error("Category Name is a required field");
			 if (!eregi('^[a-zA-Z0-9]', $_POST['category'])) return error("please enter a valid category name"); 
			 
			 $this->db = ADODB_Connect();
			 
			 $sql = "select * from tbl_podcasts_categories where id = -1";
			 $rs = $this->db->Execute($sql);
			 
			 $record = array();
			  
			 $record["category"] = addslashes($_POST['category']);
			 
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
		}
	function Edit_Category()
		{
		 	$status = '';
			 
			 if(isset($_GET['save']))
				{
				   $status = $this->Update_Category();
				}
				
			$this->db = ADODB_Connect();
			$query = $this->db->Prepare('select * from tbl_podcasts_categories where id = ? limit 1');
			$result = $this->db->Execute($query,$_GET['edit']);
				  
			  if ( $result->RecordCount() > 0 )
				  {
					$data = $result->fields;
					
					$_POST['category'] = $data['category'];
					
					$this -> Add_Template_File('form','modules/podcasts/html/edit_category.html');
					$parts['id'] = $data['id'];
					$parts['category'] = $_POST['category'];
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
			$query = $this->db->Prepare('select * from tbl_podcasts_categories where id = ? limit 1');
			$result = $this->db->Execute($query,$_GET['edit']);
			
			$record = array();
			$record["category"] = $_POST['category'];
			
			$updateSQL = $this->db->GetUpdateSQL($result, $record);
					  
			if($this->db->Execute($updateSQL))
				{
					return success('Category successfully updated.');  
				}
				else{
					return error('Could not successfully update category.');	
				}
		}
	function View_Category()
		{
			$this->db = ADODB_Connect();
			
			if(isset($_GET['add_podcast']))
				{
					$list .= $this->Add_Podcast_Form();
				}
			if(isset($_GET['edit_podcast']))
			 	{
				 	$list .= $this->Edit_Podcast_Form();
				}
			if(isset($_GET['publish']))
			 	{
				 	$query = $this->db->Prepare('update tbl_podcasts set publish = 1 where id = ?');
					$result = $this->db->Execute($query,$_GET['publish']);
					if($result)
						{
							$list .= success('Podcast published successfully.');
						}
				}
			if(isset($_GET['unpublish']))
			 	{
					$query = $this->db->Prepare('update tbl_podcasts set publish = 0 where id = ?');
					$result = $this->db->Execute($query,$_GET['unpublish']);
					if($result)
						{
							$list .= success('Podcast unpublished successfully.');
						}
				}	
			
			$list .= '<h2>Category : '.Podcasts_Category_Name($_GET['view']).'</h2>';
		    $list .= '<a href="index.php?menu=podcasts&view='.$_GET['view'].'&add_podcast"><img src="images/plus.png"></a>';
			$list .= '<div style="clear:both;">';
			
			$query = $this->db->Prepare('select * from tbl_podcasts where category = ? order by date_time desc');
		    $result = $this->db->Execute($query,$_GET['view']);
		  
		    if ( $result->RecordCount() > 0 )
				{
					 $no = 0;
					 
					 $list .= '<table class="hovertable">';
					 $list .= '<tr>';
					 $list .= '<th>No</th>';
					 $list .= '<th>Category</th>';
					 $list .= '<th>Title</th>';
					 $list .= '<th>Date</th>';
					 $list .= '<th>Edit</th>';
					 $list .= '<th>Publish</th>';
					 $list .= '</tr>';
					 
					while (!$result->EOF)
					   {
						   $data = $result->fields;
						   
							  $list .= '<tr>';
							  $list .= '<td>'.$no = ($no+1).'</td>';
							  $list .= '<td>'.Podcasts_Category_Name($data['category']).'</td>';
							  $list .= '<td>'.$data['title'].'</td>';
							  $list .= '<td>'.stripslashes($data['date_time']).'</td>';
							  $list .= '<td><a href="index.php?menu=podcasts&view='.$_GET['view'].'&edit_podcast='.$data['id'].'">Edit</a></td>';
							  
								if($data['publish'] == 0) {
									$list .= '<td><a href="index.php?menu=podcasts&view='.$_GET['view'].'&publish='.$data['id'].'">Publish</a></td>';
								}
								else {
									$list .= '<td><a href="index.php?menu=podcasts&view='.$_GET['view'].'&unpublish='.$data['id'].'">Un-publish</a></td>';
								}
								
							  $list .= '</tr>';
			
						   $result->MoveNext();	
						}
					$list .= '</table>';
					
				}
				else{
					$list .= error('No '.Podcasts_Category_Name($_GET['view']).' loaded yet.');
				}
			
			return $list;
		}
	function Add_Podcast_Form()
		{
		 	$status = '';
			
			if(isset($_GET['save']))
				{
					$status = $this-> Save_Podcast();
				}
			
			$this -> Add_Template_File('form','modules/podcasts/html/upload.html');
			$parts['id'] = $_GET['view'];
			$parts['title'] = $_POST['title'];
			$parts['description'] = $_POST['description'];
			$parts['code'] = $_POST['code'];
			$parts['podcast_category'] = Podcast_Category_Select($_POST['podcast_category']);
			$parts['status'] = $status;
			return $this -> Populate_Template_Parts( 'form',$parts );
		}
	function Save_Podcast()
		{
			 if (trim($_POST['title']) == '') return error("Podcast title is a required field");
			 if (!eregi('^[a-zA-Z0-9]', $_POST['title'])) return error("please enter a valid podcast title"); 
			 
			 $this->db = ADODB_Connect();
			 
			 $sql = "select * from tbl_podcasts where id = -1";
			 $rs = $this->db->Execute($sql);
			 
			 $record = array();
			  
			 $record["title"] = $_POST['title'];
			 $record["description"] = $_POST['description'];
			 $record["code"] = $_POST['code'];
			 $record["category"] = $_POST['podcast_category'];
			 $record["author"] = $_SESSION['USER']['uid'];
			 $record["date_time"] = DATETIME;
			 $record["publish"] = 0;
			 
			 $insertSQL = $this->db->GetInsertSQL($rs, $record);
		
			  if($this->db->Execute($insertSQL))
				 {
				    $this->Clear_Podcast();
				    return success('Podcast added successfully.');  
				 }
				 else{
					 return error('Could not successfully add podcast.');
				 }
		}
	function Clear_Podcast()
		{
		   $_POST['title'] = '';
		   $_POST['description'] = '';
		   $_POST['code'] = '';
		   $_POST['podcast_category'] = '';
		}
	function Edit_Podcast_Form()
	    {
			$status = '';
			 
			 if(isset($_GET['save']))
				{
				   $status = $this->Update_Podcast();
				}
				
			$this->db = ADODB_Connect();
			$query = $this->db->Prepare('select * from tbl_podcasts where id = ? limit 1');
			$result = $this->db->Execute($query,$_GET['edit_podcast']);
				  
			  if ( $result->RecordCount() > 0 )
				  {
					$data = $result->fields;
					
					$_POST['title'] = $data['title'];
					$_POST['description'] = $data['description'];
					$_POST['code'] = $data['code'];
					$_POST['podcast_category'] = $data['category'];
					
					$this -> Add_Template_File('form','modules/podcasts/html/edit_upload.html');
					$parts['id'] = $_GET['view'];
					$parts['podcast_id'] = $data['id'];
					$parts['title'] = $_POST['title'];
					$parts['description'] = $_POST['description'];
					$parts['code'] = $_POST['code'];
					$parts['podcast_category'] = Podcast_Category_Select($_POST['podcast_category']);
					$parts['status'] = $status;
					return $this -> Populate_Template_Parts( 'form',$parts );
				  }
				  else {
				   	return error('Podcast does not exist.');  
				  }
		}
	function Update_Podcast()
		{
			if (trim($_POST['title']) == '') return error("Podcast title is a required field");
			if (!eregi('^[a-zA-Z0-9]', $_POST['title'])) return error("please enter a valid podcast title");
			 
			$this->db = ADODB_Connect();
			$query = $this->db->Prepare('select * from tbl_podcasts where id = ? limit 1');
			$result = $this->db->Execute($query,$_GET['edit_podcast']);
			
			$record = array();
			$record["title"] = $_POST['title'];
			$record["description"] = $_POST['description'];
			$record["code"] = $_POST['code'];
			$record["category"] = $_POST['podcast_category'];
			$record["author"] = $_SESSION['USER']['uid'];
			$record["date_time"] = DATETIME;
			
			$updateSQL = $this->db->GetUpdateSQL($result, $record);
					  
			if($this->db->Execute($updateSQL))
				{
					return success('Podcast successfully updated.'); 
				}
				else{
					return error('Could not successfully update podcast.');	
				}
		}
}
?>
