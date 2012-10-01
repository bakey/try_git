<?php

session_start();
header("Content-type: text/html; charset=utf-8"); 
include_once( 'config.php' );
include_once( 'saet.ex.class.php' );


$c = new SaeTClient( WB_AKEY , WB_SKEY , $_SESSION['last_key']['oauth_token'] , $_SESSION['last_key']['oauth_token_secret']  );
$ms  = $c->friends_timeline( 1 , 50 ); // done
?>
<html>
<h2>发送新微博</h2>
<form action="weibolist.php" >
<input type="text" name="text" style="width:300px" />
&nbsp;<input type="submit" />
</form>
</html>
<?php


if( isset($_REQUEST['text']) )
{
	$c->update( $_REQUEST['text'] );
	echo "<p>发送完成</p>";
}

foreach ($ms as $data){
	$user_name = $data['user']['name'];
	$text = $data['text'];
	echo $user_name."=".$text.";";
	echo "<br>";
}
?>




