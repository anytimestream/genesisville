<?php
require 'require-admin.php';
require 'models/impl/StationRemittanceService.php';
require 'models/impl/StationService.php';

$views['remittance'] = 'views/backend/station/remittance.php';
$views['remittance-reload'] = 'views/backend/station/remittance-reload.php';
$views['remittance-new'] = 'views/backend/station/remittance-new.php';

if (isset($_GET['action'])) {
    switch ($_GET['action']) {
        case 'reload':
            StationRemittanceService::GetRemittance();
            require $views['remittance-reload'];
            break;
        case 'update':
            StationRemittanceService::DoUpdate();
            require $views['remittance-reload'];
            break;
        case 'new':
            StationService::GetStationNames();
            require $views['remittance-new'];
            break;
        case 'insert':
            StationRemittanceService::DoInsert();
            require $views['remittance-reload'];
            break;
    }
    return;
}

StationRemittanceService::GetRemittance();
require $views['remittance'];
?>
