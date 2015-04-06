<?php

require 'require-prepaid.php';
require 'models/impl/PrepaidPlanService.php';
require 'models/impl/PrepaidUserService.php';
require 'models/impl/PrepaidOrderService.php';

if (!isset($_GET['plan'])) {
    $_GET['info'] = PrepaidUserService::GetCurrentUserInfo();
    PrepaidPlanService::GetPrepaidPlanNames();
    $_GET['view'] = 'new-subscription-plans.php';
} else if (!isset($_POST['tenure'])) {
    PrepaidPlanService::GetPrepaidPlanNames();
    $_GET['view'] = 'new-subscription-order.php';
} else {
    PrepaidPlanService::GetPrepaidPlanNames();
    PrepaidOrderService::PlaceNewSubscription();
}

$_GET['name'] = PrepaidUserService::GetLoginUserName();
require 'views/prepaid/new-subscription.php';
?>
