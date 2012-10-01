<?php
include_once('config.php');
include_once('mysqlMgr.php');
$ci = intval($_POST['crawle_interval']);
if ( $ci <= 0 )
{
	die("输入的数字有误");
}
  $mysql = new db_mysql();
	$mysql->connect( SAE_MYSQL_HOST_M.":".SAE_MYSQL_PORT , SAE_MYSQL_USER , SAE_MYSQL_PASS , SAE_MYSQL_DB );
	$cmd = "update crawle_interval set crawel_inter=$ci where id=4";
	//$cmd = "insert into " . "crawle_interval" . "(crawel_inter) values($ci)";
	if ( $mysql->query( $cmd ) ) {
		print "更新抓取间隔成功<br>";
	}else {
		$mysql->halt();
	}
	$mysql->close();
	print '<input type="submit" value="回主页面" onclick="javascript:location.href=\'./index.php\'"/>';
?>