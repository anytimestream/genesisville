<?php
require 'require-admin.php';
require 'models/impl/SMSGatewayService.php';

$views['gateways'] = 'views/backend/management/sms-gateways.php';
$views['gateways-reload'] = 'views/backend/management/sms-gateways-reload.php';

if (isset($_GET['action'])) {
    switch ($_GET['action']) {
        case 'reload':
            SMSGatewayService::GetGateways();
            require $views['gateways-reload'];
            break;
        case 'update':
           SMSGatewayService::DoUpdate();
           require $views['gateways-reload'];
            break;
    }
    return;
}

SMSGatewayService::GetGateways();
require $views['gateways'];
?>
