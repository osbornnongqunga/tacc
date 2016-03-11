<?php
class EVENT extends MODULE
{
	function Display()
		{
			$this->Add_Template_File('documents','modules/news/events/html/container.html');
			$parts['content'] = $this->List_Events();
			return $this->Populate_Template_Parts('documents', $parts);
		}
	function List_Events()
		{
		    if(isset($_GET['view']))
				{
				 	return $this->View_Event();
				}
				
			$list .= '<h2>Events</h2>';
			$list .= '<p class="sub-heading">Events List</p>';
			$list .= '<div class="main-line"></div>';
			
			$this->db = ADODB_Connect();
			$query = $this->db->Prepare('select * from tbl_news where category = ? and publish = 1 order by date_time desc');
		    $result = $this->db->Execute($query,2);
		  
		    if ( $result->RecordCount() > 0 )
				{
					while (!$result->EOF)
					   {
						   $data = $result->fields;
						   $split = explode( ' ',$data['date_time'] );
						   $date = explode( '-',$split[0]);
						   $year = $date[0];
						   $month = $date[1];
						   $day = $date[2];
						   
							$list .= '<div class="col-4">';
								$list .= '<div class="post-module hover">';
									$list .= '<div class="thumbnail">';
										$list .= '<div class="date">';
										$list .= '<div class="day">'.$day.'</div>';
										$list .= '<div class="month">'.date('M', mktime(0, 0, 0, $month, 10)).'</div>';
										$list .= '</div>';
										$list .= '<a href="index.php?menu=event&view='.$data['id'].'">';
									    $list .= '<img src="media/pics/'.Article_Pic($data['id']).'">';
									    $list .= '</a>';
									$list .= '</div>';
									$list .= '<div class="post-content">';
										$list .= '<div class="category">'.$year.'</div>';
										$list .= '<h1 class="title">';
										$list .= '<a href="index.php?menu=event&view='.$data['id'].'">';
										$list .= ''.$data['title'].'</a></h1>';
										
										$list .= '<h1 class="sub_title">';
										$list .= 'Starts: '.$data['start'].'</h1>';
										$list .= '<h1 class="sub_title">';
										$list .= 'Ends: '.$data['end'].'</h1>';
										
										$list .= '<p class="description">'.substr($data['description'],0,100).'</p>';
										$list .= '<div class="post-meta">';
										$list .= '<span class="timestamp">';
										$list .= '<i class="fa fa-clock-o"></i> '.timeAgo($data['date_time']).'';
										$list .= '</span>';
										$list .= '</div>';
									$list .= '</div>';
								$list .= '</div>';
							$list .= '</div>';
						   $result->MoveNext();	
					   }
				}
				else{
					$list .= error('No events loaded yet.');
				}
			
			return $list;
		}
	function View_Event()
		{
			$list .= '<h2>Event</h2>';
			
			$this->db = ADODB_Connect();
			$query = $this->db->Prepare('select * from tbl_news where id = ? and publish = 1 limit 1');
		    $result = $this->db->Execute($query,$_GET['view']);
		  
		    if ( $result->RecordCount() > 0 )
				{
					$data = $result->fields;
					$split = explode( ' ',$data['date_time'] );
				    $date = explode( '-',$split[0]);
				    $year = $date[0];
				    $month = $date[1];
				    $day = $date[2];
					
					$list .= '<p class="sub-heading">'.$data['title'].'</p>';
					$list .= '<div class="main-line"></div>';
					$list .= '<div class="col-4">';
					$list .= '<div class="post-module hover">';
						$list .= '<div class="thumbnail">';
							$list .= '<div class="date">';
							$list .= '<div class="day">'.$day.'</div>';
							$list .= '<div class="month">'.date('M', mktime(0, 0, 0, $month, 10)).'</div>';
							$list .= '</div>';
							$list .= '<img src="media/pics/'.Article_Pic($data['id']).'">';
						$list .= '</div>';
						$list .= '<div class="post-content">';
							$list .= '<div class="category">'.$year.'</div>';
							
							$list .= '<h1 class="sub_title">';
							$list .= 'Starts: '.$data['start'].'</h1>';
							$list .= '<h1 class="sub_title">';
							$list .= 'Ends: '.$data['end'].'</h1>';
										
							$list .= '<span class="timestamp">';
							$list .= '<i class="fa fa-clock-o"></i> '.timeAgo($data['date_time']).'';
							$list .= '</span>';
						$list .= '</div>';
					$list .= '</div>';	
					$list .= '</div>';
					$list .= '<div class="col-8">';
					$list .= nl2br($data['description']);
					$list .= '</div>';
				}
				else{
					$list .= error('Event not found.');
				}
			return $list;
		}
	function Latest_Events()
		{
			$this->Add_Template_File('documents','modules/news/events/html/home_container.html');
			$parts['content'] = $this->List_Latest_Events();
			return $this->Populate_Template_Parts('documents', $parts);
		}
	function List_Latest_Events()
		{
		 	$list .= '<h2>Events</h2>';
			$list .= '<p class="sub-heading">Latest Events</p>';
			$list .= '<div class="main-line"></div>';
			
			$this->db = ADODB_Connect();
			$query = $this->db->Prepare('select * from tbl_news where category = ? and publish = 1 order by date_time desc limit 3');
		    $result = $this->db->Execute($query,2);
		  
		    if ( $result->RecordCount() > 0 )
				{
					while (!$result->EOF)
					   {
						   $data = $result->fields;
						   $split = explode( ' ',$data['date_time'] );
						   $date = explode( '-',$split[0]);
						   $year = $date[0];
						   $month = $date[1];
						   $day = $date[2];
						   
							$list .= '<div class="col-4">';
								$list .= '<div class="post-module hover">';
									$list .= '<div class="thumbnail">';
										$list .= '<div class="date">';
										$list .= '<div class="day">'.$day.'</div>';
										$list .= '<div class="month">'.date('M', mktime(0, 0, 0, $month, 10)).'</div>';
										$list .= '</div>';
										$list .= '<a href="index.php?menu=event&view='.$data['id'].'">';
									    $list .= '<img src="media/pics/'.Article_Pic($data['id']).'">';
									    $list .= '</a>';
									$list .= '</div>';
									$list .= '<div class="post-content">';
										$list .= '<div class="category">'.$year.'</div>';
										$list .= '<h1 class="title">';
										$list .= '<a href="index.php?menu=event&view='.$data['id'].'">';
										$list .= ''.$data['title'].'</a></h1>';
										
										$list .= '<h1 class="sub_title">';
										$list .= 'Starts: '.$data['start'].'</h1>';
										$list .= '<h1 class="sub_title">';
										$list .= 'Ends: '.$data['end'].'</h1>';
										
										$list .= '<p class="description">'.substr($data['description'],0,100).'</p>';
										$list .= '<div class="post-meta">';
										$list .= '<span class="timestamp">';
										$list .= '<i class="fa fa-clock-o"></i> '.timeAgo($data['date_time']).'';
										$list .= '</span>';
										$list .= '</div>';
									$list .= '</div>';
								$list .= '</div>';
							$list .= '</div>';
						   $result->MoveNext();	
					   }
				}
				else{
					$list .= error('No events loaded yet.');
				}
			
			return $list;
		}
}
?>
