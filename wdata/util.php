<?php
include_once('mysqlMgr.php');
include_once('config.php');
function get_public_timeline( $app_key , $page = 1)
{
	$page_url = PUBLIC_PAGE_URL . $app_key ;
	return get_http_content( $page_url );
	//return MOCK_STRING;
}
function get_appkey_status( $user , $pwd , $app_key )
{
	$page_url = RATE_LIMIT_STATUS_URL . $app_key;
	return get_http_content( $page_url , $user , $pwd );
}
function parse_weibo_result( $json_data )
{
	$jobj = json_decode( $json_data , true );
	return $jobj;	
}
function select_available_appkey()
{
	$mysql = new db_mysql();
	$mysql->connect( SAE_MYSQL_HOST_M.":".SAE_MYSQL_PORT , SAE_MYSQL_USER , SAE_MYSQL_PASS , SAE_MYSQL_DB );
	$cmd = "select appkey,status from app_key ";
	$result = $mysql->query( $cmd );
	$row_cnt = $mysql->num_rows( $result );
	if ( $row_cnt == 0 )
	{
		$mysql->close();
		return "";
	}
	$index = rand() % $row_cnt;
	for ( $i = 0 ; $i < $row_cnt ; $i ++ )
	{
		$row_result = $mysql->fetch_assoc( $result );	
		if ( $row_result["status"] == "1" )
		{
			$mysql->close();
			return $row_result["appkey"];
		}
		if ( $i == $index )
		{
			$the_key = $row_result["appkey"];
		}
	}
	$mysql->close();
	return $the_key;	
}
function get_crawle_interval()
{
		$mysql = new db_mysql();
		$mysql->connect( SAE_MYSQL_HOST_M.":".SAE_MYSQL_PORT , SAE_MYSQL_USER , SAE_MYSQL_PASS , SAE_MYSQL_DB );
		$cmd = "select crawel_inter from crawle_interval where id=4";
	
		$row_result = $mysql->fetch_assoc( $mysql->query( $cmd ) );	
		$ret =  $row_result['crawel_inter'];
		$mysql->close();
		return $ret ;		
}
function set_appkey_status( $appkey , $status )
{
	$mysql = new db_mysql();
	$mysql->connect( SAE_MYSQL_HOST_M.":".SAE_MYSQL_PORT , SAE_MYSQL_USER , SAE_MYSQL_PASS , SAE_MYSQL_DB );
	$cmd = "update app_key set status=$status where appkey='$appkey'";
	$mysql->query( $cmd );
	$mysql->close();
}
function get_http_content( $url , $user = "" , $pwd = "" )
{
	$ch = curl_init( $url );
	curl_setopt( $ch , CURLOPT_RETURNTRANSFER , true );
	if ( $user != "" )
	{
		//curl_setopt( $ch , CURLOPT_USERPWD , $user.":".$pwd);
	}
	if ( COOKIE_STRING != "" ){
		curl_setopt($ch, CURLOPT_COOKIE , COOKIE_STRING );
	}
	$html_content = curl_exec( $ch );
	
	curl_close( $ch );	
	return $html_content;	
}
function store_user_profile( $user_profile , $mysql )
{
	$uid = $user_profile['id'];
	$uprovince = $user_profile['province'];
	$ucity = $user_profile['city'];
	$ulocation = $user_profile['location'];
	$ugender = $user_profile['gender'];
	
	$followers_count = $user_profile['followers_count'];
	$friends_count = $user_profile['friends_count'];
	$statuses_count = $user_profile['statuses_count'];
	$favourites_count = $user_profile['favourites_count'];
	
	$created_at = $user_profile['created_at'];
	$geo_enable = strval($user_profile['geo_enable']);
	$verified = strval($user_profile['verified']);
	$online_status = $user_profile['online_status'];
	$bi_followers_count = $user_profile['bi_followers_count'];
	print "store uid = " . $uid . ",create at = " . $created_at . ",geo_enable = " . $geo_enable . "<br>";
	//$mysql = new db_mysql();
	//$mysql->connect( SAE_MYSQL_HOST_M.":".SAE_MYSQL_PORT , SAE_MYSQL_USER , SAE_MYSQL_PASS , SAE_MYSQL_DB );
	$cmd = "insert into " . DB_TABLE_NAME . "(uid,uprovince,ucity,ulocation,ugender,followers_count,friends_count,statuses_count,favourites_count,created_at,geo_enable,verified,online_status,bi_followers_count) values('$uid','$uprovince','$ucity','$ulocation','$ugender',$followers_count,$friends_count,$statuses_count,$favourites_count,'$created_at','$geo_enable','$verified',$online_status,$bi_followers_count)";
	print "store cmd = " . $cmd . "<br>";
	$mysql->query( $cmd );	
/*	$mysql->close();	*/
}
function store_weibo_to_mysql( $jobj , &$dup )
{
//Alter table weibo_public add in_reply_to_status_id varchar(256) 
//alter table weibo_public add comments_count int(10) unsigned default 0
	$mysql = new db_mysql();
	$mysql->connect( SAE_MYSQL_HOST_M.":".SAE_MYSQL_PORT , SAE_MYSQL_USER , SAE_MYSQL_PASS , SAE_MYSQL_DB );
	$profile_cnt = count( $jobj["statuses"] );
	$dup = false;
	for( $i = 0 ; $i < $profile_cnt ; $i ++ )
	{
		$id = $jobj['statuses'][$i]['id'];
		$time_stamp = strtotime($jobj['statuses'][$i]['created_at']);
		$cTIMEALL = strval(date("Y" , $time_stamp)).strval(date("m",$time_stamp)) . strval(date("d",$time_stamp)) . strval(date("h",$time_stamp)) . strval(date("i",$time_stamp)) . strval(date("s",$time_stamp));
		$geo = json_encode( $jobj['statuses'][$i]['geo'] );
		$glat = strval($jobj['statuses'][$i]['geo']['coordinates'][0]) ;
		$glon = strval( $jobj['statuses'][$i]['geo']['coordinates'][1]);
		//print "inserting cid = " . $id . "<br>";
		$source = $jobj['statuses'][$i]['source'];
		$in_reply_to_status_id = $jobj['statuses'][$i]['in_reply_to_status_id'];
		$in_reply_to_user_id = $jobj['statuses'][$i]['in_reply_to_user_id'];
		$mid = $jobj['statuses'][$i]['mid'];
		$reposts_count = $jobj['statuses'][$i]['reposts_count'];
		$comments_count = $jobj['statuses'][$i]['comments_count'];
		
		$uid = $jobj['statuses'][$i]['user']['id'];
		$uprovince = $jobj['statuses'][$i]['user']['province'];
		$ucity = $jobj['statuses'][$i]['user']['city'];
		$ulocation = $jobj['statuses'][$i]['user']['location'];
		$ugender = $jobj['statuses'][$i]['user']['gender'];
	
		$followers_count = $jobj['statuses'][$i]['user']['followers_count'];
		$friends_count = $jobj['statuses'][$i]['user']['friends_count'];
		$statuses_count = $jobj['statuses'][$i]['user']['statuses_count'];
		$favourites_count = $jobj['statuses'][$i]['user']['favourites_count'];
	
		$created_at = $jobj['statuses'][$i]['user']['created_at'];
		if ( $jobj['statuses'][$i]['user']['geo_enabled'] )
			$geo_enable = "true";
		else
			$geo_enable = "false";
		//$geo_enable = strval($jobj['statuses'][$i]['user']['geo_enabled']);
		if ( $jobj['statuses'][$i]['user']['verified'] )
			$verified = "true";
		else
			$verified = "false";
		//$verified = strval($jobj['statuses'][$i]['user']['verified']);
		$online_status = $jobj['statuses'][$i]['user']['online_status'];
		$bi_followers_count = $jobj['statuses'][$i]['user']['bi_followers_count'];
		
		//print "get verified = " . $verified . ", geo enable = " . $geo_enable . "<br>";
	
		$cmd = "insert into " . DB_TABLE_NAME . "(cid,cTIMEALL,geo,csource,in_reply_to_status_id,in_reply_to_user_id,mid,reposts_count,comments_count,uid,uprovince,ucity,ulocation,ugender,followers_count,friends_count,statuses_count,favourites_count,created_at,geo_enabled,verified,online_status,bi_followers_count,glat,glon) values('$id','$cTIMEALL','$geo','$source','$in_reply_to_status_id','$in_reply_to_user_id','$mid','$reposts_count','$comments_count' , '$uid','$uprovince','$ucity','$ulocation','$ugender',$followers_count,$friends_count,$statuses_count,$favourites_count,'$created_at','$geo_enable','$verified',$online_status,$bi_followers_count,'$glat','$glon')";
		$check_cmd = "select mid from " . DB_TABLE_NAME . " where mid='$mid'";
		$ret = $mysql->query( $check_cmd );
		if ( !$ret )
		{
			die("查询数据库失败 :" . mysql_error() . "cmd = $check_cmd");
			$mysql->close();
		}
		if ( $mysql->num_rows( $ret ) > 0 )
		{
			$dup = true;
		}
		$mysql->query( $cmd );		
	}
	$mysql->close();
	return $profile_cnt;
}
function get_app_key_as_table()
{
	$mysql = new db_mysql();
	$mysql->connect( SAE_MYSQL_HOST_M.":".SAE_MYSQL_PORT , SAE_MYSQL_USER , SAE_MYSQL_PASS , SAE_MYSQL_DB );
	$cmd = "select * from app_key";
	$result = $mysql->query( $cmd );
	$row_cnt = $mysql->num_rows( $result );
	$result_str = "";
	for ( $i = 0 ; $i < $row_cnt ; $i ++ )
	{
		$result_str .= "<tr>";
		$result_str .= "<th>";
		$row_result = $mysql->fetch_assoc( $result );
		$the_key = $row_result["appkey"];
		$result_str .= "<a href='./check_appkey.php?appkey=" . $the_key . "'>" . $the_key . "</a>";
		$result_str .= "</th><th>";
		if ( $row_result["status"] == 0 )
			$result_str .= "未使用";
		else
			$result_str .= "使用中";

 	  $jobj = json_decode( get_appkey_status( WEIBO_USER , WEIBO_PWD , $the_key ) , true );
		$result_str .= "</th>";
		$result_str .= "<th>";
		$result_str .= strval($jobj['remaining_user_hits']);
		$result_str .= "</th>";
		$result_str .= "</tr>";
	} 
	$mysql->close();
	return $result_str;
}
function get_db_record_num()
{
	$mysql = new db_mysql();
	$mysql->connect( SAE_MYSQL_HOST_M.":".SAE_MYSQL_PORT , SAE_MYSQL_USER , SAE_MYSQL_PASS , SAE_MYSQL_DB );
	$cmd = "select count(*) from weibo_public";	
	$result = $mysql->query( $cmd );
	if ( !$result )
	{
		die("查询weibo_public 数量失败 " . mysql_error() );
	}
	$res = $mysql->fetch_row( $result );
	$mysql->close();
	return $res[0];
}
function get_db_valid_geo_num()
{
	$mysql = new db_mysql();
	$mysql->connect( SAE_MYSQL_HOST_M.":".SAE_MYSQL_PORT , SAE_MYSQL_USER , SAE_MYSQL_PASS , SAE_MYSQL_DB );
	$cmd = 'SELECT COUNT( * ) FROM  `weibo_public` WHERE geo !=  "null" LIMIT 50000';
	$result = $mysql->query( $cmd );
	if ( !$result )
	{
		die("获取合法的geo数量失败 : " . mysql_error() );
	}
	$res = $mysql->fetch_row( $result );
	$mysql->close();
	return $res[0];
}
function get_latest20_record()
{
		$mysql = new db_mysql();
		$mysql->connect( SAE_MYSQL_HOST_M.":".SAE_MYSQL_PORT , SAE_MYSQL_USER , SAE_MYSQL_PASS , SAE_MYSQL_DB );
		//$cmd = 'SELECT geo,csource,mid,reposts_count,comments_count,uid,ulocation,followers_count,friends_count,insert_time FROM `weibo_public` order by insert_time desc limit 20';
		$cmd = 'select insert_time,geo,glat,glon,uprovince,ucity,ulocation,mid,uid,cTIMEALL FROM `weibo_public` limit 20';
		$result = $mysql->query( $cmd );
		if ( !$result )
		{
			die("执行数据库查询失败 : " . mysql_error() );
		}
		$row_cnt = $mysql->num_rows( $result );
		$result_str = "";
		for ( $i = 0 ; $i < $row_cnt ; $i ++ )
		{
			$result_str .= "<tr>";		
			$row_result = $mysql->fetch_row( $result );
			for ( $j = 0 ; $j < count($row_result) ; $j ++ )
			{
					$result_str .= "<th>";
					$result_str .= $row_result[ $j ];
					$result_str .= "</th>";
			}
			$result_str .= "</tr>";				
		}
		$mysql->close();
		return $result_str;		
}
//如果当前除了原appkey外无其他可用的appkey，那么当前appkey会被停用
function check_and_mod_appkey_status()
{
	$mysql = new db_mysql();
	$mysql->connect( SAE_MYSQL_HOST_M.":".SAE_MYSQL_PORT , SAE_MYSQL_USER , SAE_MYSQL_PASS , SAE_MYSQL_DB );
	$cmd = "select appkey,status from app_key ";
	$result = $mysql->query( $cmd );
	$row_cnt = $mysql->num_rows( $result );
	$update_status = false;
	$next_use_key = "";
	
	for ( $i = 0 ; $i < $row_cnt ; $i ++ )
	{
		$row_result = $mysql->fetch_assoc( $result );
//找到当前使用的appkey
		if ( $row_result["status"] == "1" )
		{
			$key = $row_result['appkey'];
			 $jobj = json_decode( $content , get_appkey_status(WEIBO_USER , WEIBO_PWD , $key) );
			 if ( $jobj['remaining_user_hits'] < 10 )
			 {				
				$up_cmd = "update app_key set status=0 where appkey='$key'";
				$mysql->query( $up_cmd );
				$update_status = true;
			 }
		}
		else
		{
			$next_use_key = $row_result['appkey'];
		}
	}
	if ( $update_status )
	{
		if ( $next_use_key != "" )
		{
			$up_cmd = "update app_key set status=1 where appkey='$next_use_key'";
			$mysql->query( $up_cmd );
		}
	}
	$mysql->close();
}
?>