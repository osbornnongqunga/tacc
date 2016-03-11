<?php
class GALLERY extends MODULE
{
	function Display()
		{
			$this->Add_Template_File('documents','modules/media/gallery/html/container.html');
			$parts['content'] = $this->List_Galleries();
			return $this->Populate_Template_Parts('documents', $parts);
		}
	function List_Galleries()
		{
			if(isset($_GET['view']))
				{
				 	$list .= $this->View_Gallary();
				}
			$list .= '<div class="col-3">';	
		 	$list .= '<h2>Galleries</h2>';
			
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
							$list .= ' | <a href="index.php?menu=gallery&view='.$data['id'].'">View</a>';
							$list .= '</li>';
							$result->MoveNext();
						  }
					$list .= '</ol>';
					
				 } else {
					   $list .= error('No photo gallaries created yet.');
				 }
			$list .= '</div>';
			return $list;
		}
	function View_Gallary()
		{
		    $this->db = ADODB_Connect();
			
			$list .= '<h2>'.Gallary_Name($_GET['view']).'</h2>';
			$list .= '<p class="sub-heading">'.Gallary_Description($_GET['view']).'</p>';
			$list .= '<div class="main-line"></div>';
			
		    $query = $this->db->Prepare('select * from tbl_gallary_pics where gallary_id = ? order by date_filed DESC');
		    $result = $this->db->Execute($query,$_GET['view']);
			
			 if ( $result->RecordCount() > 0 )
				 {
				 	$list .= '<div class="projects-container">';
						$list .= '<ul class="projects">';
						
						  while (!$result->EOF)
							  {
								$data = $result->fields;
								
									$list .= '<li class="single-project dark-bg">';
										$list .= '<img src="media/pics/'.$data['file_name'].'">';
										$list .= '<a class="fancybox" rel="group" href="media/pics/'.$data['file_name'].'">';
											$list .= '<div class="project-details-conainer">';
												$list .= '<div class="project-details">';
												$list .= '<h3>'.$data['file_name'].'</h3>';
													$list .= '<p>';
													$list .= '</p>';
												$list .= '</div>';
											$list .= '</div>';
										$list .= '</a>';
									$list .= '</li>';
								$result->MoveNext();
							  }
						$list .= '</ul>';
					$list .= '</div>';
				 }
				 else{
					   $list .= error('No pictures loaded yet on this gallery.');
					 }
			
			return $list;
		}
	function Latest_Pictures()
		{
			$this->Add_Template_File('documents','modules/media/gallery/html/container.html');
			$parts['content'] = $this->List_Latest_Pictures();
			return $this->Populate_Template_Parts('documents', $parts);
		}
	function List_Latest_Pictures()
		{
			 $this->db = ADODB_Connect();
			
			$list .= '<h2>Galleries</h2>';
			$list .= '<p class="sub-heading">Lastest Pictures</p>';
			$list .= '<div class="main-line"></div>';
			
		    $query = $this->db->Prepare('select * from tbl_gallary_pics order by date_filed DESC limit 3');
		    $result = $this->db->Execute($query);
			
			 if ( $result->RecordCount() > 0 )
				 {
				 	$list .= '<div class="projects-container">';
						$list .= '<ul class="projects">';
						
						  while (!$result->EOF)
							  {
								$data = $result->fields;
								
									$list .= '<li class="single-project dark-bg">';
										$list .= '<img src="media/pics/'.$data['file_name'].'">';
										$list .= '<a class="fancybox" rel="group" href="media/pics/'.$data['file_name'].'">';
											$list .= '<div class="project-details-conainer">';
												$list .= '<div class="project-details">';
												$list .= '<h3>'.$data['file_name'].'</h3>';
													$list .= '<p>';
													$list .= '</p>';
												$list .= '</div>';
											$list .= '</div>';
										$list .= '</a>';
									$list .= '</li>';
								$result->MoveNext();
							  }
						$list .= '</ul>';
					$list .= '</div>';
				 }
				 else{
					   $list .= error('No pictures loaded yet.');
					 }
			
			return $list;
		}
}
?>