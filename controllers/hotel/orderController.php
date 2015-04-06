<?php
require 'require-hotel.php';
require 'models/impl/HotelOrderService.php';
require 'models/impl/HotelService.php';

$views['orders'] = 'views/hotel/orders.php';
$views['order-print'] = 'views/hotel/order-print.php';
$views['order-summary'] = 'views/hotel/order-summary.php';

if (isset($_GET['action'])) {
    switch ($_GET['action']) {
        case 'print':
            HotelOrderService::PrintTicketByOrderId($_GET['id']);
            require $views['order-print'];
            break;
        case 'summary':
            HotelOrderService::GetHotelSummary();
            require $views['order-summary'];
            break;
    }
    return;
}

HotelService::GetHotelMonthlyPoints();
HotelOrderService::GetHotelOrders();
require $views['orders'];
?>
