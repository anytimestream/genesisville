<?php
require 'require-station.php';
require 'models/impl/StationOrderService.php';
require 'models/impl/StationService.php';

$views['orders'] = 'views/station/orders.php';
$views['order-print'] = 'views/station/order-print.php';
$views['order-summary'] = 'views/station/order-summary.php';

if (isset($_GET['action'])) {
    switch ($_GET['action']) {
        case 'print':
            StationOrderService::PrintTicketByOrderId($_GET['id']);
            require $views['order-print'];
            break;
        case 'summary':
            StationOrderService::GetStationSummary();
            require $views['order-summary'];
            break;
    }
    return;
}

StationService::GetStationReportBalance();
StationOrderService::GetStationOrders();
require $views['orders'];
?>
