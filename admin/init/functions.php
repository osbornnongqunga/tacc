<?php
function error( $text )
	{
	  return '<div class="error-message">'.$text.'</div>';
	}
function success( $text )
	{
	  return '<div class="success-message">'.$text.'</div>';
	}
function MaxId()
	{
		$db = ADODB_Connect();
		$result = mysql_query( 'select Max(id) as max from tbl_news limit 1' );
		if((!result) || (mysql_num_rows($result) == 0)) return '0';
		   else 
				$data = mysql_fetch_array($result);
		
				return $data['max'];
	}
function Gallary_Name($gallary)
	{
		if($gallary == 0) { return '';}
		
		$db = ADODB_Connect();
		$sql = $db->Prepare('select * from tbl_gallaries where id = ? limit 1');
		$result = $db->Execute($sql,$gallary);
		
		 if ( $result || $result->RecordCount() > 0 )
				{
					$data = $result->fields;
					return $data['name']; 
				}
	}
function Document_Category_Name($category)
	{
		if($category == 0) { return '';}
		
		$db = ADODB_Connect();
		$sql = $db->Prepare('select * from tbl_doc_categories where id = ? limit 1');
		$result = $db->Execute($sql,$category);
		
		 if ( $result || $result->RecordCount() > 0 )
				{
					$data = $result->fields;
					return $data['category']; 
				}
	}
function News_Category_Name($category)
	{
		if($category == 0) { return '';}
		
		$db = ADODB_Connect();
		$sql = $db->Prepare('select * from tbl_news_categories where id = ? limit 1');
		$result = $db->Execute($sql,$category);
		
		 if ( $result || $result->RecordCount() > 0 )
				{
					$data = $result->fields;
					return $data['category']; 
				}
	}
function Podcasts_Category_Name($category)
	{
		if($category == 0) { return '';}
		
		$db = ADODB_Connect();
		$sql = $db->Prepare('select * from tbl_podcasts_categories where id = ? limit 1');
		$result = $db->Execute($sql,$category);
		
		 if ( $result || $result->RecordCount() > 0 )
				{
					$data = $result->fields;
					return $data['category']; 
				}
	}
function News_Category_Select($news_category)
	{
		$db = ADODB_Connect();
		$query = $db->Prepare('select * from tbl_news_categories');
		$result = $db->Execute($query);
		
		if ( $result->RecordCount() > 0 )
				{
					$select = '<select id="select-box" name="news_category">';
		
					$select .= '<option class="optional-select-box-label" value="" selected>Select News Category</option>';
					
					while (!$result->EOF)
						 {
							if ($news_category == $result->fields['id']) $selected = 'selected';
								else $selected = '';
					
							$select .= '<option value="'.$result->fields['id'].'" '.$selected.'>';
							$select .= $result->fields['category'].'</option>';
							
							$result->MoveNext();
						 }
						 
					$select .= '</select>';
				
					return $select;
				}
				else
					{
						return error('Could not retrieve news category list');
					}
	}
function Podcast_Category_Select($podcast_category)
	{
		$db = ADODB_Connect();
		$query = $db->Prepare('select * from tbl_podcasts_categories');
		$result = $db->Execute($query);
		
		if ( $result->RecordCount() > 0 )
				{
					$select = '<select id="select-box" name="podcast_category">';
		
					$select .= '<option class="optional-select-box-label" value="" selected>Select Podcast Category</option>';
					
					while (!$result->EOF)
						 {
							if ($podcast_category == $result->fields['id']) $selected = 'selected';
								else $selected = '';
					
							$select .= '<option value="'.$result->fields['id'].'" '.$selected.'>';
							$select .= $result->fields['category'].'</option>';
							
							$result->MoveNext();
						 }
						 
					$select .= '</select>';
				
					return $select;
				}
				else
					{
						return error('Could not retrieve podcast category list');
					}
	}
function Show_Article_Pic($article_id)
    {
	 	$db = ADODB_Connect();
		$query = $db->Prepare('select * from tbl_news_pics where news_id = ? order by id DESC limit 1');
		$result = $db->Execute($query,$article_id);
		
		 if ( $result->RecordCount() > 0 )
			 {
				$data = $result->fields;
				
				$list .= '<div class="col-3">';
					$list .= '<div class="single-member">';
						$list .= '<img src="../media/pics/'.$data['file_name'].'" width="100%" height="100%">';
							$list .= '<div class="member-info">';
								$list .= '<h5>'.$data['file_name'].'</h5>';
							$list .= '</div>';
					$list .= '</div>';
				$list .= '</div>';
			 }
		return $list;
	}
function Document_Icon($type,$name)
	{
		$file_type = explode('/', $type);
		$file_type = $file_type[0];
					
		if ($file_type=='application')
			{
				$file_type = substr($name, strlen($name) - 3, 3);
			}
			elseif($file_type[0]=='image')
			{
				$file_type = $file_type;
			}
			elseif($file_type[0]=='audio')
			{
				$file_type = $file_type;
			}
			elseif($file_type[0]=='video')
			{
				$file_type = $file_type;
			}		
			switch($file_type)
					{
						//Load audio icon
						case 'audio':
							{
								return '<img src="images/icons/music.png" width="30px" height="30px">';
							}
						break;
						//Load video icon
						case 'video':
							{
								return '<img src="images/icons/video.png" width="30px" height="30px">';
							}
						break;
						//Load Image icon
						case 'image':
							{
								return '<img src="images/icons/image.png" width="30px" height="30px">';
							}
						break;
						//Load XLS icon
						case 'xls':
							{
								return '<img src="images/icons/excel.jpg" width="30px" height="30px">';							
							}
						break;
						case 'csv':
							{
								return '<img src="images/icons/excel.jpg" width="30px" height="30px">';							
							}
						break;
						//Load word 97/2003 icon
						case 'doc':
							{
								return '<img src="images/icons/word.jpg" width="30px" height="30px">';								
							}
						break;
						//Load word 2007/2010 icon
						case 'ocx':
							{
								return '<img src="images/icons/word.jpg" width="30px" height="30px">';								
							}
						break;
						//Load word 2007/2010 icon
						case 'lsx':
							{
								return '<img src="images/icons/excel.jpg" width="30px" height="30px">';								
							}
						break;
						//Load powerpoint 2007/2010 icon
						case 'ptx':
							{
								return '<img src="images/icons/powerpoint.jpg" width="30px" height="30px">';								
							}
						break;
						//Load powerpoint 2007/2010 icon
						case 'ppt':
							{
								return '<img src="images/icons/powerpoint.jpg" width="30px" height="30px">';								
							}
						break;
						//Load PDF icon
						case 'pdf':
							{
								return '<img src="images/icons/pdf.png" width="30px" height="30px">';							
							}
						break;
						//Load DEFAULT icon
						default :
							{
								return '<img src="images/icons/anydoc.jpg" width="30px" height="30px">';							
							}
						break;														
						
					}	
	}
?>
