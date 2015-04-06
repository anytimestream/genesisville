<?php
ini_set('session.use_only_cookies', true);
date_default_timezone_set('Africa/Lagos');
ini_set('post_max_size', 100000);
ini_set('upload_max_filesize', 100000);

define('DB_HOST','genesis.cfkrs2cbrrb7.us-east-1.rds.amazonaws.com');
define('DB_USER','norman');
define('DB_PASSWORD','adminadmin');
define('DB_HOST','localhost');
//efine('DB_USER','root');
//define('DB_PASSWORD','mysql');
define('DB_USERNAME','');
define("GTPAY_HASH_KEY", "8F8AA7B03CC1264FAE5F9102E75F6641933AAE2FED7BDBE85CF42946E2FB565E1A7D086870F59DAC464CBA75BA88D8BD4FDCE33A15C95DA232B02B9056E809A8");
define("GTPAY_TRANS_STATUS", "https://ibank.gtbank.com/GTPayService/gettransactionstatus.json?");
define("GTPAY_TRANS_REQUEST", "https://ibank.gtbank.com/GTPay/Tranx.aspx");
define('CONTEXT_PATH', 'http://'.$_SERVER['SERVER_NAME']);
