<?php

require 'require-admin.php';
require 'models/impl/PrepaidOrderService.php';
require 'models/impl/PrepaidPlanService.php';
require 'models/impl/PrepaidUserService.php';
require 'models/impl/SMSService.php';
require 'models/impl/AgentService.php';

$views['orders'] = 'views/backend/prepaid/orders.php';
$views['order-reload'] = 'views/backend/prepaid/order-reload.php';
$views['order-new'] = 'views/backend/prepaid/order-new.php';
$views['order-print'] = 'views/station/prepaid-order-print.php';
$views['order-summary'] = 'views/backend/prepaid/order-summary.php';

if (isset($_GET['action'])) {
    switch ($_GET['action']) {
        case 'reload':
            PrepaidOrderService::GetOrders();
            require $views['order-reload'];
            break;
        case 'new':
            AgentService::GetAgentNames();
            PrepaidPlanService::GetPrepaidPlanNames();
            PrepaidUserService::GetPrepaidUserNames();
            if ($_GET['agents']->count() == 0) {
                echo "No Agents Found";
            } else if ($_GET['plans']->count() == 0) {
                echo "No Prepaid Plans Found";
            } else {
                require $views['order-new'];
            }
            break;
        case 'insert':
            PrepaidOrderService::DoInsert();
            require $views['order-reload'];
            break;
        case 'update':
            PrepaidOrderService::DoUpdate();
            require $views['order-reload'];
            break;
        case 'print':
            PrepaidOrderService::PrintByOrderId($_GET['id']);
            require $views['order-print'];
            break;
        case 'summary':
            PrepaidOrderService::GetSummary();
            require $views['order-summary'];
            break;
        case 'get-start-date':
            echo PrepaidOrderService::GetStartDate();
            break;
    }
    return;
}

PrepaidOrderService::GetOrders();
require $views['orders'];
?>
