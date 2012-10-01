<?php
define( "PUBLIC_PAGE_URL" , "http://api.weibo.com/2/statuses/public_timeline.json?source=");
define( "RATE_LIMIT_STATUS_URL" , "https://api.weibo.com/2/account/rate_limit_status.json?source=");
define('COOKIE_STRING','');
define('MOCK_STRING' , '{
	"statuses": [
		{
			"created_at": "Tue May 31 17:46:55 +0800 2011",
            "id": 11488058246,
			"geo": null,
			"mid": "5612814510546515491",
            "reposts_count": 8,
            "comments_count": 9
		}
	],
	"previous_cursor": 0,
    "next_cursor": 11488013766,
    "total_number": 81655
	}');
define('DB_TABLE_NAME' , 'weibo_public');
define('DB_APPKEY_TABLE_NAME' , 'app_key');
define('MAX_FETCH_NUM' , 30);

define('SAE_MYSQL_USER' , 'root');
define('SAE_MYSQL_PASS' , 'root');
define('SAE_MYSQL_HOST_M' , '127.0.0.1');
define('SAE_MYSQL_PORT' , '3306');
define('SAE_MYSQL_DB' , 'wb001');
?>