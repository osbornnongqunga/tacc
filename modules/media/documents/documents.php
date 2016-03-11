<?php
class DOCUMENTS extends MODULE
{
	function Display()
		{
			$this->Add_Template_File('documents','modules/media/documents/html/container.html');
			$parts['content'] = $this->List_Categories();
			return $this->Populate_Template_Parts('documents', $parts);
		}
	function List_Categories()
		{
			$this->db = ADODB_Connect();
			
			if(isset($_GET['view']))
				{
				 	$list .= $this->View_Category();
				}
			
			$list .= '<h2>Documents</h2>';
			
			$query = $this->db->Prepare('select * from tbl_doc_categories order by category ASC');
		    $result = $this->db->Execute($query);
			
			if ( $result->RecordCount() > 0 )
			 {
			  	$list .= '<ol class="list">';
				  while (!$result->EOF)
					  {
						$data = $result->fields;
						$list .= '<li>'.$data['category'].'';
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
	function View_Category()
		{
			$this->db = ADODB_Connect();
			$list .= '<h2>'.Document_Category_Name($_GET['view']).'</h2>';
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
					 $list .= '</tr>';
					 
					while (!$result->EOF)
					   {
						   $data = $result->fields;
						   
							  $list .= '<tr>';
							  $list .= '<td>'.$no = ($no+1).'</td>';
							  $list .= '<td>'.stripslashes(Document_Icon($data['file_type'],$data['file_name'])).'</td>';
							  $list .= '<td><a href="http://'.$_SERVER['HTTP_HOST'].'/media/docs/'.$data['file_name'].'" target="_blank">'.stripslashes($data['file_name']).'</a></td>';
							  $list .= '<td>'.stripslashes($data['date_filed']).'</td>';
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
	function Latest_Documents()
		{
			$this->Add_Template_File('documents','modules/media/documents/html/home_container.html');
			$parts['content'] = $this->List_Latest_Documents();
			return $this->Populate_Template_Parts('documents', $parts);
		}
	function List_Latest_Documents()
		{
			$this->db = ADODB_Connect();
			
			$query = $this->db->Prepare('select * from tbl_docs order by date_filed desc limit 3');
		    $result = $this->db->Execute($query);
		  
		    if ( $result->RecordCount() > 0 )
				{
					 $no = 0;
					 
					 $list .= '<table class="hovertable">';
					 $list .= '<tr>';
					 $list .= '<th>No</th>';
					 $list .= '<th>Type</th>';
					 $list .= '<th>Name</th>';
					 $list .= '<th>Date</th>';
					 $list .= '</tr>';
					 
					while (!$result->EOF)
					   {
						   $data = $result->fields;
						   
							  $list .= '<tr>';
							  $list .= '<td>'.$no = ($no+1).'</td>';
							  $list .= '<td>'.stripslashes(Document_Icon($data['file_type'],$data['file_name'])).'</td>';
							  $list .= '<td><a href="http://'.$_SERVER['HTTP_HOST'].'/media/docs/'.$data['file_name'].'" target="_blank">'.stripslashes($data['file_name']).'</a></td>';
							  $list .= '<td>'.stripslashes($data['date_filed']).'</td>';
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
}
