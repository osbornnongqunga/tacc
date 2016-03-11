<?php
class NEWS extends MODULE
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
					
			$list .= '<h2>News Categories</h2>';
		    $list .= '<a href="index.php?menu=news&add"><img src="images/plus.png"></a>';
			
		    $query = $this->db->Prepare('select * from tbl_news_categories order by category ASC');
		    $result = $this->db->Execute($query);
			
			if ( $result->RecordCount() > 0 )
			 {
			  	$list .= '<ol class="list">';
				  while (!$result->EOF)
					  {
						$data = $result->fields;
						$list .= '<li>'.$data['category'].'';
						$list .= ' | <a href="index.php?menu=news&edit='.$data['id'].'">Edit</a>';
						$list .= ' | <a href="index.php?menu=news&view='.$data['id'].'">View</a>';
						$list .= '</li>';
						$result->MoveNext();
					  }
				$list .= '</ol>';
			 }
			 else{
				   $list .= error('No news categories created yet.');
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
			
			$this -> Add_Template_File('form','modules/news/html/create_category.html');
			$parts['category'] = $_POST['category'];
			$parts['status'] = $status;
			return $this -> Populate_Template_Parts( 'form',$parts ); 
		}
	function Save_Category()
		{
		 	 if (trim($_POST['category']) == '') return error("Category Name is a required field");
			 if (!eregi('^[a-zA-Z0-9]', $_POST['category'])) return error("please enter a valid category name"); 
			 
			 $this->db = ADODB_Connect();
			 
			 $sql = "select * from tbl_news_categories where id = -1";
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
			$query = $this->db->Prepare('select * from tbl_news_categories where id = ? limit 1');
			$result = $this->db->Execute($query,$_GET['edit']);
				  
			  if ( $result->RecordCount() > 0 )
				  {
					$data = $result->fields;
					
					$_POST['category'] = $data['category'];
					
					$this -> Add_Template_File('form','modules/news/html/edit_category.html');
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
			$query = $this->db->Prepare('select * from tbl_news_categories where id = ? limit 1');
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
			
			 if(isset($_GET['add_news']))
				{
					$list .= $this->Add_News_Form();
				}
			 if(isset($_GET['edit_article']))
			 	{
				 	$list .= $this->Edit_News_Form();
				}
			 if(isset($_GET['publish']))
			 	{
				 	$query = $this->db->Prepare('update tbl_news set publish = 1 where id = ?');
					$result = $this->db->Execute($query,$_GET['publish']);
					if($result)
						{
							$list .= success('Article published successfully.');
						}
				}
			if(isset($_GET['unpublish']))
			 	{
					$query = $this->db->Prepare('update tbl_news set publish = 0 where id = ?');
					$result = $this->db->Execute($query,$_GET['unpublish']);
					if($result)
						{
							$list .= success('Article unpublished successfully.');
						}
				}	
			
			$list .= '<h2>Category : '.News_Category_Name($_GET['view']).'</h2>';
		    $list .= '<a href="index.php?menu=news&view='.$_GET['view'].'&add_news"><img src="images/plus.png"></a>';
			$list .= '<div style="clear:both;">';
			
			$query = $this->db->Prepare('select * from tbl_news where category = ? order by date_time desc');
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
					 $list .= '<th>Start Date</th>';
					 $list .= '<th>End Date</th>';
					 $list .= '<th>Edit</th>';
					 $list .= '<th>Publish</th>';
					 $list .= '</tr>';
					 
					while (!$result->EOF)
					   {
						   $data = $result->fields;
						   
							  $list .= '<tr>';
							  $list .= '<td>'.$no = ($no+1).'</td>';
							  $list .= '<td>'.News_Category_Name($data['category']).'</td>';
							  $list .= '<td>'.$data['title'].'</td>';
							  $list .= '<td>'.stripslashes($data['date_time']).'</td>';
							  $list .= '<td>'.stripslashes($data['start']).'</td>';
							  $list .= '<td>'.stripslashes($data['end']).'</td>';
							  $list .= '<td><a href="index.php?menu=news&view='.$_GET['view'].'&edit_article='.$data['id'].'">Edit</a></td>';
							  
								if($data['publish'] == 0) {
									$list .= '<td><a href="index.php?menu=news&view='.$_GET['view'].'&publish='.$data['id'].'">Publish</a></td>';
								}
								else {
									$list .= '<td><a href="index.php?menu=news&view='.$_GET['view'].'&unpublish='.$data['id'].'">Un-publish</a></td>';
								}
								
							  $list .= '</tr>';
			
						   $result->MoveNext();	
						}
					$list .= '</table>';
					
				}
				else{
					$list .= error('No '.News_Category_Name($_GET['view']).' loaded yet.');
				}
			
			return $list;
		}
	function Add_News_Form()
		{
			$status = '';
			
			if(isset($_GET['save']))
				{
					$status = $this-> Save_News();
				}
			
			$this -> Add_Template_File('form','modules/news/html/upload.html');
			$parts['id'] = $_GET['view'];
			$parts['title'] = $_POST['title'];
			$parts['news_category'] = News_Category_Select($_POST['news_category']);
			$parts['description'] = $_POST['description'];
			$parts['start'] = $_POST['start'];
			$parts['end'] = $_POST['end'];
			$parts['status'] = $status;
			return $this -> Populate_Template_Parts( 'form',$parts );
		}
	function Save_News()
		{
			 if (trim($_POST['title']) == '') return error("News title is a required field");
			 if (!eregi('^[a-zA-Z0-9]', $_POST['title'])) return error("please enter a valid news title"); 
			 
			 $this->db = ADODB_Connect();
			 $id = MaxId() + 1;
			 
			 $sql = "select * from tbl_news where id = -1";
			 $rs = $this->db->Execute($sql);
			 
			 $record = array();
			  
			 $record["title"] = $_POST['title'];
			 $record["description"] = $_POST['description'];
			 $record["start"] = $_POST['start'];
			 $record["end"] = $_POST['end'];
			 $record["category"] = $_POST['news_category'];
			 $record["author"] = $_SESSION['USER']['uid'];
			 $record["date_time"] = DATETIME;
			 $record["publish"] = 0;
			 
			 $insertSQL = $this->db->GetInsertSQL($rs, $record);
		
			  if($this->db->Execute($insertSQL))
				 {
				   $this->Clear_News();

				   $upload_directory = '../media/pics/';
				   
					if ($_FILES['img']['name']!= '') 
						{
							$random_digit=rand(0000,9999);
							if(move_uploaded_file($_FILES['img']['tmp_name'],$upload_directory.$random_digit.$_FILES['img']['name'])) 
									{
										$sql = "select * from tbl_news_pics where id = -1";
										$rs = $this->db->Execute($sql);
										
										$record = array();
										$record["news_id"] = addslashes($id);
										$record["file_size"] = addslashes($_FILES['img']['size']);
										$record["file_type"] = addslashes($_FILES['img']['type']);
										$record["file_name"] = $random_digit.addslashes($_FILES['img']['name']);
										$record["date_filed"] = addslashes(DATETIME);
										
										$insertSQL = $this->db->GetInsertSQL($rs, $record);
										
										if($this->db->Execute($insertSQL))
											{
											   $list .= success('Image saved successfully.');
											}
											else {
												$list .=  error('Could not save image.');
											}
									}
									else {
									  $list .=  error('Could not move image to server.');
									}
						}
						
				   $list .=  success('News added successfully.');  
				 }
				 else{
					 $list .=  error('Could not successfully add news.');
				 }
			return $list;
		}
	function Clear_News()
		{
		 	$_POST['title'] = '';
			$_POST['description'] = '';
			$_POST['start'] = '';
			$_POST['end'] = '';
			$_POST['news_category'] = '';
		}
	function Edit_News_Form()
		{
			$status = '';
			 
			 if(isset($_GET['save']))
				{
				   $status = $this->Update_News();
				}
				
			$this->db = ADODB_Connect();
			$query = $this->db->Prepare('select * from tbl_news where id = ? limit 1');
			$result = $this->db->Execute($query,$_GET['edit_article']);
				  
			  if ( $result->RecordCount() > 0 )
				  {
					$data = $result->fields;
					
					$_POST['title'] = $data['title'];
					$_POST['description'] = $data['description'];
					$_POST['start'] = $data['start'];
					$_POST['end'] = $data['end'];
					$_POST['news_category'] = $data['category'];
					
					$this -> Add_Template_File('form','modules/news/html/edit_upload.html');
					$parts['id'] = $_GET['view'];
					$parts['artitle_id'] = $data['id'];
					$parts['title'] = $_POST['title'];
					$parts['description'] = $_POST['description'];
					$parts['start'] = $_POST['start'];
			        $parts['end'] = $_POST['end'];
					$parts['news_category'] = News_Category_Select($_POST['news_category']);
					$parts['pic'] = Show_Article_Pic($data['id']);
					$parts['status'] = $status;
					return $this -> Populate_Template_Parts( 'form',$parts );
				  }
				  else {
				   	return error('News article does not exist.');  
				  }
		}
	function Update_News()
		{
		 	if (trim($_POST['title']) == '') return error("News title is a required field");
			if (!eregi('^[a-zA-Z0-9]', $_POST['title'])) return error("please enter a valid news title");
			 
			$this->db = ADODB_Connect();
			$query = $this->db->Prepare('select * from tbl_news where id = ? limit 1');
			$result = $this->db->Execute($query,$_GET['edit_article']);
			
			$record = array();
			$record["title"] = $_POST['title'];
			$record["description"] = $_POST['description'];
			$record["start"] = $_POST['start'];
			$record["end"] = $_POST['end'];
			$record["category"] = $_POST['news_category'];
			$record["author"] = $_SESSION['USER']['uid'];
			$record["date_time"] = DATETIME;
			
			$updateSQL = $this->db->GetUpdateSQL($result, $record);
					  
			if($this->db->Execute($updateSQL))
				{
					//DELETE THE CURRENT IMAGE IF PICTURE IS SELECTED
					$upload_directory = '../media/pics/';
				   
					if ($_FILES['img']['name']!= '') 
						{
							//DELETE OLD
						    $sql = $this->db->Prepare('select id,file_name from tbl_news_pics where news_id = ? limit 1');
							$rs = $this->db->Execute($sql,$_GET['edit_article']);
							
							if ( $rs->RecordCount() > 0 )
								{
									$query = $this->db->Prepare('delete from tbl_news_pics where news_id = ?');
									$result = $this->db->Execute($query,$_GET['edit_article']);
									if($result)
									   {
											$target = $upload_directory . basename($rs->fields['file_name']) ;
											unlink($target);
											$list .= success('Picture '.$rs->fields['file_name'].' deleted successfully');
									   }
								}
							//UPLOAD NEW
							$random_digit=rand(0000,9999);
							if(move_uploaded_file($_FILES['img']['tmp_name'],$upload_directory.$random_digit.$_FILES['img']['name'])) 
									{
										$sql = "select * from tbl_news_pics where id = -1";
										$rs = $this->db->Execute($sql);
										
										$record = array();
										$record["news_id"] = addslashes($_GET['edit_article']);
										$record["file_size"] = addslashes($_FILES['img']['size']);
										$record["file_type"] = addslashes($_FILES['img']['type']);
										$record["file_name"] = $random_digit.addslashes($_FILES['img']['name']);
										$record["date_filed"] = addslashes(DATETIME);
										
										$insertSQL = $this->db->GetInsertSQL($rs, $record);
										
										if($this->db->Execute($insertSQL))
											{
											   $list .= success('Image saved successfully.');
											}
											else {
												$list .=  error('Could not save image.');
											}
									}
									else {
									  $list .=  error('Could not move image to server.');
									}
						}
					$list .= success('News article successfully updated.'); 
					return $list;
				}
				else{
					return error('Could not successfully update news article.');	
				}
		}
}
?>