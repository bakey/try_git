<?php
include_once('util.php');
include_once('config.php');
$myaction = $_GET["action"];
if ( $myaction == "crawl")
{
	$appkey = select_available_appkey();
	if ( "" == $appkey )
	{
		die("当前无可用appkey <br>");		
	}
	$total = 0;
	$sleep_int = intval(get_crawle_interval());
	print "抓取间隔 : $sleep_int <br>";
	while ( true )
	{
			print "开始抓取....  使用app key = $appkey"  . "<br>";
			$crawl_content = get_public_timeline( $appkey );
			if ( $crawl_content == "")
			{
				echo "get public timeline failed";
				break;
			}
			else
			{
				set_appkey_status( $appkey , 1 );
				$cjson = json_decode( $crawl_content , true );
				$store_cnt = store_weibo_to_mysql( $cjson , $dup );
				print "抓取存储成功 ,本次总共存储 $store_cnt 条记录<br>";
				$total += $store_cnt;
				if ( $store_cnt <= 0 ) {
					print "没有存储记录，可能是抓取内容有问题";
					var_dump( $crawl_content );
					break;
				}
				if ( $total > MAX_FETCH_NUM )
				{
					print "more than MAX_FETCH_NUM <br>";
					break;
				}
				if ( $dup )
				{
					print "meet duplicate record <br>";
					break;
				}
		}
		sleep($sleep_int);		
	}
	print "<br>此次总共存储 $total 条记录 <br>"; 
	print '<input type="submit" value="回主页面" onclick="javascript:location.href=\'./index.php\'"/><br>';
	print '<input type="submit" value="查询当前数据库的记录数" onclick="javascript:location.href=\'./check.php?action=db_num\'"/>';		
}
else if ( $myaction == "check_interval")
{
			$sleep_int = intval(get_crawle_interval());
			print "当前抓取间隔 : $sleep_int <br>";
	echo '<form name="myform" action="./change_ci.php" method="POST">更改抓取间隔
	<input type="text" name="crawle_interval" /> <br><input type="submit" value="Submit" /></form>';
	?>
	<html>
	<body>
	<input type="submit" name="uploadsubmit" id="btnupload" value="马上开始一次抓取" class="submit"  onclick="javascript:location.href='./crawl.php?action=crawl'"/>
	<input type="submit" value="回主页面" onclick="javascript:location.href='./index.php'"/>
	</body>
	</html>
		<?php
}
?>
