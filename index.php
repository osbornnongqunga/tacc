<?php
require_once('init/adodb5/adodb.inc.php');
require_once('init/definitions.php');
require_once('init/module.php');
require_once('init/functions.php');
require_once('modules/menu/menu.php');
require_once('modules/home/home.php');
require_once('modules/about/about.php');
require_once('modules/apostles/apostles.php');
require_once('modules/branch/branch.php');
require_once('modules/contact/contact.php');
require_once('modules/media/documents/documents.php');
require_once('modules/media/gallery/gallery.php');
require_once('modules/media/video/video.php');
require_once('modules/news/articles/articles.php');
require_once('modules/news/events/events.php');

$page = new MODULE;
$menu = new MENU;
$home = new HOME;
$about = new ABOUT;
$apostle = new APOSTLE;
$branch = new BRANCH;
$contact = new CONTACT;
$documents = new DOCUMENTS;
$gallery = new GALLERY;
$article = new ARTICLE;
$event = new EVENT;
$video = new VIDEO;

$page -> Add_Template_File('template','templates/index.html');
$parts['menu'] = $menu->Display();
if ( !isset( $_GET['menu'] ) ) $_GET['menu'] = 'home';
switch ( $_GET['menu'])
	{
	    case 'home':
			{
			   $parts['content'] = $home->Display();
			   $parts['news'] = $article->Latest_News();
			   $parts['events'] = $event->Latest_Events();
			   $parts['gallery'] = $gallery->Latest_Pictures();
			   $parts['podcasts'] = $video->Latest_Podcasts();
			   $parts['documents'] = $documents->Latest_Documents();
			}
		break;
		case 'about':
			{
			   $parts['content'] = $about->Display();
			   $parts['news'] = '';
			   $parts['events'] = '';
			   $parts['gallery'] = '';
			   $parts['podcasts'] = '';
			   $parts['documents'] = '';
			}
		break;
		case 'apostle':
			{
			   $parts['content'] = $apostle->Display();
			   $parts['news'] = '';
			   $parts['events'] = '';
			   $parts['gallery'] = '';
			   $parts['podcasts'] = '';
			   $parts['documents'] = '';
			}
		break;
		case 'documents':
			{
			   $parts['content'] = $documents->Display();
			   $parts['news'] = '';
			   $parts['events'] = '';
			   $parts['gallery'] = '';
			   $parts['podcasts'] = '';
			   $parts['documents'] = '';
			}
		break;
		case 'gallery':
			{
			   $parts['content'] = $gallery->Display();
			   $parts['news'] = '';
			   $parts['events'] = '';
			   $parts['gallery'] = '';
			   $parts['podcasts'] = '';
			   $parts['documents'] = '';
			}
		break;
		case 'podcasts':
			{
			   $parts['content'] = $video->Display();
			   $parts['news'] = '';
			   $parts['events'] = '';
			   $parts['gallery'] = '';
			   $parts['podcasts'] = '';
			   $parts['documents'] = '';
			}
		break;
		case 'article':
			{
			   $parts['content'] = $article->Display();
			   $parts['news'] = '';
			   $parts['events'] = '';
			   $parts['gallery'] = '';
			   $parts['podcasts'] = '';
			   $parts['documents'] = '';
			}
		break;
		case 'event':
			{
			   $parts['content'] = $event->Display();
			   $parts['news'] = '';
			   $parts['events'] = '';
			   $parts['gallery'] = '';
			   $parts['podcasts'] = '';
			   $parts['documents'] = '';
			}
		break;
		case 'branch':
			{
			   $parts['content'] = $branch->Display();
			   $parts['news'] = '';
			   $parts['events'] = '';
			   $parts['gallery'] = '';
			   $parts['podcasts'] = '';
			   $parts['documents'] = '';
			}
		break;
		case 'contact':
			{
			   $parts['content'] = $contact->Display();
			   $parts['news'] = '';
			   $parts['events'] = '';
			   $parts['gallery'] = '';
			   $parts['podcasts'] = '';
			   $parts['documents'] = '';
			}
		break;
    }
echo $page -> Populate_Template_Parts('template',$parts); 
?>
