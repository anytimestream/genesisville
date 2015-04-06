<?php
require 'require-admin.php';
require 'models/impl/StationOrderService.php';
require 'models/impl/StationService.php';
require 'models/impl/SMSService.php';

$views['orders'] = 'views/backend/station/orders.php';
$views['order-reload'] = 'views/backend/station/order-reload.php';
$views['order-send-sms'] = 'views/backend/station/order-send-sms.php';
$views['order-summary'] = 'views/backend/station/order-summary.php';

if (isset($_GET['action'])) {
    switch ($_GET['action']) {
        case 'reload':
            StationOrderService::GetOrders();
            require $views['order-reload'];
            break;
        case 'send-sms':
            SMSService::SendStationTicketByOrderId($_GET['id'], false);
            require $views['order-send-sms'];
            break;
        case 'summary':
            StationOrderService::GetSummary();
            require $views['order-summary'];
            break;
    }
    return;
}

StationOrderService::GetOrders();
require $views['orders'];
?>
