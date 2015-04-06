<?php
require 'require-prepaid.php';
require 'models/impl/PrepaidUserService.php';

header('location: '.CONTEXT_PATH.'/prepaid/new-subscription');

?>
