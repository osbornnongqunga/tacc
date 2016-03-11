<?php
session_start();
require_once('init/adodb5/adodb.inc.php');
require_once('init/definitions.php');
require_once('init/module.php');
require_once('init/functions.php');
require_once('modules/menu/menu.php');
require_once('modules/login/login.php');
require_once('modules/gallary/gallary.php');
require_once('modules/documents/documents.php');
require_once('modules/news/news.php');
require_once('modules/podcasts/podcasts.php');

$page = new MODULE;
$menu = new MENU;
$login = new LOGIN;
$gallary = new GALLARY;
$documents = new DOCUMENTS;
$news = new NEWS;
$podcasts = new PODCASTS;

$page -> Add_Template_File('template','templates/index.html');

if (isset($_GET['logout']))
	{
		unset($_SESSION['USER']);
	}			
if (!isset($_SESSION['USER']))
	{
		$parts['menu'] = '';
		$parts['content'] = $login->Run_Login();
	}
	else
		{
			$parts['menu'] = $menu->Display();
			
			if ( !isset( $_GET['menu'] ) ) $_GET['menu'] = 'home';
			
			switch ( $_GET['menu'])
				  {
						case 'home':
							{
							   $parts['content'] = $gallary->Display();
							}
						break;
						case 'gallary':
							{
							   $parts['content'] = $gallary->Display();
							}
						break;
						case 'documents':
							{
							   $parts['content'] = $documents->Display();
							}
						break;
						case 'news':
							{
							   $parts['content'] = $news->Display();
							}
						break;
						case 'podcasts':
							{
								$parts['content'] = $podcasts->Display();
							}
						break;
						
				  }
		}
echo $page -> Populate_Template_Parts('template',$parts); 
?>