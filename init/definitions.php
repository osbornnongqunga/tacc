<?php
// MySQL Database Settings:
//--------------------------------------
define( 'DB_DRIVER', 'mysql' );
define( 'DB_SERVER','localhost');
/*define( 'DB_USERNAME','root');
define( 'DB_PASSWORD','');  
define( 'DB_NAME','tacc');*/
define( 'DB_USERNAME','lumitqxz_tacc');
define( 'DB_PASSWORD','w@t3rfr0nt'); 
define( 'DB_NAME','lumitqxz_tacc');
define( 'DATETIME', date( 'Y-m-d H:i:s' ));

// MySQL Database Connection Function:
//---------------------------------------
function ADODB_Connect()
{
	$db = '';
	$db = &ADONewConnection(DB_DRIVER);
	$db->debug = false;
	$db->PConnect(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);
	$db->SetFetchMode(ADODB_FETCH_ASSOC);
	return $db;
}
?>
