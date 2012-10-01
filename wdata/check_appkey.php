<?php
include_once('util.php');
include_once('config.php');
 $key = $_GET['appkey'];
 $content = get_appkey_status( WEIBO_USER , WEIBO_PWD , $key );
 if ( "" == $content )
 {
	echo "get appkey rate limit status failed";
 }
 else
 {
	$jobj = json_decode( $content , true );
	echo "remaining_ip_hits : " . $jobj['remaining_ip_hits'] . "<br>";
	echo "remaining_user_hits : " . $jobj['remaining_user_hits'] . "<br>";
	echo "reset_time : " . $jobj['reset_time'] . "<br>";
	echo "user_limit : " . $jobj['user_limit'] . "<br>";	
	print "ip_limit : " . $jobj['ip_limit'] . "<br>";
	print '<input type="submit" value="回主页面" onclick="javascript:location.href=\'./index.php\'"/>';
 }

?>