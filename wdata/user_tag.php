<?php
session_start();
header("Content-type: text/html; charset=utf-8"); 
include_once( 'config.php' );
include_once( 'saet.ex.class.php' );

$c = new SaeTClient( WB_AKEY , WB_SKEY , $_SESSION['last_key']['oauth_token'] , $_SESSION['last_key']['oauth_token_secret']  );
$tags  = $c->list_dm( 1 , 10 );
foreach ($tags as $data){
	$create_time = $data['created_at'];
	echo $create_time;
}

?>