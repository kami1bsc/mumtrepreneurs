<?php

	define('USER_TYPES', array(
		'admin' => '0',
		'candidate' => '1',
        'recruiter' => '2',
	));

	if(env('DB_USERNAME') == 'root')
	{
		define('IMAGE_URL', 'http://127.0.0.1:8000/');
	}else{
		define('IMAGE_URL', 'https://dev.joinsentinels.com/sentinels/public/');
	}
?>