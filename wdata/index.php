<?php

//session_start();
//header("Content-type: text/html; charset=utf-8"); 
include_once( 'config.php' );
include_once( 'util.php');
	$sleep_int = intval(get_crawle_interval());
			print "当前抓取间隔 : $sleep_int <br>";
	echo '<form name="myform" action="./change_ci.php" method="POST">更改抓取间隔
	<input type="text" name="crawle_interval" /> <br><input type="submit" value="Submit" /></form>';
	print '<input type="submit" name="uploadsubmit" id="btnupload" value="马上开始一次抓取" class="submit"  onclick="javascript:location.href=\'./crawl.php?action=crawl\'"/>';
		/*	print "<table border='1'>";
			print "<tr><th>当前时间 : " . date("F j, Y, g:i a") . "</th><th>" ; 
			print "<tr><th>数据库总记录:" .get_db_record_num() . "</th><th>geo不为null的记录 :" . get_db_valid_geo_num() . "</th></tr>";
			
			$mysql = new db_mysql();
			$mysql->connect( SAE_MYSQL_HOST_M.":".SAE_MYSQL_PORT , SAE_MYSQL_USER , SAE_MYSQL_PASS , SAE_MYSQL_DB );
			$cmd = "select * from app_key";
			$result = $mysql->query( $cmd );
			if ( !$result )
			{
				die("数据库查询app_key失败 : " . mysql_error() );
			}
			$row_cnt = $mysql->num_rows( $result );
			for ( $i = 0 ; $i < $row_cnt ; $i ++ )
			{
					print "<tr>";
					$row_result = $mysql->fetch_assoc( $result );
					$key = $row_result["appkey"];
					$stat = $row_result["status"];
					if ( $stat == "0" )
						$key_stat = "未使用";
					else
						$key_stat = "使用中";
					$jobj = json_decode( get_appkey_status( WEIBO_USER , WEIBO_PWD , $key ) , true );
					$ip_limit = strval( $jobj['ip_limit'] );
					$ip_hit = strval( $jobj['remaining_ip_hits'] );
					$remain_user_hit = strval( $jobj['remaining_user_hits'] );
					$reset_time = strval( $jobj['reset_time'] );
					$reset_time_in_second = strval( $jobj['reset_time_in_seconds'] );
					print "<th>appkey : " . $key . "</th>";
					print "<th>iplimit : " . $ip_limit. "</th>";
					print "<th>ip hit : " . $ip_hit. "</th>";
					print "<th>remain user hit : " . $remain_user_hit. "</th>";
					print "<th>reset time : " . $reset_time. "</th>";
					print "<th>reset time in second : " . $reset_time_in_second. "</th>";
					print "<th>" . $key_stat . "</th>";
					print "</tr>";
			}
			print "</table>";*/
			print '<input type="submit" value="查询信息页" onclick="window.location.href=\'./check.php?action=all_stat\'" />';
			print '<form name="myform" action="./add_app_key.php" method="POST">添加appkey<input type="text" name="appkey" /> <br><input type="submit" value="Submit" /></form>';
			print '<input type="submit" value="最近20条记录" onclick="window.location.href=\'./check.php?action=latest20\'" />';
?>
<html>
	<body>
	</body>
</html>
