<?php
require 'require-admin.php';
require 'models/impl/HotelOrderService.php';
require 'models/impl/HotelService.php';
require 'models/impl/SMSService.php';

$views['orders'] = 'views/backend/hotel/orders.php';
$views['order-reload'] = 'views/backend/hotel/order-reload.php';
$views['order-send-sms'] = 'views/backend/hotel/order-send-sms.php';
$views['order-summary'] = 'views/backend/hotel/order-summary.php';

if (isset($_GET['action'])) {
    switch ($_GET['action']) {
        case 'reload':
            HotelOrderService::GetOrders();
            require $views['order-reload'];
            break;
        case 'send-sms':
            SMSService::SendHotelTicketByOrderId($_GET['id'], false);
            require $views['order-send-sms'];
            break;
        case 'summary':
            HotelOrderService::GetSummary();
            require $views['order-summary'];
            break;
    }
    return;
}

HotelOrderService::GetOrders();
require $views['orders'];
?>
