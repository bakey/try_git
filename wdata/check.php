<html>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<body>
	<?php
	include_once('util.php');
	include_once('config.php');
	//header("Content-type: text/html; charset=utf-8"); 
	$action_name = $_GET["action"];
	if ( $action_name == "appkey" )
	{
		echo "<table border='1'><tr><th>app_key</th><th>status</th><th>remaining_user_hits</th></tr>";
		echo get_app_key_as_table();
		echo "</table>";
		echo '<form name="myform" action="./add_app_key.php" method="POST">添加appkey<input type="text" name="appkey" /> <br><input type="submit" value="Submit" /></form>';
		print '<input type="submit" value="回主页面" onclick="javascript:location.href=\'./index.php\'"/>';
	}
	else if ( $action_name == "systime" )
	{
		echo "当前系统时间为 : " . date("F j, Y, g:i a") . "<br>";  
		print '<input type="submit" value="回主页面" onclick="javascript:location.href=\'./index.php\'"/>';
	}
	else if ( $action_name == "db_num" )
	{
		echo "当前主库中的记录数为 : " . get_db_record_num() . "<br>";
		print '<input type="submit" value="回主页面" onclick="javascript:location.href=\'./index.php\'"/>';
		print '<input type="submit" value="最近20条记录" onclick="window.location.href=\'./check.php?action=latest20\'" />';
	}
	else if ( $action_name == "db_geo_num" )
	{
		echo "数据库中geo字段不为NULL的记录数为 ：" .get_db_valid_geo_num() , "<br>";
		print '<input type="submit" value="回主页面" onclick="javascript:location.href=\'./index.php\'"/>';
	}
	else if ( $action_name == "latest20" )
	{
		echo "<table border='1'><tr><th>insert_time</th><th>geo</th><th>glat</th><th>glon</th><th>urpovince</th><th>ucity</th><th>ulocation</th><th>mid</th><th>uid</th><th>cTIMEALL</th></tr>" . get_latest20_record() . "</table>";
		print '<input type="submit" value="回主页面" onclick="javascript:location.href=\'./index.php\'"/>';
	}
	else if ( $action_name == "all_stat")
	{
		  $mysql = new db_mysql();
			$mysql->connect( SAE_MYSQL_HOST_M.":".SAE_MYSQL_PORT , SAE_MYSQL_USER , SAE_MYSQL_PASS , SAE_MYSQL_DB );
			$cmd = "select * from app_key";
			$result = $mysql->query( $cmd );
			$row_cnt = $mysql->num_rows( $result );
			print "<table border='1'>";
			print "<tr><th>当前时间 : " . date("F j, Y, g:i a") . "</th><th>" ; 
			print "<tr><th>数据库总记录:" .get_db_record_num() . "</th><th>geo不为null的记录 :" . get_db_valid_geo_num() . "</th></tr>";
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
			print "</table>";
			print '<form name="myform" action="./add_app_key.php" method="POST">添加appkey<input type="text" name="appkey" /> <br><input type="submit" value="Submit" /></form>';
			print '<input type="submit" value="回主页面" onclick="javascript:location.href=\'./index.php\'"/>';
			print '<input type="submit" value="最近20条记录" onclick="window.location.href=\'./check.php?action=latest20\'" />';
	}
	?>

	</body>
</html>