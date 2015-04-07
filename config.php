<?php
ini_set('session.use_only_cookies', true);
date_default_timezone_set('Africa/Lagos');
ini_set('post_max_size', 100000);
ini_set('upload_max_filesize', 100000);


define('DB_HOST','localhost');
efine('DB_USER','root');
define('DB_PASSWORD','mysql');
define('DB_USERNAME','');
define('CONTEXT_PATH', 'http://'.$_SERVER['SERVER_NAME']);
