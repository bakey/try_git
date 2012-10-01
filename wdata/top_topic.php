<?php
session_start();
header("Content-type: text/html; charset=utf-8"); 
include_once( 'config.php' );
include_once( 'saet.ex.class.php' );

$c = new SaeTClient( WB_AKEY , WB_SKEY , $_SESSION['last_key']['oauth_token'] , $_SESSION['last_key']['oauth_token_secret']  );
$trends  = $c->daily_trends( 1 );
foreach ( $trends as $element )
{
	echo( $element['trends']['name'] );
}

?>