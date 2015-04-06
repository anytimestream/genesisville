<?php

require 'require-station.php';
require 'models/impl/PrepaidOrderService.php';
require 'models/impl/PrepaidPlanService.php';
require 'models/impl/SMSService.php';
require 'models/impl/StationService.php';
require 'models/impl/AgentService.php';
require 'models/impl/PrepaidUserService.php';

if (!StationService::CanAcceptPrepaid()) {
    header('Location: ' . CONTEXT_PATH . '/login');
}

$views['orders'] = 'views/station/prepaid-orders.php';
$views['order-new'] = 'views/station/prepaid-order-new.php';
$views['order-reload'] = 'views/station/prepaid-order-reload.php';
$views['order-print'] = 'views/station/prepaid-order-print.php';
$views['order-summary'] = 'views/station/prepaid-order-summary.php';

if (isset($_GET['action'])) {
    switch ($_GET['action']) {
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
        case 'print':
            PrepaidOrderService::PrintByOrderId($_GET['id']);
            require $views['order-print'];
            break;
        case 'summary':
            PrepaidOrderService::GetStationSummary();
            require $views['order-summary'];
            break;
        case 'get-start-date':
            echo PrepaidOrderService::GetStartDate();
            break;
    }
    return;
}

StationService::GetStationReportBalance();
PrepaidOrderService::GetStationOrders();
require $views['orders'];
?>
