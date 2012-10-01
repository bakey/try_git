<?php
include_once('config.php');
include_once('mysqlMgr.php');
 $key = $_POST["appkey"];
 	$mysql = new db_mysql();
		$mysql->connect( SAE_MYSQL_HOST_M.":".SAE_MYSQL_PORT , SAE_MYSQL_USER , SAE_MYSQL_PASS , SAE_MYSQL_DB );
		$cmd = 'SELECT * FROM `app_key` where appkey="' . $key . '"';
		$result = $mysql->query( $cmd );
		$row_cnt = $mysql->num_rows( $result );
		if ( $row_cnt > 0 )
		{
			echo "你添加的appkey已经存在" . '<br><input type="submit" value="返回查询页面" onclick="' . "window.location.href='./check.html'\"" . 'size="10"/>';
		}
		else
		{
			$insert_cmd = "insert into " . DB_APPKEY_TABLE_NAME . "(appkey,status) values('$key','0')";
			$mysql->query( $insert_cmd );
			echo "添加成功" . '<br><input type="submit" value="返回查询页面" onclick="' . "window.location.href='./check.php?action=appkey'\"" . 'size="10"/>';			
		}
?>