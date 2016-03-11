<?php
class VIDEO extends MODULE
{
	function Display()
		{
			$this->Add_Template_File('documents','modules/media/video/html/container.html');
			$parts['channels'] = $this->List_Channels();
			
			if(isset($_GET['view']))
				{
				    if(isset($_GET['show']))
					   {
					    	$parts['video'] .= $this->Show();
					   }
				 	$parts['video'] .= $this->View_Category();
				} else {
					$parts['video'] =  '';
				}
				
			return $this->Populate_Template_Parts('documents', $parts);
		}
	function List_Channels()
		{
			$this->db = ADODB_Connect();
			
			
			$query = $this->db->Prepare('select * from tbl_podcasts_categories order by category ASC');
		    $result = $this->db->Execute($query);
			
			if ( $result->RecordCount() > 0 )
			 {
			  	$list .= '<ol class="list">';
				  while (!$result->EOF)
					  {
						$data = $result->fields;
						$list .= '<li>'.$data['category'].'';
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
	function View_Category()
		{
		 	$this->db = ADODB_Connect();
			
			$list .= '<h2>'.Podcasts_Category_Name($_GET['view']).'</h2>';
			
			$query = $this->db->Prepare('select * from tbl_podcasts where category = ? and publish = 1 order by date_time desc');
		    $result = $this->db->Execute($query,$_GET['view']);
		  
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
						   
							$list .= '<div class="col-6">';
								$list .= '<div class="post-module hover">';
									$list .= '<div class="thumbnail">';
									$list .= '<div class="date">';
									$list .= '<div class="day">'.$day.'</div>';
									$list .= '<div class="month">'.date('M', mktime(0, 0, 0, $month, 10)).'</div>';
									$list .= '</div>';
									$list .= '<a href="index.php?menu=podcasts&view='.$_GET['view'].'&show='.$data['id'].'">';
									preg_match('~embed/(.*?)"~', $data['code'], $output);
									$list .=  '<img src="http://img.youtube.com/vi/'.$output[1].'/sddefault.jpg">';
									$list .= '</a>';
									$list .= '</div>';
									$list .= '<div class="post-content">';
									$list .= '<div class="category">'.$year.'</div>';
									$list .= '<h1 class="title">';
									$list .= '<a href="index.php?menu=podcasts&view='.$_GET['view'].'">';
									$list .= ''.$data['title'].'</a></h1>';
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
					$list .= error('No '.Podcasts_Category_Name($_GET['view']).' loaded yet.');
				}
			return $list;
		}
	function Show()
		{
		 	$this->db = ADODB_Connect();
			$query = $this->db->Prepare('select * from tbl_podcasts where id = ? and publish = 1 limit 1');
		    $result = $this->db->Execute($query,$_GET['show']);
		  
		    if ( $result->RecordCount() > 0 )
				{
				 	$data = $result->fields;
					$list .= $data['code'];
				}else{
					$list .= error('Podcast not found.');
				}
			return $list;
		}
	function Latest_Podcasts()
		{
			$this->Add_Template_File('documents','modules/media/video/html/home_container.html');
			$parts['video'] = $this->List_Lastest_Podcasts();
			return $this->Populate_Template_Parts('documents', $parts);
		}
	function List_Lastest_Podcasts()
		{
			$this->db = ADODB_Connect();
			
			$query = $this->db->Prepare('select * from tbl_podcasts where publish = 1 order by date_time desc limit 3');
		    $result = $this->db->Execute($query);
		  
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
									$list .= '<a href="index.php?menu=podcasts&view='.$data['category'].'&show='.$data['id'].'">';
									preg_match('~embed/(.*?)"~', $data['code'], $output);
									$list .=  '<img src="http://img.youtube.com/vi/'.$output[1].'/sddefault.jpg">';
									$list .= '</a>';
									$list .= '</div>';
									$list .= '<div class="post-content">';
									$list .= '<div class="category">'.$year.'</div>';
									$list .= '<h1 class="title">';
									$list .= '<a href="index.php?menu=podcasts&view='.$data['category'].'">';
									$list .= ''.$data['title'].'</a></h1>';
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
					$list .= error('No Podcasts loaded yet.');
				}
			return $list;
		}
}
?>