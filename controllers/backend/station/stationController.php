<?php
require 'require-admin.php';
require 'models/impl/StationService.php';

$views['stations'] = 'views/backend/station/stations.php';
$views['station-reload'] = 'views/backend/station/station-reload.php';
$views['station-new'] = 'views/backend/station/station-new.php';

if (isset($_GET['action'])) {
    switch ($_GET['action']) {
        case 'reload':
            StationService::GetStations();
            require $views['station-reload'];
            break;
        case 'update':
            StationService::DoUpdate();
            require $views['station-reload'];
            break;
        case 'new':
            require $views['station-new'];
            break;
        case 'insert':
            StationService::DoInsert();
            require $views['station-reload'];
            break;
        case 'delete':
            StationService::DoDelete();
            break;
    }
    return;
}

StationService::GetStations();
require $views['stations'];
?>
