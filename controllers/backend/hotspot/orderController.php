<?php
require 'require-admin.php';
require 'models/impl/OrderService.php';
require 'models/impl/SMSService.php';

$views['orders'] = 'views/backend/hotspot/orders.php';
$views['order-reload'] = 'views/backend/hotspot/order-reload.php';
$views['order-send-sms'] = 'views/backend/hotspot/order-send-sms.php';
$views['order-summary'] = 'views/backend/hotspot/order-summary.php';

if (isset($_GET['action'])) {
    switch ($_GET['action']) {
        case 'reload':
            OrderService::GetOrders();
            require $views['order-reload'];
            break;
        case 'send-sms':
            SMSService::SendHotspotTicketByOrderId($_GET['id'], false);
            require $views['order-send-sms'];
            break;
        case 'summary':
            OrderService::GetSummary();
            require $views['order-summary'];
            break;
    }
    return;
}

OrderService::GetOrders();
require $views['orders'];
?>
