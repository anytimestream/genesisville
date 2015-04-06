<?php
require 'require-prepaid.php';
require 'models/impl/PrepaidOrderService.php';
require 'models/impl/PrepaidUserService.php';

$views['orders'] = 'views/prepaid/payment-history.php';

$_GET['name'] = PrepaidUserService::GetLoginUserName();

PrepaidOrderService::GetPrepaidOrders();
require $views['orders'];
?>
