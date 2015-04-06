<?php
require 'require-admin.php';
require 'models/impl/OrderService.php';

$views['orders'] = 'views/backend/hotspot/pending-orders.php';
$views['order-reload'] = 'views/backend/hotspot/pending-order-reload.php';

if (isset($_GET['action'])) {
    switch ($_GET['action']) {
        case 'reload':
            OrderService::GetPendingOrders();
            require $views['order-reload'];
            break;
    }
    return;
}

OrderService::GetPendingOrders();
require $views['orders'];
?>
