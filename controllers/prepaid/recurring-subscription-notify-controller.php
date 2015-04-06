<?php
require 'models/impl/PrepaidPlanService.php';
require 'models/impl/PrepaidUserService.php';
require 'models/impl/PrepaidOrderService.php';

PrepaidOrderService::SubscriptionNotify();
?>
