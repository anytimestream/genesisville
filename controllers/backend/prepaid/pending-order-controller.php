<?php

require 'require-admin.php';
require 'models/impl/PrepaidOrderService.php';
require 'models/impl/SMSService.php';

$views['orders'] = 'views/backend/prepaid/pending-orders.php';
$views['order-reload'] = 'views/backend/prepaid/pending-order-reload.php';

if (isset($_GET['action'])) {
    switch ($_GET['action']) {
        case 'reload':
            PrepaidOrderService::GetPendingOrders();
            require $views['order-reload'];
            break;
        case 'accept':
            PrepaidOrderService::Accept();
            break;
    }
    return;
}

PrepaidOrderService::GetPendingOrders();
require $views['orders'];
?>
