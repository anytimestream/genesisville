<?php

require 'require-admin.php';
require 'models/impl/PCOrderService.php';
require 'models/impl/PCPlanService.php';
require 'models/impl/PCUserService.php';
require 'models/impl/SMSService.php';

$views['orders'] = 'views/backend/pc/orders.php';
$views['order-reload'] = 'views/backend/pc/order-reload.php';
$views['order-new'] = 'views/backend/pc/order-new.php';
$views['order-print'] = 'views/station/pc-order-print.php';
$views['order-summary'] = 'views/backend/pc/order-summary.php';

if (isset($_GET['action'])) {
    switch ($_GET['action']) {
        case 'reload':
            PCOrderService::GetOrders();
            require $views['order-reload'];
            break;
        case 'new':
            PCPlanService::GetPCPlanNames();
            PCUserService::GetPCUserNames();
            if ($_GET['plans']->count() == 0) {
                echo "No Plantation City Plans Found";
            } else {
                require $views['order-new'];
            }
            break;
        case 'insert':
            PCOrderService::DoInsert();
            require $views['order-reload'];
            break;
        case 'update':
            PCOrderService::DoUpdate();
            require $views['order-reload'];
            break;
        case 'print':
            PCOrderService::PrintByOrderId($_GET['id']);
            require $views['order-print'];
            break;
        case 'summary':
            PCOrderService::GetSummary();
            require $views['order-summary'];
            break;
        case 'get-start-date':
            echo PCOrderService::GetStartDate();
            break;
    }
    return;
}

PCOrderService::GetOrders();
require $views['orders'];
?>
